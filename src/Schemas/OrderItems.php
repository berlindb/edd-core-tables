<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_order_items` (structure only).
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
class OrderItems extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'parent', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'order_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'product_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'product_name', 'type' => 'text' ),
			array( 'name' => 'price_id', 'type' => 'bigint', 'unsigned' => true, 'allow_null' => true, 'default' => null ),
			array( 'name' => 'cart_index', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => 'download' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => 'pending' ),
			array( 'name' => 'quantity', 'type' => 'int', 'unsigned' => false, 'default' => '0' ),
			array( 'name' => 'amount', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'subtotal', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'discount', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'tax', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'total', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'rate', 'type' => 'decimal', 'length' => '10', 'scale' => '5', 'unsigned' => false, 'default' => '1.00000' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'order_product_price_id', 'columns' => array( 'order_id', 'product_id', 'price_id' ) ),
			array( 'type' => 'key', 'name' => 'parent', 'columns' => array( 'parent' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'type_status', 'columns' => array( 'type', 'status' ) ),
	);
}
