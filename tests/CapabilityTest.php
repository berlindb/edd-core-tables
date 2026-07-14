<?php
/**
 * Capability / parity test: can berlindb/core faithfully reproduce EDD's tables?
 *
 * For each committed Schema, core creates a scratch table from it; that table is then
 * re-introspected and compared, column-for-column and index-for-index, against EDD's
 * live table. A match means today's core can express that EDD table exactly.
 *
 * STRICT: there is no known-gaps allowlist. Any column or index core cannot reproduce
 * turns the suite red. (Presently red on the money decimals - core cannot express
 * decimal(P,S) scale; berlindb/core#244.)
 *
 * @package EDDCoreTables\Tests
 */

declare( strict_types = 1 );

namespace EDDCoreTables\Tests;

use EDDCoreTables\Generator\SchemaGenerator;
use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * @since 0.1.0
 */
class CapabilityTest extends TestCase {

	/**
	 * class name => unprefixed EDD table (e.g. 'Orders' => 'edd_orders').
	 *
	 * @return array<string, string>
	 */
	private static function manifest(): array {
		return require __DIR__ . '/../src/Schemas/manifest.php';
	}

	/**
	 * One case per generated schema.
	 *
	 * @return array<string, array{0: string, 1: string}>
	 */
	public function schema_provider(): array {
		$cases = array();
		foreach ( self::manifest() as $class => $unprefixed ) {
			$cases[ $class ] = array( $class, $unprefixed );
		}
		return $cases;
	}

	/**
	 * Core must recreate each EDD table exactly as EDD declares it.
	 *
	 * @dataProvider schema_provider
	 *
	 * @param string $class      Generated Schema class name (e.g. 'Orders').
	 * @param string $unprefixed Unprefixed EDD table (e.g. 'edd_orders').
	 */
	public function test_core_reproduces_edd_table( string $class, string $unprefixed ): void {
		global $wpdb;

		$live      = $wpdb->prefix . $unprefixed;
		$scratch   = $wpdb->prefix . 'eddct_cap_' . $class;
		$fqcn      = '\\EDDCoreTables\\Schemas\\' . $class;
		$generator = new SchemaGenerator( $wpdb );

		$this->assertNotEmpty(
			$wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $live ) ),
			"EDD live table {$live} is missing - is EDD installed?"
		);

		// Have core render the committed schema's CREATE body and build a scratch table.
		$schema = new $fqcn();
		$body   = $schema->get_create_table_string();
		$this->assertNotEmpty( $body, "{$class} produced an empty CREATE body." );

		$wpdb->query( "DROP TABLE IF EXISTS `{$scratch}`" );
		$wpdb->query( "CREATE TABLE `{$scratch}` ( {$body} )" );

		// Surface the real engine error if core's rendered DDL is rejected, instead of
		// silently producing an empty introspection that reads as a giant diff.
		$this->assertNotEmpty(
			$wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $scratch ) ),
			"core's CREATE for {$class} was rejected: {$wpdb->last_error}\nDDL:\n{$body}"
		);

		// Re-introspect both through the same generator and compare, ignoring only the
		// physical table name embedded in the doc comment.
		$norm = static function ( string $src ) use ( $live, $scratch ): string {
			return str_replace( array( $live, $scratch ), 'TABLE', $src );
		};

		$from_live    = $norm( $generator->generate( $live, $class ) );
		$from_scratch = $norm( $generator->generate( $scratch, $class ) );

		$wpdb->query( "DROP TABLE IF EXISTS `{$scratch}`" );

		$this->assertSame(
			$from_live,
			$from_scratch,
			"berlindb/core cannot reproduce {$live} exactly (see the diff; e.g. decimal scale, core#244)."
		);
	}
}
