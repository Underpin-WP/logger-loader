<?php

use Underpin\Abstracts\Underpin;
use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add this loader.
Underpin::attach( 'setup', new \Underpin\Factories\Observer( 'logger', [
	'update' => function ( Underpin $plugin ) {
	require_once( plugin_dir_path( __FILE__ ) . 'lib/loaders/Logger.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/abstracts/Event_Type.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Event_Type_Instance.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'lib/factories/Log_Item.php' );

	// Add the logger.
	$plugin->loaders()->add( 'logger', [
		'class' => 'Underpin_Logger\Loaders\Logger',
	] );
	},
] ) );