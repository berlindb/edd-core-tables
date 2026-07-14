<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_adjustments` (structure only).
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
class Adjustments extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'parent', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'name', 'type' => 'varchar', 'length' => '200', 'default' => '' ),
			array( 'name' => 'code', 'type' => 'varchar', 'length' => '50', 'default' => '' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'scope', 'type' => 'varchar', 'length' => '20', 'default' => 'all' ),
			array( 'name' => 'amount_type', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'amount', 'type' => 'decimal', 'length' => '18,9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'description', 'type' => 'longtext' ),
			array( 'name' => 'max_uses', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'use_count', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'once_per_customer', 'type' => 'int', 'length' => '1', 'unsigned' => false, 'default' => '0' ),
			array( 'name' => 'min_charge_amount', 'type' => 'decimal', 'length' => '18,9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'start_date', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'end_date', 'type' => 'datetime', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'code', 'columns' => array( 'code' ) ),
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'date_start_end', 'columns' => array( 'start_date', 'end_date' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'type_status', 'columns' => array( 'type', 'status' ) ),
			array( 'type' => 'key', 'name' => 'type_status_dates', 'columns' => array( 'type', 'status', 'start_date', 'end_date' ) ),
	);
}
