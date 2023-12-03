<?php
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes
 */
class Advanced_Swiss_Qr_Bill_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    
    public static function activate() {

        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

		$req = $this->check_requirements();

        if ( !is_dir(WC_SWISS_QR_BILL_UPLOAD_DIR) ) {
            mkdir(WC_SWISS_QR_BILL_UPLOAD_DIR, 0700);
        }

    }

	private function check_requirements() {

	    $min_wp  = '5.0';
	    $min_php = '7.4';
	    $exts    = array( 'WooCommerce' );
	    $countries = array('CH', 'LI');
	
	    // Check for WordPress version
	    if ( version_compare( get_bloginfo('version'), $min_wp, '<' ) ) {
            $this->deactivate();
            $this->die( __( 'WordPress version is too old. Minimum: ', 'swiss-qr-bill' ) . $min_wp );
	    }
	
	    // Check the PHP version
	    if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
            $this->deactivate();
            $this->die( __( 'PHP version is too old. Minimum: ', 'swiss-qr-bill' ) . $min_php );
	    }
	
	    // Check Class existance
	    foreach ( $exts as $ext ) {
	        if ( ! class_exists( $ext ) ) {
	            $this->deactivate();
	            $this->die( __( 'Dependency doesn\'t exist: ', 'swiss-qr-bill' ) . $ext );
	        }
	    }

        $woocommerce_default_country = get_option( 'woocommerce_default_country', '' );
        $country = explode( ':', $woocommerce_default_country )[0];

		if ( !in_array( strtoupper( $country ), $countries ) ) {
            $this->deactivate();
            $this->die( __( 'Wrong country set in WooCommerce. Please set it for Schweiz or Liechtenstein.', 'swiss-qr-bill' ) );
		}

		return true;

	}

	private function deactivate() {

		deactivate_plugins( plugin_basename( __FILE__ ) );

	}

	private function die( $message ) {

		wp_die( __( $message, 'swiss-qr-bill' ), 'Plugin dependency check', array( 'back_link' => true ) );

	}

}
