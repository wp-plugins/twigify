<?php
namespace ContentTemplates;

use \ContentTemplates\View;

class Alert {
  static $instance;
  public $alerts = array();

  public static function instance() {
    if(!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function add($alert,$classes)
  {
    $this->alerts[] = array( 'message' => $alert, 'classes' => $classes );
    return $this->alerts;
  }

  public static function queue($message, $classes) {
    $alerts = self::instance();
    $alerts->add($message, $classes);
    return $alerts->alerts;
  }

  public static function render() {
    $alerts = self::instance();
    $views = View::instance();
    foreach( $alerts->alerts as $alert ) {
      echo $views->render('alert.html.php', (array) $alert);
    }
  }

}
