<?php
namespace ContentTemplates;
use \ContentTemplates\Settings;

class Ajax {

  public function __construct() {

  }

  public static function save_settings($data) {
    $data = $_POST['twigify'];
var_dump($_POST);
die();
    $settings = Settings::instance();
    $newsettings = array_merge( $settings->get(), $data );
    $settings->set($newsettings)->save();
    exit;
  }

  public static function success($message) {
    $data = array(
      'success' => 1,
      'message' => $message
    );
    header("Content-type: application/json");
    print json_encode($data);
  }

}
