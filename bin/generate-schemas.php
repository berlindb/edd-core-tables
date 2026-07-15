<?php
/**
 * Regenerate the EDD schema classes from a live EDD install.
 *
 * Run inside a WordPress environment that has EDD (core) active:
 *   wp eval-file bin/generate-schemas.php
 *
 * It installs EDD's tables (idempotent), introspects every `{$prefix}edd_*` table,
 * and writes one berlindb/core Schema class per table into src/Schemas/, plus a
 * manifest mapping class name -> unprefixed table name.
 *
 * Scope: by default every `{$prefix}edd_*` table. In CI only EDD CORE is active, so
 * that is exactly EDD core's set. Locally, set EDDCT_ONLY to a comma-separated list
 * of unprefixed names (e.g. "orders,order_items,customers") to avoid add-on tables.
 *
 * @package EDDCoreTables
 */

// No declare(strict_types) here: wp eval-file evals this file, where a strict_types
// declaration cannot be the first statement of the script.

use EDDCoreTables\Generator\SchemaGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	fwrite( STDERR, "Must run inside WordPress (wp eval-file).\n" );
	exit( 1 );
}

require_once __DIR__ . '/../vendor/autoload.php';

global $wpdb;

$schemas_dir = __DIR__ . '/../src/Schemas';

/*
 * Make sure EDD's tables exist. edd_install_component_database_tables() is EDD's own
 * "used by unit tests and tools" installer; edd_sessions is registered separately by
 * the DB session manager, so install it directly too.
 */
if ( function_exists( 'edd_setup_components' ) ) {
	edd_setup_components();

	if ( function_exists( 'edd_install_component_database_tables' ) ) {
		edd_install_component_database_tables();
	}

	if ( class_exists( '\\EDD\\Database\\Tables\\Sessions' ) ) {
		$sessions = new \EDD\Database\Tables\Sessions();
		if ( ! $sessions->exists() ) {
			$sessions->install();
		}
	}
}

// Discover target tables.
$like   = $wpdb->esc_like( $wpdb->prefix . 'edd_' ) . '%';
$tables = $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $like ) );

// Optional local scoping to EDD core (avoids add-on tables in a dev database).
$only = getenv( 'EDDCT_ONLY' );
if ( ! empty( $only ) ) {
	$allow  = array_map( 'trim', explode( ',', $only ) );
	$prefix = $wpdb->prefix . 'edd_';
	$tables = array_values( array_filter(
		$tables,
		static function ( $t ) use ( $allow, $prefix ) {
			return in_array( substr( $t, strlen( $prefix ) ), $allow, true );
		}
	) );
}

if ( empty( $tables ) ) {
	fwrite( STDERR, "No edd_ tables found. Is EDD active and installed?\n" );
	exit( 1 );
}

$generator = new SchemaGenerator( $wpdb );
$prefix    = $wpdb->prefix . 'edd_';
$manifest  = array();

foreach ( $tables as $physical ) {
	$unprefixed = substr( $physical, strlen( $wpdb->prefix ) ); // e.g. edd_order_items
	$bare       = substr( $physical, strlen( $prefix ) );       // e.g. order_items
	$class      = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $bare ) ) );

	file_put_contents( "{$schemas_dir}/{$class}.php", $generator->generate( $physical, $class ) );
	$manifest[ $class ] = $unprefixed;

	echo "generated {$class} <- {$physical}\n";
}

// Write the manifest (class => unprefixed table name) for the tests + plugin.
ksort( $manifest );
$entries = '';
foreach ( $manifest as $class => $table ) {
	$entries .= "\t'{$class}' => '{$table}',\n";
}

$manifest_src = "<?php\n/**\n * GENERATED - class name => unprefixed EDD table name.\n *\n"
	. " * @package EDDCoreTables\\Schemas\n */\n\ndeclare( strict_types = 1 );\n\nreturn array(\n{$entries});\n";

file_put_contents( "{$schemas_dir}/manifest.php", $manifest_src );

echo 'wrote ' . count( $manifest ) . " schema(s) + manifest.\n";
