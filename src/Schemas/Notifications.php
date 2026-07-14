<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_notifications` (structure only).
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
class Notifications extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'remote_id', 'type' => 'varchar', 'length' => '20', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'source', 'type' => 'varchar', 'length' => '20', 'default' => 'api' ),
			array( 'name' => 'title', 'type' => 'text' ),
			array( 'name' => 'content', 'type' => 'longtext' ),
			array( 'name' => 'buttons', 'type' => 'longtext', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '64', 'default' => 'success' ),
			array( 'name' => 'conditions', 'type' => 'longtext', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'start', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'end', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'dismissed', 'type' => 'tinyint', 'length' => '1', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_updated', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'dismissed_start_end', 'columns' => array( 'dismissed', 'start', 'end' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'remote_id', 'columns' => array( 'remote_id' ) ),
	);
}
