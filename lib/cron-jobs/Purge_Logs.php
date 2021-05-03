<?php
/**
 * Cron task to purge error logs.
 *
 * @since   1.0.0
 * @package Underpin\Cron
 */


namespace Underpin_Logger\Cron_Jobs;

use Underpin_Cron_Jobs\Abstracts\Cron_Job;
use function Underpin\Underpin;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Purge_Logs
 *
 * @since   1.0.0
 * @package Underpin\Cron
 */
class Purge_Logs extends Cron_Job {

	public $description = 'Purges logged events that are older than 30 days. This keeps the log files from becoming massive.';

	public $name = 'Purge Logs';

	/**
	 * Purge_Logs constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$frequency = apply_filters( 'underpin/cron/purge_logs/frequency', 'daily' );

		parent::__construct( 'underpin_logs', $frequency );
	}

	/**
	 * @inheritDoc
	 */
	function cron_action() {
		underpin()->logger()->cleanup();
	}
}