<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_orders` (structure only).
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
class Orders extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'parent', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'order_number', 'type' => 'varchar', 'length' => '255', 'default' => '' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => 'pending' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => 'sale' ),
			array( 'name' => 'user_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'customer_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'email', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
			array( 'name' => 'ip', 'type' => 'varchar', 'length' => '60', 'default' => '' ),
			array( 'name' => 'gateway', 'type' => 'varchar', 'length' => '100', 'default' => 'manual' ),
			array( 'name' => 'mode', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'currency', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'payment_key', 'type' => 'varchar', 'length' => '64', 'default' => '' ),
			array( 'name' => 'tax_rate_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => false, 'allow_null' => true, 'default' => null ),
			array( 'name' => 'subtotal', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'discount', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'tax', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'total', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'rate', 'type' => 'decimal', 'length' => '10', 'scale' => '5', 'unsigned' => false, 'default' => '1.00000' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_completed', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'date_refundable', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'date_actions_run', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'currency', 'columns' => array( 'currency' ) ),
			array( 'type' => 'key', 'name' => 'customer_id', 'columns' => array( 'customer_id' ) ),
			array( 'type' => 'key', 'name' => 'date_created_completed', 'columns' => array( 'date_created', 'date_completed' ) ),
			array( 'type' => 'key', 'name' => 'email', 'columns' => array( 'email' ) ),
			array( 'type' => 'key', 'name' => 'order_number', 'columns' => array( 'order_number(191)' ) ),
			array( 'type' => 'key', 'name' => 'payment_key', 'columns' => array( 'payment_key' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'status_type', 'columns' => array( 'status', 'type' ) ),
			array( 'type' => 'key', 'name' => 'user_id', 'columns' => array( 'user_id' ) ),
	);
}
