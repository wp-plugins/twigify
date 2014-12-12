<?php
namespace ContentTemplates;

class AdminBar {

  static function modify() {
    global $post, $wp_admin_bar;
    if ( is_single() OR is_numeric($_REQUEST['post']) ) {
      $post_id = @$_REQUEST['post'] ? $_REQUEST['post'] : get_the_ID();
      $template_id = get_post_meta($post_id, 'ct_override_template', true);
      $wp_admin_bar->add_group(array(
        'id' => 'ct-admin-bar-group',
        'title' => 'Templates'
      ));
      $wp_admin_bar->add_node( array(
        'id'  => 'zct-admin-node',
        'title' => 'Edit Template',
        'href'  => get_edit_post_link($template_id),
        'group' => 'ct-admin-bar-group',
      ));
    }
  }

}
