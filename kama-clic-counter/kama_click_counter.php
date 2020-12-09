<?php
/**
 * Plugin Name: Kama Click Counter - Custom
 * Description: Count clicks on any link all over the site. Creates beautiful file download block in post content - use shortcode [download url="any file URL"]. Has widget of top clicks/downloads.
 *
 * Text Domain: kama-clic-counter-custom
 * Domain Path: /languages
 *
 * Author: Kama
 * Author URI: https://wp-kama.com
 * Plugin URI: https://wp-kama.com/?p=77
 *
 * Version: 99999.6.8
 */

defined('ABSPATH') || exit;

$data = get_file_data( __FILE__, array('ver'=>'Version') );

define( 'KCC_VER',  $data['ver'] );
define( 'KCC_PATH', plugin_dir_path(__FILE__) );
define( 'KCC_URL',  plugin_dir_url(__FILE__) );
define( 'KCC_NAME', basename(KCC_PATH) );

require_once KCC_PATH . 'class.KCCounter.php';
require_once KCC_PATH . 'class.KCCounter_Admin.php';
require_once KCC_PATH . '_backward-compatibility.php';


## init
add_action( 'plugins_loaded', 'KCCounter_init' );
function KCCounter_init(){
	load_plugin_textdomain( 'kama-clic-counter', false, KCC_NAME .'/languages' );

	KCCounter();
}

## get instance
function KCCounter(){
	return KCCounter::instance();
}

register_activation_hook( __FILE__, function(){
	KCCounter()->activation();
} );


