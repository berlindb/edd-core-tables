<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_order_transactions` (structure only).
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
class OrderTransactions extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'object_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'object_type', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'transaction_id', 'type' => 'varchar', 'length' => '256', 'default' => '' ),
			array( 'name' => 'gateway', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'total', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'rate', 'type' => 'decimal', 'length' => '10', 'scale' => '5', 'unsigned' => false, 'default' => '1.00000' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'gateway', 'columns' => array( 'gateway' ) ),
			array( 'type' => 'key', 'name' => 'object_type_object_id', 'columns' => array( 'object_type', 'object_id' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'status', 'columns' => array( 'status' ) ),
			array( 'type' => 'key', 'name' => 'transaction_id', 'columns' => array( 'transaction_id(64)' ) ),
	);
}
