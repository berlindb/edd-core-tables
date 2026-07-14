<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_sessions` (structure only).
 * Regenerate with `bin/generate-schemas.php`.
 *
 * @package EDDCoreTables\Schemas
 */

declare( strict_types = 1 );

namespace EDDCoreTables\Schemas;

use BerlinDB\Database\Kern\Schema;

defined( 'ABSPATH' ) || exit;

/**
 * @since 0.1.0
 */
class Sessions extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'session_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'session_key', 'type' => 'varchar', 'length' => '64', 'default' => false ),
			array( 'name' => 'session_value', 'type' => 'longtext' ),
			array( 'name' => 'session_expiry', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => false ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'primary', 'columns' => array( 'session_id' ) ),
			array( 'type' => 'key', 'name' => 'session_expiry', 'columns' => array( 'session_expiry' ) ),
			array( 'type' => 'key', 'name' => 'session_key', 'columns' => array( 'session_key' ) ),
	);
}
