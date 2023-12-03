<?php
/**
 *
 * @since             1.0.0
 * @package           WC_Swiss_Qr_Bill
 *
 * Plugin Name:       Advanced Swiss QR Bill
 * Description:       Advanced Swiss QR Bill
 * Version:           1.0.0
 * Author:            Rocketsky GmbH
 * Author URI:        https://www.rocketsky.ch/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       swiss-qr-bill
 * Domain Path:       /languages
 * WC requires at least: 2.6
 * WC tested up to: 5.7.1
 */

// If this file is called directly, abort.
if ( !defined('WPINC') ) {
    die;
}

/**
 * Currently plugin version.
 */
define('ADVANCED_SWISS_QR_BILL_VER', '1.0.0');
/**
 * Root level plugin file
 */
if ( !defined( 'WC_SWISS_QR_BILL_FILE' ) ) {
    define( 'WC_SWISS_QR_BILL_FILE', __FILE__ );
}
/**
 * Define plugin upload directory
 */
if ( !defined( 'WC_SWISS_QR_BILL_UPLOAD_DIR' ) ) {
    $upload_dir = wp_upload_dir();
    define( 'WC_SWISS_QR_BILL_UPLOAD_DIR', $upload_dir['basedir'] . '/advanced-swiss-qr-bill/' );
}


require_once plugin_dir_path(__FILE__) . 'includes/advanced-swiss-qr-bill-activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wc-swiss-qr-bill.php';

register_activation_hook(   __FILE__, array( 'Advanced_Swiss_Qr_Bill_Activator', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Advanced_Swiss_Qr_Bill_Activator', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'Advanced_Swiss_Qr_Bill_Activator', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'Advanced_Swiss_Qr_Bill', 'init' ) );

class Advanced_Swiss_Qr_Bill {
    protected static $instance;

    public static function init() {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

    public function __construct() {
        add_action( current_filter(), array( $this, 'load_files' ), 30 );
    }

    public function load_files() {
	    require_once plugin_dir_path(__FILE__) . 'includes/advanced-swiss-qr-bill-activator.php';
/*
        foreach ( glob( plugin_dir_path( __FILE__ ).'includes/*.php' ) as $file )
            include_once $file;
*/
    }
}


/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wc_swiss_qr_bill() {

    $plugin = new WC_Swiss_Qr_Bill();
    $plugin->run();

}

add_action('plugins_loaded', 'run_wc_swiss_qr_bill');

add_action('before_woocommerce_init', 'asqb_before_woocommerce_hpos');

function asqb_before_woocommerce_hpos() { 

        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) { 

                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true ); 

        }

}