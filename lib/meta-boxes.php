<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'ct_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ct_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ct_';
	/**
	 * Sample metabox to demonstrate each field type included
	*/
	$post_types = get_post_types();
	unset($post_types['ctemplates']);

	$options = array();
	$templates = new WP_Query(array('post_type'=>'ctemplates', 'post_status' => 'publish'));
	$items = $templates->get_posts();
	$options[''] = '--none--';
	foreach( $items as $item ) {
		$options[$item->ID] = $item->post_title;
	}
	$prefix = 'ct_';

	$meta_boxes['ct_post_metabox'] = array(
		'id'        => 'Content Template',
		'title'     => __( 'Content Template', 'content-templates' ),
		'desc'			=> 'You can set up global rules under the Content Templates >> Rules menu, or override for this template here',
		'pages'     => apply_filters("ct_available_post_types", $post_types),
		'context'   => 'side',
		'priority'  => 'high',
		'show_names'=> true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'    => array(
			array(
				'name'      => __( 'Override', 'cmb' ),
				'desc'      => __( 'Override content template to use', 'cmb' ),
				'id'        => "{$prefix}override_template",
				'type'      => 'select',
				'options'	=> $options,
			),
		)
	);

	$meta_boxes['ct_rules_metabox'] = array(
    'id'          => "{$prefix}repeat_group",
    'type'        => 'repeatable-group',
		'class'				=> 'repeatable-group',
		'title'				=> __("Template Rules",'content-templates'),
    'desc' => __( 'Generates reusable form entries', 'content-templates' ),
		'pages'				=> array('ctemplates'),
    'options'     => array(
        'group_title'   => __( 'Entry {#}', 'content-templates' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Entry', 'content-templates' ),
        'remove_button' => __( 'Remove Entry', 'content-templates' ),
        'sortable'      => true, // beta
    ),
		'priority'	=> 'high',
		'context'		=> 'normal',
		'show_names'=> true,
    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
    'fields'      => array(
        array(
          'name' => 'Rule Context',
          'id'   => 'rule_context',
          'group' => 'context', // Repeatable fields are supported w/in repeatable groups (for most types)
					'type'        => 'repeatable-group',
					'fields' => array(
						array(
							'type' => 'select',
							'id'   => 'rule_context_rule',
							'name' => 'Rule Context',
							'options' => array(
								'taxonomy' => 'Taxonomy',
								'post_type' => 'Post type',
							),
						),array(
							'type' => 'text',
							'id'   => 'rule_context_value',
							'name' => 'Rule Context',
						),
					),
					'options' => array(
						'add_button' => 'Add rule',
						'remove_button' => 'Remove Rule',
					)
        ),
    	),
		);

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) ) {
		require_once plugin_dir_path(__FILE__).'/../vendor/Custom-Metaboxes-and-Fields-for-WordPress-master/init.php';
	}
}
