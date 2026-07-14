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

// Relationships / meta: score the curated matrix (imperative in the fork) against core.
$matrix_file = __DIR__ . '/../.readiness/capabilities.php';
$matrix      = is_readable( $matrix_file ) ? (array) require $matrix_file : array();
$features    = CoreFeatures::fromCore();
$caps        = CapabilityReadiness::score( 'EDD', $features, $matrix );

// One combined behavioral score (flags + relationships/meta) -> one badge.
$report = Report::combine( 'EDD', $flags, $caps );

// Human-readable summary.
printf( "\n== EDD behavioral readiness ==\n" );
printf( "  fork schemas: %d   flags: %d   relationship/meta: %d\n\n", count( $classes ), $flags->total(), $caps->total() );
foreach ( $report->rows() as $name => $row ) {
	$status = ( Report::GAP === $row['status'] ) ? 'GAP' : $row['status'];
	printf( "  %-42s %-11s %s\n", $name, $status, $row['via'] );
}
printf( "\n  READINESS: %s%%  (%d/%d)\n", $report->percent(), $report->covered(), $report->total() );
printf( "  GAPS: %s\n\n", $report->is_ready() ? '(none)' : implode( ', ', $report->gaps() ) );

// Write the shields endpoint badge (dashboard mode: always write, never fail on a gap -
// the badge colour communicates a regression; CI's PR surfaces the change).
$out = __DIR__ . '/../.readiness';
if ( ! is_dir( $out ) ) {
	mkdir( $out, 0777, true );
}
file_put_contents( $out . '/edd.json', Badge::toJson( $report ) );
printf( "  wrote %s/edd.json\n\n", $out );
