<?php
namespace ContentTemplates;

use \ContentTemplates\Rules;
use \ContentTemplatesPlugin as Plugin;
use \Twig_Autoloader,
  Twig_Loader_String,
  Twig_Loader_Filesystem,
  Twig_Environment;
use \ContentTemplates\Terms;
use \ContentTemplates\Post;
use \ContentTemplates\Functions;

class View {
  private $cache;
  private static $instance;
  private $env;

  public function __construct() {
    require_once __DIR__.'/../../vendor/twig/twig/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();
  }

  // prevents duplicate instances
  static public function instance() {
    if ( null === self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function render($content, $data) {
    if (file_exists(Plugin::get_views_dir()."/$content")) {
      return $this->render_from_file($content, $data);
    } else {
      return $this->render_from_string($content, $data);
    }
  }

  private function render_from_file($file, $data) {
    $loader = new Twig_Loader_Filesystem(Plugin::get_views_dir());
    $twig = new Twig_Environment($loader, array());
    $template = $twig->loadTemplate($file);
    $rendered = $template->render($data);
    return $rendered;
  }

  private function render_from_string($string, $data) {
    if (!$this->env) {
      $loader = new Twig_Loader_String();
      $this->env = new Twig_Environment($loader);
      $this->load_functions();
    }
    $rendered = $this->env->render($string, $data);
    return $rendered;
  }

  private function load_functions() {
    $this->env->addGlobal( 'terms', new Terms() );
    $this->env->addGLobal( 'query', new Query() );
    $this->env->addGlobal( 'function', new Functions() );
  }

  public static function hook($content,$post=null) {
    global $post;
    if (is_admin())
      return $content;
    $hash = md5($content);
    $cache_content = wp_cache_get($hash, 'posts');
    if ( $cache_content ) {
      return $cache_content;
    }
    $template_id = get_post_meta(get_the_ID(), 'ct_override_template', true);
    $view = View::instance();
    $data = array();
    $metas = get_post_custom($post->ID);
    foreach ((array)$post as $key => $value) {
      $data[$key] = $value;
    }
    foreach ($metas as $key => $value) {
      if (count($value) < 2) {
        $value = $value[0];
      }
      $data[$key] = $value;
    }

    // first render the post_content

    $data['post_content'] = $content = $view->render_from_string($post->post_content, $data);
    // now render the other templates
    $templates = apply_filters('content_templates_default', array(), $data);
    if( is_numeric($template_id) ) {
      $templates[] = get_post($template_id);
    }

    foreach ($templates as $template) {
      $content = $view->render_from_string($template->post_content, $data);
    }

    $content = html_entity_decode($content);
    wp_cache_set($hash, $content, 'posts');
    return $content;
  }

}
