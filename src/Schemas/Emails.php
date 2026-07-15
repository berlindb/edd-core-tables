<?php
/**
 * GENERATED FILE - do not edit by hand.
 *
 * Introspected from the live EDD table `wp_edd_emails` (structure only).
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
class Emails extends Schema {

	/** @var array<int, array<string, mixed>> */
	public $columns = array(
			array( 'name' => 'id', 'type' => 'bigint', 'unsigned' => true, 'extra' => 'auto_increment', 'primary' => true ),
			array( 'name' => 'email_id', 'type' => 'varchar', 'length' => '32', 'default' => false ),
			array( 'name' => 'context', 'type' => 'varchar', 'length' => '32', 'default' => 'order' ),
			array( 'name' => 'sender', 'type' => 'varchar', 'length' => '32', 'default' => 'edd' ),
			array( 'name' => 'recipient', 'type' => 'varchar', 'length' => '32', 'default' => 'customer' ),
			array( 'name' => 'subject', 'type' => 'text' ),
			array( 'name' => 'heading', 'type' => 'text', 'allow_null' => true, 'default' => null ),
			array( 'name' => 'content', 'type' => 'longtext' ),
			array( 'name' => 'status', 'type' => 'tinyint', 'unsigned' => true, 'default' => '0' ),
			array( 'name' => 'date_created', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
			array( 'name' => 'date_modified', 'type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP' ),
	);

	/** @var array<int, array<string, mixed>> */
	public $indexes = array(
			array( 'type' => 'unique', 'name' => 'email_id', 'columns' => array( 'email_id' ) ),
			array( 'type' => 'primary', 'columns' => array( 'id' ) ),
	);
}
