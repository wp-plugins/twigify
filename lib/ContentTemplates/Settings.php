<?php
namespace ContentTemplates;

class Settings {
	private $settings;
	static $instance;

	public function __construct() {
		$this->settings = array_merge( self::defaults(), get_option('twigify-settings', array()) );
		self::$instance = $this;
	}

	static public function instance() {
		if ( !self::$instance ) {
			new Settings();
		}
		return self::$instance;
	}

	public static function defaults()
	{
		return array(
			'capability' => array('administrator'),
		);
	}

	public static function merged()
	{
		$settings = array_merge( self::defaults(), get_option('twigify-settings', array()) );
		return $settings;
	}

	public function get($key=null) {
		if( !$key ) {
			return $this->settings;
		}
		return $this->settings[$key];
	}

	public function save() {
		update_option('twigify-settings',$this->settings);
		return $this->settings;
	}

	public function set($settings) {
		foreach( $settings as $key => $value) {
			$this->settings[$key] = $value;
		}
		return $this;
	}


}
