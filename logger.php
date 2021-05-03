<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add this loader.
add_action( 'underpin/before_setup', function ( $class ) {
	if ( 'Underpin\Underpin' === $class ) {
		require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/registries/Event_Registry.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/loaders/Logger.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/Event_Type.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Event_Type_Instance.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/Writer.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Basic_Logger.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Log_Item.php' );

		// Add the logger.
		Underpin\underpin()->loaders()->add( 'logger', [
			'instance' => 'Underpin_Logger\Abstracts\Event_Type',
			'registry' => 'Underpin_Logger\Loaders\Logger',
		] );
	}
}, 5 );