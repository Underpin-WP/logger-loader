<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin_Logger\Loaders;

use Underpin_Logger\Factories\Log_Item;
use Underpin_Logger\Abstracts\Registries\Event_Registry;
use WP_Error;
use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logger
 * Houses methods to manage event logging
 *
 * @since   1.0.0
 * @package Underpin\Loaders
 */
class Logger extends Event_Registry {

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'error', [
			'class' => 'Underpin_Logger\Factories\Event_Type_Instance',
			'args'  => [ [
				'type'              => 'error',
				'write_to_log'      => true,
				'group'             => 'core',
				'description'       => 'Intended to log events when something goes wrong.',
				'name'              => "Error",
				'include_backtrace' => true,
				'purge_frequency'   => 7,
			] ],
		] );

		if ( underpin()->is_debug_mode_enabled() ) {

			$this->add( 'warning', [
				'class' => 'Underpin_Logger\Factories\Event_Type_Instance',
				'args'  => [ [
					'type'         => 'warning',
					'write_to_log' => false,
					'description'  => 'Intended to log events when something seems wrong.',
					'name'         => 'Warning',
					'group'        => 'core',
				] ],
			] );

			$this->add( 'notice', [
				'class' => 'Underpin_Logger\Factories\Event_Type_Instance',
				'args'  => [ [
					'type'         => 'notice',
					'write_to_log' => false,
					'description'  => 'Posts informative notices that do not necessarily mean anything is wrong.',
					'name'         => 'Notice',
					'group'        => 'core',
				] ],
			] );

		}
	}

	/**
	 * Gathers errors from a set of variables.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed ...$items
	 *
	 * @return WP_Error
	 */
	public static function gather_errors( ...$items ) {
		$errors = new WP_Error();
		$items  = func_get_args();
		foreach ( $items as $item ) {
			self::extract( $errors, $item );
		}

		return $errors;
	}

	/**
	 * Appends errors to a WP_Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error          $error    The error to append to. Passed by reference.
	 * @param Log_Item|WP_Error $log_item The log item to append. If this has multiple errors, it will append all of them.
	 *
	 * @return void
	 */
	public static function extract( WP_Error &$error, $log_item ) {

		// Transform the log item into a WP_Error, if it is a Log_item
		if ( $log_item instanceof Log_Item ) {
			$log_item = $log_item->error();
		}

		// Append the error, if it is an error.
		if ( $log_item instanceof WP_Error ) {
			foreach ( $log_item->get_error_codes() as $code ) {
				$error->add( $code, $log_item->get_error_message( $code ), $log_item->get_error_data( $code ) );
			}
		}
	}

	/**
	 * Retrieves a list of all capabilities of all logged items.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function capabilities() {
		$capabilities = [];

		foreach ( (array) $this as $key => $item ) {
			$item = $this->get( $key );
			if ( ! is_wp_error( $item ) ) {
				$capabilities = array_merge( $capabilities, $item->capabilities );
			}
		}

		return array_unique( $capabilities );
	}

}