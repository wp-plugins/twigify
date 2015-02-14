<?php
/*
	Plugin Name: Twigify Content Templates
	Plugin URI: http://wordpress.org/extend/plugins/twigify-twigify
	Description: Allows you to create content templates that will conditionally override the content of a post or page or customer post type
	Author: Mike Van Winkle
	Version: 1.1.2-beta
	Author URI: http://mikevanwinkle.com
	Text Domain: twigify
	Domain Path: /lang
*/
define('CT_VERSION', '1.1.2-beta');

// register autoloader
spl_autoload_register('__ct_autoload');
function __ct_autoload($class) {
	$class = str_replace("\\","/", $class);
	$class = __DIR__."/lib/$class.php";
	if (file_exists($class)) {
		require_once($class);
	}
}

// include symfony autoloader
require_once(plugin_dir_path(__FILE__).'/vendor/autoload.php');
require_once(plugin_dir_path(__FILE__).'/lib/meta-boxes.php');

// activation stuff
register_activation_hook( __FILE__, array('ContentTemplatesPlugin','activate'));

ContentTemplatesPlugin::instance();

class ContentTemplatesPlugin {
	static $instance;
	private $settings;
	private $slug = 'twigify-options';
	private $views_dir = '/views';
	private $cache_dir = '/cache';
	private $version = '0.1';
	static $textdomain = 'twigify';

	private function __construct() {
		$this->get_settings();
		$this->admin_init();
		add_action('admin_menu', array( $this, 'admin_menu' ));
		add_action('admin_init', array( $this, 'admin_init' ));
		add_action('init', array($this,'init'));
		add_action('add_meta_boxes', array('\ContentTemplates\Rules', 'add_meta_box'));
		add_action('save_post', array('\ContentTemplates\Rules', 'save_post'));
		add_action('the_content', array('\ContentTemplates\View','hook'), -1);
		add_action('add_admin_bar_menus', array('\ContentTemplates\AdminBar','modify'), 200);
		add_action('wp_ajax_twigifysettings', array('\ContentTemplates\Ajax','save_settings'));
		add_action('load-twigify_page_twigify-settings', array($this,'admin_pre_headers_sent'));
		add_action('admin_notices', array('\ContentTemplates\Alert','render'));
	}

	static function activate() {
		if ( self::settings() ) {
		}
	}

	function admin_pre_headers_sent() {
		$settings = \ContentTemplates\Settings::instance();
		if (isset($_POST['twigify'])) {
			$to_save = $_POST['twigify'];
			$newsettings = array_merge( $settings->get(), $to_save );
			$this->settings = $settings->set($newsettings)->save();
			\ContentTemplates\Alert::queue(__("Twigify settings updated!", 'twigify'), 'updated');
		}
	}

	function admin_init() {
		// current unused
	}

	function admin_menu() {
		add_menu_page('TWIGify', 'TWIGify', 'administrator', 'twigify', array($this, 'settings_page'), 'dashicons-media-code' );
		add_submenu_page('twigify', 'Settings', 'Settings', 'administrator', 'twigify-settings', array($this, 'settings_page'), 'dashicons-media-code' );
	}

	function init() {
		$labels = array(
			'name'               => _x( 'Templates', 'post type general name', 'twigify' ),
			'singular_name'      => _x( 'Template', 'post type singular name', 'twigify' ),
			'menu_name'          => _x( 'Templates', 'admin menu', 'twigify' ),
			'name_admin_bar'     => _x( 'Template', 'add new on admin bar', 'twigify' ),
			'add_new'            => _x( 'Add New', 'template', 'twigify' ),
			'add_new_item'       => __( 'Add New Template', 'twigify' ),
			'new_item'           => __( 'New Template', 'twigify' ),
			'edit_item'          => __( 'Edit Template', 'twigify' ),
			'view_item'          => __( 'View Template', 'twigify' ),
			'all_items'          => __( 'Templates', 'twigify' ),
			'search_items'       => __( 'Search Templates', 'twigify' ),
			'parent_item_colon'  => __( 'Parent Templates:', 'twigify' ),
			'not_found'          => __( 'No templates found.', 'twigify' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'twigify' )
		);

		$settings = \ContentTemplates\Settings::instance();
		$roles = @$settings->get('roles') ? array_values($settings->get('roles')) : array('administrator');
		$params = array(
			'labels'		     			=> $labels,
			'public'             	=> false,
			'publicly_queryable' 	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> 'twigify',
			'exclude_from_search' => true,
			'supports'						=> array('title','editor','revisions'),
			'capability_types'		=> array('ctemplates'),
			'meta_map_cap'				=> true,
		);
		register_post_type('ctemplates', $params);

		// do the meta mapping thing
		foreach ( $roles as $role ) {
			$role = get_role($role);
			$role->add_cap('read_ctemplates');
			$role->add_cap('edit_ctemplates');
			$role->add_cap('delete_ctemplates');
			$role->add_cap('publish_ctemplates');
		}

		// this removes the tinymce from template pages to prevent parsing confusions
		add_filter( 'user_can_richedit', function() {
			global $post;
			if ( 'ctemplates' == $post->post_type ) {
				return false;
			}
			return true;
		});

	}

	static function settings() {
		$plugin = self::instance();
		return $plugin->get_settings();
	}

	public function get_settings() {
		if ( !$this->settings )
			$this->settings = \ContentTemplates\Settings::merged();
		return $this->settings;
	}

	public function view($file_or_string, $data) {
		$view = \ContentTemplates\View::instance();
		return $view->render($file_or_string, $data);
	}

	public function get($property) {
		return $this->$property;
	}

	static function settings_page() {
		global $wp_roles;
		$templates = new WP_Query(array('post_type'=>'ctemplates'));
		$views = \ContentTemplates\View::instance();
		$data = array();
		$data['settings'] = self::settings();
		if (!isset($data['settings']['roles'])) {
			$data['settings']['roles'] = array();
		}
		$roles = $wp_roles->roles;
		foreach ($roles as $name=>$role) {
				if (in_array($name, array_values($data['settings']['roles']))) {
					$roles[$name]['checked'] = "checked=true";
				}
		}
		$data['roles'] = $roles;
		echo $views->render('settings.html.php', $data);
	}

	static function instance() {
		if ( !self::$instance ) {
			self::$instance = new ContentTemplatesPlugin();
		}
		return self::$instance;
	}

	static function get_views_dir() {
		$plugin = ContentTemplatesPlugin::instance();
		$dir = sprintf('%s%s',__DIR__,$plugin->get('views_dir'));
		return $dir;
	}

	static function get_cache_dir() {
		$plugin = ContentTemplatesPlugin::instance();
		$dir = sprintf('%s%s',__DIR__,$plugin->get('cache_dir'));
		return $dir;
	}

	static function textdomain() {
		return self::$textdomain;
	}
}
