<?php
namespace ContentTemplates;

use \ContentTemplatesPlugin;

class Rules {

	public static function add_meta_box() {
			add_meta_box(
				"ct_rules_builder",
				"Rules",
				array( '\ContentTemplates\Rules', 'field'),
				'ctemplates',
				'normal',
				'high'
			);
	}

	public static function field($post)
	{
		$rules = get_post_meta($post->ID,'rules',false);

		$plugin = ContentTemplatesPlugin::instance();
		$options = array(
			array( 'label' => 'Post Type', 'value' => 'post_type' ),
			array( 'label' => 'Taxonomy', 'value' => 'taxonomy')
		);

		echo $plugin->view('form.html.php', array('options'=>$options));
	}

	public static function save_post($post) {
		return $post;

	}

}
