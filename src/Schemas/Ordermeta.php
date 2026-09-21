<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_ordermeta` (structure only).
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
class Ordermeta extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'meta_id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'edd_order_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'meta_key', 'type' => 'varchar', 'length' => '255', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'meta_value', 'type' => 'longtext', 'allow_null' => true, 'default' => null ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'edd_order_id', 'columns' => array( 'edd_order_id' ) ),
			array( 'type' => 'key', 'name' => 'meta_key', 'columns' => array( 'meta_key(191)' ) ),
			array( 'type' => 'primary', 'columns' => array( 'meta_id' ) ),
	);
}
