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
}
