<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add this loader.
add_action( 'underpin/before_setup', function ( $class ) {
	if ( 'Underpin\Underpin' === $class ) {
		require_once( plugin_dir_path( __FILE__ ) . 'Event_Registry.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Logger.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Event_Type.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Event_Type_Instance.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Writer.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Basic_Logger.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'Log_Item.php' );

		// Add the logger.
		Underpin\underpin()->loaders()->add( 'logger', [
			'instance' => 'Underpin_Logger\Abstracts\Event_Type',
			'registry' => 'Underpin_Logger\Loaders\Logger',
		] );
	}
}, 5 );