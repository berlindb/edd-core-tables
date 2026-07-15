<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_order_addresses` (structure only).
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
class OrderAddresses extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'order_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => 'billing' ),
			array( 'name' => 'name', 'type' => 'mediumtext' ),
			array( 'name' => 'address', 'type' => 'mediumtext' ),
			array( 'name' => 'address2', 'type' => 'mediumtext' ),
			array( 'name' => 'city', 'type' => 'mediumtext' ),
			array( 'name' => 'region', 'type' => 'mediumtext' ),
			array( 'name' => 'postal_code', 'type' => 'varchar', 'length' => '32', 'default' => '' ),
			array( 'name' => 'country', 'type' => 'mediumtext' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'city', 'columns' => array( 'city(191)' ) ),
			array( 'type' => 'key', 'name' => 'country', 'columns' => array( 'country(191)' ) ),
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'order_id', 'columns' => array( 'order_id' ) ),
			array( 'type' => 'key', 'name' => 'postal_code', 'columns' => array( 'postal_code' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'region', 'columns' => array( 'region(191)' ) ),
	);
}
