<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_payment_tokens` (structure only).
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
class PaymentTokens extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'customer_id', 'type' => 'bigint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'gateway', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'token_id', 'type' => 'varchar', 'length' => '255', 'default' => '' ),
			array( 'name' => 'gateway_customer_id', 'type' => 'varchar', 'length' => '255', 'default' => '' ),
			array( 'name' => 'type', 'type' => 'varchar', 'length' => '20', 'default' => '' ),
			array( 'name' => 'label', 'type' => 'varchar', 'length' => '255', 'default' => '' ),
			array( 'name' => 'payment_source', 'type' => 'longtext', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'mode', 'type' => 'varchar', 'length' => '10', 'default' => 'live' ),
			array( 'name' => 'status', 'type' => 'varchar', 'length' => '20', 'default' => 'active' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'key', 'name' => 'customer_id', 'columns' => array( 'customer_id' ) ),
			array( 'type' => 'key', 'name' => 'gateway', 'columns' => array( 'gateway' ) ),
			array( 'type' => 'key', 'name' => 'mode', 'columns' => array( 'mode' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
			array( 'type' => 'key', 'name' => 'status', 'columns' => array( 'status' ) ),
			array( 'type' => 'key', 'name' => 'token_id', 'columns' => array( 'token_id' ) ),
	);
}
