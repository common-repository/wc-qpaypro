<?php 
/**
* @since 1.0.0
* @package wp-qpaypro-woocommerce
* @author xicoofficial
* 
* Plugin Name: Payment Gateway for QPayPro on WooCommerce
* Plugin URI: https://digitallabs.agency
* Description: WooCommerce custom payment gateway integration with QPayPro.
* Version: 1.0.0
* Author: digitallabs, XicoOfficial
* Author URI: https://digitallabs.agency
* Licence: GPL-3.0+
* Text Domain: wp-qpaypro-woocommerce
* Domain Path: /languages
* WC requires at least: 3.0.0
* WC tested up to: 8.8
*/
 
 use qpaypro_woocommerce\Admin\Util;


// If this file is accessed directory, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( trailingslashit( dirname( __FILE__ ) ) . 'admin/util/dl-php-logger/class-dl-logger.php' );

// Include the autoloader so we can dynamically include the rest of the classes.
require_once( trailingslashit( dirname( __FILE__ ) ) . 'inc/autoloader.php' );

require_once('admin/wp-filters.php');
require_once('admin/wp-actions.php');
// require_once('admin/wp-functions.php');



defined( 'ABSPATH' ) or exit;
// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

// define('dl_qpp_staging', false);
function dl_qpp_load_textdomain() {
    load_plugin_textdomain( 'wp-qpaypro-woocommerce', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'dl_qpp_load_textdomain' );
