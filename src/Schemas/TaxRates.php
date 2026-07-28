<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_tax_rates` (structure only).
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
class TaxRates extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'country', 'type' => 'varchar', 'length' => '64', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'state', 'type' => 'varchar', 'length' => '64', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'amount', 'type' => 'decimal', 'length' => '18', 'scale' => '9', 'unsigned' => false, 'default' => '0.000000000' ),
			array( 'name' => 'scope', 'type' => 'varchar', 'length' => '20', 'default' => 'country' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => 'active' ),
			array( 'name' => 'source', 'type' => 'varchar', 'length' => '20', 'default' => 'manual' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'country_state', 'columns' => array( 'country', 'state' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
	);
}
