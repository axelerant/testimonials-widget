# Aihrus Framework helper for WordPress plugins

## Features

* Donation links
* Helper methods
* Licensing
* Notifications

## Usage

1. Change to plugin directory that's a Git clone
1. Load and link the external library
	```
	git remote add aihrus https://github.com/michael-cannon/aihrus-framework.git
	git fetch aihrus 
	git subtree add -P lib/aihrus aihrus master
	git commit -a -m "Readd aihrus framework"
	git push origin master
	```
1. Link plugin to libary
	```
	require_once WPSP_PLUGIN_DIR_LIB . '/aihrus/class-aihrus-common.php';
1. Extend plugin class to library
	```
	class Wordpress_Starter extends Aihrus_Common {
	```
1. Add class static members
	```
	public static $class;
	public static $notice_key;
	```
1. Set notices…
	```
	…
	if ( $bad_version )
		self::set_notice( 'notice_version' );
	…
	public static function notice_version( $free_base = null, $free_name = null, $free_slug = null, $free_version = null, $item_name = null ) {
		$free_base    = self::FREE_PLUGIN_BASE;
		$free_name    = 'Testimonials';
		$free_slug    = 'testimonials-widget';
		$free_version = self::FREE_VERSION;
		$item_name    = self::ITEM_NAME;

		parent::notice_version( $free_base, $free_name, $free_slug, $free_version, $item_name );
	}
	```
1. Update the external library
	```
	git subtree pull -P lib/aihrus aihrus master
	```
1. Update the plugin repository
	```
	git push origin master
	```
