<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_logs_api_requests` (structure only).
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
class LogsApiRequests extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'user_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'api_key', 'type' => 'varchar', 'length' => '32', 'default' => 'public' ),
			array( 'name' => 'token', 'type' => 'varchar', 'length' => '32', 'default' => '' ),
			array( 'name' => 'version', 'type' => 'varchar', 'length' => '32', 'default' => '' ),
			array( 'name' => 'request', 'type' => 'longtext' ),
			array( 'name' => 'error', 'type' => 'longtext' ),
			array( 'name' => 'ip', 'type' => 'varchar', 'length' => '60', 'default' => '' ),
			array( 'name' => 'time', 'type' => 'varchar', 'length' => '60', 'default' => '' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'user_id', 'columns' => array( 'user_id' ) ),
	);
}
