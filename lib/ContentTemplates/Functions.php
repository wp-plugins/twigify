<?php
namespace ContentTemplates;

class Functions {

  public function the_permalink($id=null) {
    return get_the_permalink($id);
  }

  public function the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
    return get_the_term_list( $id, $taxonomy, $before, $sep, $after);
  }

  public function the_post_thumbnail($id = null, $size = 'post-thumbnail', $attr = '' ) {
    return get_the_post_thumbnail( $id, $size, $attr);
  }

  public function get_bloginfo($key = null) {
    return get_bloginfo($key);
  }

  public function get_sidebar($name = null) {
    ob_start();
      get_sidebar($name);
    $sidebar = ob_get_clean();
    return $sidebar;
  }

  public function get_footer($name = null) {
    ob_start();
      get_footer($name);
    $footer = ob_get_clean();
    return $footer;
  }

  public function the_time($format = null) {
    ob_start();
      the_time($format);
    $time = ob_get_clean();
    return $time;
  }

  public function comments_popup_link($zero = false, $one = false, $more = false, $css_class = '', $none = false) {
    ob_start();
      comments_popup_link($zero, $one, $more, $css_class, $none);
    $comment_link = ob_get_clean();
    return $comment_link;
  }

  public function the_title($before = '', $after = '', $echo = true) {
    ob_start();
      the_title($before, $after, $echo);
    $title = ob_get_clean();
    return $title;
  }

  public function the_category($separator = '', $parents='', $post_id = false) {
    ob_start();
      the_category($separator, $parents, $post_id);
    $category = ob_get_clean();
    return $category;
  }

  public function the_author($deprecated = '', $deprecated_echo = true) {
    return get_the_author();
  }

  public function the_ID() {
    return get_the_ID();
  }

  public function edit_post_link($text = null, $before = '', $after = '', $id = 0) {
    ob_start();
      edit_post_link($text, $before, $after, $id);
    $link = ob_get_clean();
    return $link;
  }

  public function wp_list_bookmarks($args = '') {
    ob_start();
      wp_list_bookmarks($args);
    $list = ob_get_clean();
    return $list;
  }

  public function comments_template() {
    ob_start();
      comments_template();
    $comments = ob_get_clean();
    return $comments;
  }

  public function wp_list_pages($args = '') {
    ob_start();
      wp_list_pages($args);
    $pages = ob_get_clean();
    return $pages;
  }

  public function wp_list_categories($args = '') {
    ob_start();
      wp_list_categories($args);
    $cats = ob_get_clean();
    return $cats;
  }

  public function get_post_meta($post_id, $key = '', $single = false) {
    return get_post_meta($post_id, $key, $single);
  }

  public function posts_nav_link($sep = '', $prelabel = '', $nxtlabel = '') {
    ob_start();
      posts_nav_link($sep, $prelabel, $nxtlabel);
    $link = ob_get_clean();
    return $link;
  }

  public function get_search_form() {
    ob_start();
      get_search_form();
    $search = ob_get_clean();
    return $search;
  }

  public function custom($function,$args = array()) {
      return call_user_func_array($function, $args);
  }
}
