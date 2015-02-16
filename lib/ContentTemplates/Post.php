<?php
namespace ContentTemplates;

/** 
 * Functions and properties related to the current user object
 */

class Post {
  public $post;

  public function field($field = "*") {
    if (!$this->post) {
      $this->post = get_post();
    }
    if ($field === "*") {
      echo "<pre>";
      var_dump($this->post);
      echo "</pre>";
    } elseif( isset($this->user->$field) ) {
      return $this->user->$field;
    }
    return false;
  }
 
}
