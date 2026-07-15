<?php
/**
 * Compute EDD's behavioral (column-flag) readiness and write its badge JSON.
 *
 * Run inside a WordPress environment that has EDD (core) active:
 *   wp eval-file bin/readiness-report.php
 *
 * EDD vendors its own first-generation BerlinDB fork, so its behavioral surface - the
 * per-column flags (sortable / searchable / in / compare / ...) - lives in EDD's fork
 * Schema classes, NOT in this repo's generated (structural-only) schemas. This scores
 * those fork classes against shared berlindb/core and writes a shields endpoint badge
 * to .readiness/edd.json. See https://github.com/berlindb/readiness.
 *
 * @package EDDCoreTables
 */

// No declare(strict_types) here: wp eval-file evals this file, where a strict_types
// declaration is a fatal ("must be the very first statement in the script").

use BerlinDB\Readiness\Badge;
use BerlinDB\Readiness\CapabilityReadiness;
use BerlinDB\Readiness\CoreCapabilities;
use BerlinDB\Readiness\CoreFeatures;
use BerlinDB\Readiness\FlagReadiness;
use BerlinDB\Readiness\Report;
use BerlinDB\Readiness\SchemaSurface;

if ( ! defined( 'ABSPATH' ) ) {
	fwrite( STDERR, "Must run inside WordPress (wp eval-file).\n" );
	exit( 1 );
}

require_once __DIR__ . '/../vendor/autoload.php';

// EDD's fork Schema classes (its declared behavioral surface).
$fork_dir = defined( 'EDD_PLUGIN_DIR' )
	? EDD_PLUGIN_DIR . 'src/Database/Schemas'
	: WP_PLUGIN_DIR . '/easy-digital-downloads/src/Database/Schemas';

$classes = array();
foreach ( glob( $fork_dir . '/*.php' ) ?: array() as $file ) {
	$class = 'EDD\\Database\\Schemas\\' . basename( $file, '.php' );

	// Only concrete Schema classes that actually load contribute a surface.
	if ( class_exists( $class ) ) {
		$classes[] = $class;
	}
}

if ( empty( $classes ) ) {
	fwrite( STDERR, "No EDD fork Schema classes found under {$fork_dir}\n" );
	exit( 1 );
}

$supported = CoreCapabilities::fromCore();
$declared  = SchemaSurface::fromClasses( $classes );
$flags     = FlagReadiness::score( 'EDD', $supported, $declared );

// Relationships / meta: score the curated matrix (imperative in the fork) against core,
// split into two dimensions - can core RUN EDD's queries (behavioral) vs can core MODEL
// EDD's schema with faithful relationships (modeling). Flags count toward both.
$matrix_file = __DIR__ . '/../.readiness/capabilities.php';
$matrix      = is_readable( $matrix_file ) ? (array) require $matrix_file : array();
$features    = CoreFeatures::fromCore();

$behavioral = Report::combine( 'EDD', $flags, CapabilityReadiness::score( 'EDD', $features, $matrix, 'behavioral' ) );
$modeling   = Report::combine( 'EDD', $flags, CapabilityReadiness::score( 'EDD', $features, $matrix, 'modeling' ) );

// Human-readable summary (modeling view is the superset, so it shows every row).
printf( "\n== EDD reunification readiness ==\n" );
printf( "  fork schemas: %d   flags: %d   relationship/meta entries: %d\n\n", count( $classes ), $flags->total(), count( $matrix ) );
foreach ( $modeling->rows() as $name => $row ) {
	$status = ( Report::GAP === $row['status'] ) ? 'GAP' : $row['status'];
	printf( "  %-46s %-11s %s\n", $name, $status, $row['via'] );
}
printf( "\n  BEHAVIORAL: %s%%  (%d/%d)   MODELING: %s%%  (%d/%d)\n",
	$behavioral->percent(), $behavioral->covered(), $behavioral->total(),
	$modeling->percent(), $modeling->covered(), $modeling->total()
);
printf( "  MODELING GAPS: %s\n\n", $modeling->is_ready() ? '(none)' : implode( ', ', $modeling->gaps() ) );

// Two shields endpoint badges (dashboard mode: always write, never fail on a gap - the
// colour communicates a regression, CI's PR surfaces the change). Rendered as adjacent
// chips with tooltips in the README.
$out = __DIR__ . '/../.readiness';
if ( ! is_dir( $out ) ) {
	mkdir( $out, 0777, true );
}
file_put_contents( $out . '/edd.json', Badge::toJson( $behavioral, 'behavioral' ) );
file_put_contents( $out . '/edd-modeling.json', Badge::toJson( $modeling, 'modeling' ) );
printf( "  wrote %s/edd.json (behavioral) + edd-modeling.json\n\n", $out );
