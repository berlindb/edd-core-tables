<?php
/**
 * PHPUnit bootstrap: load WP test suite, berlindb/core, EDD (core), and this package,
 * then install EDD's tables so the capability test has live tables to compare against.
 *
 * @package EDDCoreTables\Tests
 */

declare( strict_types = 1 );

use Yoast\WPTestUtils\WPIntegration;

$eddct_root = dirname( __DIR__ );

require_once $eddct_root . '/vendor/autoload.php';
require_once $eddct_root . '/vendor/yoast/wp-test-utils/src/WPIntegration/bootstrap-functions.php';

$_tests_dir = WPIntegration\get_path_to_wp_test_dir();

require_once $_tests_dir . '/includes/functions.php';

// Load EDD (core) and this package as must-use plugins, then install EDD's tables.
tests_add_filter(
	'muplugins_loaded',
	static function () use ( $eddct_root ) {
		// EDD is checked out / installed next to this plugin by CI (EDD_PLUGIN_FILE).
		$edd = getenv( 'EDD_PLUGIN_FILE' );
		if ( ! empty( $edd ) && is_readable( $edd ) ) {
			require_once $edd;
		}

		require_once $eddct_root . '/edd-core-tables.php';
	}
);

// After WP + EDD are loaded, make sure EDD's tables physically exist.
tests_add_filter(
	'wp_loaded',
	static function () {
		if ( function_exists( 'edd_setup_components' ) ) {
			edd_setup_components();
		}
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
);

WPIntegration\bootstrap_it();
