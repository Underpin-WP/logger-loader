<?php
/**
 * Houses table metadata
 *
 * @since   $VERSION
 * @package $PACKAGE
 */

namespace Underpin_Logger\Traits;

use BerlinDB\Database\Table;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait With_Meta {

	/**
	 * Attempt to retrieve the meta table name.
	 *
	 * @since 1.2.0
	 *
	 * @return Table|WP_Error The table model, if found. Otherwise, WP_Error
	 */
	abstract function get_meta_table();

	/**
	 * Strips out the "meta" at the end of the table, so WordPress core can add it without a problem.
	 *
	 * @since 1.2.0
	 *
	 * @return string|WP_Error The meta table name, converted to be used with wordpress x_metadata methods.
	 */
	private function get_meta_friendly_name() {
		$table = $this->get_meta_table();

		if ( is_wp_error( $table ) ) {
			return $table;
		}

		return str_replace( 'meta', '', $table->name );
	}

	/**
	 * Adds metadata to the meta table. See add_metadata
	 *
	 * @param int    $object_id  ID of the object metadata is for.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
	 * @param bool   $unique     Optional. Whether the specified metadata key should be unique for the object.
	 *                           If true, and the object already has a value for the specified metadata key,
	 *                           no change will be made. Default false.
	 * @return int|WP_Error The meta ID on success, false on failure.
	 */
	public function add_meta( $object_id, $key, $value, $unique = false ) {

		$name = $this->get_meta_friendly_name();

		if ( is_wp_error( $name ) ) {
			return $name;
		}

		if ( method_exists( $this, 'sanitize_item' ) ) {
			$value = $this->sanitize_item( $key, $value );
		}

		$added = add_metadata( $name, $object_id, $key, $value, $unique );

		if ( false === $added ) {
			return new WP_Error(
				'add_meta_failed',
				'Add metadata failed to save meta.'
			);
		}

		return $added;
	}

	/**
	 * Updates metadata for the meta table.
	 *
	 * @param int    $object_id  ID of the object metadata is for.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
	 * @param mixed  $prev_value Optional. If specified, only update existing metadata entries
	 *                           with this value. Otherwise, update all entries.
	 * @return int|WP_Error The new meta field ID if a field with the given key didn't exist and was
	 *                           therefore added, true on successful update, false on failure.
	 */
	public function update_meta( $object_id, $meta_key, $meta_value, $prev_value = '' ) {

		$name = $this->get_meta_friendly_name();

		if ( is_wp_error( $name ) ) {
			return $name;
		}

		$updated = update_metadata( $name, $object_id, $meta_key, $meta_value, $prev_value );

		if ( false === $updated ) {
			return new WP_Error(
				'update_meta_failed',
				'Update metadata failed to save meta.'
			);
		}

		return $updated;
	}

	/**
	 * Updates metadata for the meta table.
	 *
	 * @param int    $object_id  ID of the object metadata is for.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar.
	 *                           If specified, only delete metadata entries with this value.
	 *                           Otherwise, delete all entries with the specified meta_key.
	 *                           Pass `null`, `false`, or an empty string to skip this check.
	 *                           (For backward compatibility, it is not possible to pass an empty string
	 *                           to delete those entries with an empty string for a value.)
	 * @param bool   $delete_all Optional. If true, delete matching metadata entries for all objects,
	 *                           ignoring the specified object_id. Otherwise, only delete
	 *                           matching metadata entries for the specified object_id. Default false.
	 * @return true|WP_Error True on successful delete, false on failure.
	 */
	public function delete_meta( $object_id, $meta_key, $meta_value = '', $delete_all = false ) {

		$name = $this->get_meta_friendly_name();

		if ( is_wp_error( $name ) ) {
			return $name;
		}

		$deleted = delete_metadata( $name, $object_id, $meta_key, $meta_value, $delete_all );

		if ( false === $deleted ) {
			return new WP_Error(
				'delete_meta_failed',
				'Delete metadata failed to delete meta.'
			);
		}

		return $deleted;
	}

	/**
	 * Retrieves metadata for the specified object.
	 *
	 * @since 1.1.0
	 *
	 * @param int    $object_id ID of the object metadata is for.
	 * @param string $meta_key  Optional. Metadata key. If not specified, retrieve all metadata for
	 *                          the specified object. Default empty.
	 * @param bool   $single    Optional. If true, return only the first value of the specified meta_key.
	 *                          This parameter has no effect if meta_key is not specified. Default false.
	 * @return mixed|WP_Error Single metadata value, or array of values, or error if something went wrong.
	 */
	public function get_meta( $object_id, $meta_key = '', $single = false ) {

		$name = $this->get_meta_friendly_name();

		if ( is_wp_error( $name ) ) {
			return $name;
		}

		return get_metadata( $name, $object_id, $meta_key, $single );
	}
}