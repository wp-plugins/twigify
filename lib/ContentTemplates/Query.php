<?php
namespace ContentTemplates;
use \WP_Query;

class Query {

  function posts($query_string) {
    $posts = new WP_Query(wp_parse_args($query_string));
    if ( $posts->have_posts() ) {
      return $posts->get_posts();
    }
  }

}
