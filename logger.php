<?php

use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add this loader.
add_action( 'underpin/before_setup', function ( $file, $class ) {
	require_once( plugin_dir_path( __FILE__ ) . 'lib/loaders/Logger.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/Event_Type.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Event_Type_Instance.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Basic_Logger_Middleware.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Include_Backtrace_Middleware.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/Writer.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Basic_Logger.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Log_Item.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Writer_Instance.php' );

	// Add the logger.
	Underpin\underpin()->get( $file, $class )->loaders()->add( 'logger', [
		'registry' => 'Underpin_Logger\Loaders\Logger',
	] );

}, 5, 2 );