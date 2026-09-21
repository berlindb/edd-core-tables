<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_logs_emails` (structure only).
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
class LogsEmails extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'object_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'object_type', 'type' => 'varchar', 'length' => '20', 'default' => 'customer' ),
			array( 'name' => 'email', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
			array( 'name' => 'email_id', 'type' => 'varchar', 'length' => '32', 'default' => false ),
			array( 'name' => 'subject', 'type' => 'varchar', 'length' => '200', 'default' => false ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'email_id', 'columns' => array( 'email_id' ) ),
			array( 'type' => 'key', 'name' => 'object_id_type', 'columns' => array( 'object_id', 'object_type' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
	);
}
