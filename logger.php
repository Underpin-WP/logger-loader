<?php

use function Underpin\underpin;

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
		require_once( plugin_dir_path( __FILE__ ) . 'lib/decisions/event-type-purge-frequency/Event_Type.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/decisions/event-type-purge-frequency/Event_Type_Purge_Frequency.php' );

		// Add the logger.
		underpin()->loaders()->add( 'logger', [
			'registry' => 'Underpin_Logger\Loaders\Logger',
		] );

		// Setup Cron jobs
		underpin()->cron_jobs()->add( 'purge_logs', 'Underpin\Cron_Jobs\Purge_Logs' );

		// Setup Decision Lists
		underpin()->decision_lists()->add( 'event_type_purge_frequency', 'Underpin_Logger\Decisions\Event_Type_Purge_Frequency\Event_Type_Purge_Frequency' );
	}
}, 5 );