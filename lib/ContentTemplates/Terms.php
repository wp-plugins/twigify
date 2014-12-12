<?php
namespace ContentTemplates;

class Terms {

  function terms($taxonomy, $args = array()) {
    $terms = wp_get_object_terms(get_the_ID(), $taxonomy, $args);
    return $terms;
  }

  function link($term_id, $taxonomy) {
    return get_term_link($term_id, $taxonomy);
  }

}
