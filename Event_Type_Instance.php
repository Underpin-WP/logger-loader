<?php
/**
 * Event Type Factory
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin_Logger\Factories;


use Underpin_Logger\Abstracts\Event_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Event_Type_Instance
 * Handles creating custom event types
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
class Event_Type_Instance extends Event_Type {

	public function __construct( $args = [] ) {
		// Override default params.
		foreach ( $args as $arg => $value ) {
			if ( isset( $this->$arg ) ) {
				$this->$arg = $value;
				unset( $args[ $arg ] );
			}
		}

		parent::__construct();
	}

}