<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_order_adjustments` (structure only).
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
class OrderAdjustments extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'parent', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'object_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'object_type', 'type' => 'varchar', 'length' => '20', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'type_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'allow_null' => true, 'default' => null ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'type_key', 'type' => 'varchar', 'length' => '255', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'description', 'type' => 'varchar', 'length' => '100', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'subtotal', 'type' => 'decimal', 'length' => '18,9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'tax', 'type' => 'decimal', 'length' => '18,9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'total', 'type' => 'decimal', 'length' => '18,9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'rate', 'type' => 'decimal', 'length' => '10,5', 'unsigned' => false, 'default' => '1.00000' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'object_id_type', 'columns' => array( 'object_id', 'object_type' ) ),
			array( 'type' => 'key', 'name' => 'parent', 'columns' => array( 'parent' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
	);
}
