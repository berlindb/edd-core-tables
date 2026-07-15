<?php
/**
 * Behavioral equivalence: does shared berlindb/core reproduce EDD's meta_query results?
 *
 * EDD's meta engine IS WordPress core's WP_Meta_Query - `EDD\Database\Queries\Meta` is an
 * empty subclass of WP_Meta_Query. So "can core reproduce EDD's meta behavior?" reduces to
 * "does core's store-backed meta_query return the same rows as WP_Meta_Query for the same
 * data and query?"
 *
 * This test proves it directly. It stands up ONE custom `{object}_id`/`meta_key`/`meta_value`
 * meta table (the EDD `edd_ordermeta` shape) via core's meta preset, seeds it once, then for
 * each meta_query shape runs BOTH engines against the SAME table and asserts identical result
 * IDs (and the expected set, so we know both are actually correct, not merely equal):
 *
 *   - core store path  : ( new GadgetQuery )->query([ 'meta_query' => Q, 'fields' => 'ids' ])
 *   - WP_Meta_Query    : get_sql() joined to the same primary table, run raw
 *
 * Registering `$wpdb->gadgetmeta` lets WP_Meta_Query's `_get_meta_table('gadget')` resolve to
 * the very table core's relationship EXISTS path filters, so both engines see identical data.
 *
 * @package EDDCoreTables\Tests
 */

declare( strict_types = 1 );

namespace EDDCoreTables\Tests;

use BerlinDB\Database\Kern\Query;
use BerlinDB\Database\Kern\Schema;
use BerlinDB\Database\Kern\Table;
use BerlinDB\Database\Presets\Meta\Query as MetaQuery;
use BerlinDB\Database\Presets\Meta\Table as MetaTable;
use WP_Meta_Query;
use Yoast\WPTestUtils\WPIntegration\TestCase;

// ---------------------------------------------------------------------------
// Fixtures: a store-backed primary ('gadget') + its custom meta sibling.
// ---------------------------------------------------------------------------

/** Primary schema declaring the has_many named 'meta' (makes it the meta relationship). */
class GadgetSchema extends Schema {
	public $columns = array(
		array(
			'name'          => 'id',
			'type'          => 'bigint',
			'length'        => '20',
			'unsigned'      => true,
			'primary'       => true,
			'extra'         => 'auto_increment',
			'relationships' => array(
				array(
					'query'  => GadgetMetaQuery::class,
					'column' => 'gadget_id',
					'type'   => 'has_many',
					'name'   => 'meta',
				),
			),
		),
		array(
			'name'   => 'label',
			'type'   => 'varchar',
			'length' => '20',
		),
	);

	public $indexes = array(
		array(
			'type'    => 'primary',
			'columns' => array( 'id' ),
		),
	);
}

class GadgetQuery extends Query {
	protected $prefix       = 'eqt';
	protected $table_name   = 'gadgets';
	protected $table_schema = GadgetSchema::class;
	protected $item_name    = 'gadget';
	protected $cache_group  = 'eqt_gadgets';
}

class GadgetTable extends Table {
	protected $prefix  = 'eqt';
	protected $name    = 'gadgets';
	protected $version = '1.0.0';
	protected $schema  = GadgetSchema::class;
}

class GadgetMetaQuery extends MetaQuery {
	protected $primary_query_class = GadgetQuery::class;
}

class GadgetMetaTable extends MetaTable {
	protected $meta_query_class = GadgetMetaQuery::class;
}

/**
 * @since 0.1.0
 */
class MetaEquivalenceTest extends TestCase {

	/** @var GadgetTable */
	private static $table;

	/** @var GadgetMetaTable */
	private static $meta_table;

	/** @var array<string,int> label => gadget ID for the current test. */
	private $ids = array();

	/**
	 * Install the primary + meta tables once, and register the meta table with WP so
	 * WP_Meta_Query can resolve it via _get_meta_table('gadget').
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		self::$table      = new GadgetTable();
		self::$meta_table = new GadgetMetaTable();

		if ( ! self::$table->exists() ) {
			self::$table->install();
		}
		if ( ! self::$meta_table->exists() ) {
			self::$meta_table->install();
		}

		// WP_Meta_Query: _get_meta_table('gadget') => $wpdb->gadgetmeta.
		$GLOBALS['wpdb']->gadgetmeta = self::$meta_table->get_table_name();
	}

	public static function tearDownAfterClass(): void {
		self::$meta_table->uninstall();
		self::$table->uninstall();
		unset( $GLOBALS['wpdb']->gadgetmeta );
		parent::tearDownAfterClass();
	}

	/**
	 * Seed three gadgets with meta before each test (mirrors core's own meta fixtures):
	 *   A: color=blue, size=large, score=10
	 *   B: color=red,  size=large, score=100
	 *   C: (no color), size=small, score=9
	 */
	public function setUp(): void {
		parent::setUp();

		wp_set_current_user( 1 );
		self::$table->delete_all();
		self::$meta_table->delete_all();
		wp_cache_flush();

		$gadgets = new GadgetQuery();
		$store   = new GadgetMetaQuery();

		foreach ( array( 'A', 'B', 'C' ) as $label ) {
			$this->ids[ $label ] = (int) $gadgets->add_item( array( 'label' => $label ) );
		}

		$store->add_meta( $this->ids['A'], 'color', 'blue' );
		$store->add_meta( $this->ids['A'], 'size', 'large' );
		$store->add_meta( $this->ids['A'], 'score', '10' );

		$store->add_meta( $this->ids['B'], 'color', 'red' );
		$store->add_meta( $this->ids['B'], 'size', 'large' );
		$store->add_meta( $this->ids['B'], 'score', '100' );

		$store->add_meta( $this->ids['C'], 'size', 'small' );
		$store->add_meta( $this->ids['C'], 'score', '9' );

		wp_cache_flush();
	}

	/** Result IDs from core's store-backed meta_query, sorted. */
	private function core_ids( array $meta_query ): array {
		$ids = ( new GadgetQuery() )->query(
			array(
				'meta_query' => $meta_query,
				'fields'     => 'ids',
				'number'     => 100,
				'orderby'    => 'id',
				'order'      => 'ASC',
			)
		);

		$ids = array_map( 'intval', (array) $ids );
		sort( $ids );

		return $ids;
	}

	/** Result IDs from WP_Meta_Query (EDD's engine) over the same table, sorted. */
	private function wp_ids( array $meta_query ): array {
		global $wpdb;

		$primary = self::$table->get_table_name();
		$mq      = new WP_Meta_Query( $meta_query );
		$sql     = $mq->get_sql( 'gadget', 'g', 'id', null );

		$rows = $wpdb->get_col(
			"SELECT DISTINCT g.id FROM {$primary} g {$sql['join']} WHERE 1=1 {$sql['where']}"
		);

		$ids = array_map( 'intval', (array) $rows );
		sort( $ids );

		return $ids;
	}

	/** Map a set of labels to their sorted IDs for expected-set assertions. */
	private function ids_for( string ...$labels ): array {
		$ids = array();
		foreach ( $labels as $label ) {
			$ids[] = $this->ids[ $label ];
		}
		sort( $ids );

		return $ids;
	}

	/**
	 * Assert core and WP_Meta_Query return the same IDs, and that they match the expected set.
	 *
	 * @param array<int|string,mixed> $meta_query The meta_query.
	 * @param list<string>            $expected   Labels the query should match.
	 */
	private function assertEquivalent( array $meta_query, array $expected ): void {
		$core = $this->core_ids( $meta_query );
		$wp   = $this->wp_ids( $meta_query );
		$want = $this->ids_for( ...$expected );

		$this->assertSame( $want, $core, 'core store path returned an unexpected set' );
		$this->assertSame( $want, $wp, 'WP_Meta_Query returned an unexpected set' );
		$this->assertSame( $wp, $core, 'core store path and WP_Meta_Query disagree' );
	}

	public function test_equals(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'color', 'value' => 'blue', 'compare' => '=' ) ),
			array( 'A' )
		);
	}

	public function test_in(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'size', 'value' => array( 'large' ), 'compare' => 'IN' ) ),
			array( 'A', 'B' )
		);
	}

	public function test_exists(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'color', 'compare' => 'EXISTS' ) ),
			array( 'A', 'B' )
		);
	}

	public function test_not_exists(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'color', 'compare' => 'NOT EXISTS' ) ),
			array( 'C' )
		);
	}

	public function test_and_group(): void {
		$this->assertEquivalent(
			array(
				'relation' => 'AND',
				array( 'key' => 'size', 'value' => 'large' ),
				array( 'key' => 'score', 'value' => '100', 'compare' => '>=', 'type' => 'NUMERIC' ),
			),
			array( 'B' )
		);
	}

	public function test_or_group(): void {
		$this->assertEquivalent(
			array(
				'relation' => 'OR',
				array( 'key' => 'color', 'value' => 'blue' ),
				array( 'key' => 'color', 'value' => 'red' ),
			),
			array( 'A', 'B' )
		);
	}

	public function test_between_numeric(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'score', 'value' => array( 9, 10 ), 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ) ),
			array( 'A', 'C' )
		);
	}

	public function test_greater_than_numeric(): void {
		$this->assertEquivalent(
			array( array( 'key' => 'score', 'value' => 9, 'compare' => '>', 'type' => 'NUMERIC' ) ),
			array( 'A', 'B' )
		);
	}
}
