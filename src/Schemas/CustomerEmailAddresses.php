<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_customer_email_addresses` (structure only).
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
class CustomerEmailAddresses extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'customer_id', 'type' => 'bigint', 'length' => '20', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => 'secondary' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => 'active' ),
			array( 'name' => 'email', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'uuid', 'type' => 'varchar', 'length' => '100', 'default' => '' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'customer', 'columns' => array( 'customer_id' ) ),
			array( 'type' => 'key', 'name' => 'date_created', 'columns' => array( 'date_created' ) ),
			array( 'type' => 'key', 'name' => 'email', 'columns' => array( 'email' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'status', 'columns' => array( 'status' ) ),
			array( 'type' => 'key', 'name' => 'type', 'columns' => array( 'type' ) ),
	);
}
