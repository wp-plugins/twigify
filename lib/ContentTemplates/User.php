<?php
namespace ContentTemplates;

/** 
 * Functions and properties related to the current user object
 */

class User {
  public $user;

  public function field($field = "*") {
    if (!$this->user) {
      $this->user = wp_get_current_user();
    }
    if ($field === "*") {
      echo "<pre>";
      var_dump($this->user);
      echo "</pre>";
    } elseif( isset($this->user->$field) ) {
      return $this->user->$field;
    }
    return false;
  }
 
}
