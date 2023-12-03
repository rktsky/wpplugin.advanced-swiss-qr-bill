<?php

	defined( 'ABSPATH' ) OR exit;
	
	class Advanced_Swiss_Qr_Bill_Activator {

	    public static function on_activation() {
	        if ( ! current_user_can( 'activate_plugins' ) )
	            return;
	        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	        check_admin_referer( "activate-plugin_{$plugin}" );

	        # Uncomment the following line to see the function in action
	        # exit( var_dump( $_GET ) );
	    }
	
	    public static function on_deactivation() {
	        if ( ! current_user_can( 'activate_plugins' ) )
	            return;
	        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	        check_admin_referer( "deactivate-plugin_{$plugin}" );
	
	        # Uncomment the following line to see the function in action
	        # exit( var_dump( $_GET ) );
	    }
	
	    public static function on_uninstall() {
	        if ( ! current_user_can( 'activate_plugins' ) )
	            return;
	        check_admin_referer( 'bulk-plugins' );
	
	        // Important: Check if the file is the one
	        // that was registered during the uninstall hook.
	        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
	            return;
	
	        # Uncomment the following line to see the function in action
	        # exit( var_dump( $_GET ) );
	    }

		private static function check_requirements() {
	
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
