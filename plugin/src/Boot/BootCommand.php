<?php

namespace WP_Cypress\Boot;

use Exception;
use WP_CLI;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BootCommand {
	const DEFAULT_BOOTERS_DIR = 'wp-content/plugins/wp-cypress/src/Booters/*';

	const USER_BOOTERS_DIR = 'booters/*';

	/**
	 * Find and call the relevant booter when invoked.
	 *
	 * @param array $args
	 * @return void
	 */
	public function __invoke( array $args ): void {
		$booter_name = $args[0];

		if ( empty( $booter_name ) ) {
			WP_CLI::error(
				sprintf( 'You need to provide the name of a booter.' )
			);

			return;
		}

		$this->include_dir( self::USER_BOOTERS_DIR );
		$this->include_dir( self::DEFAULT_BOOTERS_DIR );

		$this->boot( $booter_name );
	}

	/**
	 * Recursively include all files in a directory
	 *
	 * @param string $dir
	 * @return void
	 */
	public function include_dir( string $dir ): void {
		$files = glob( $dir );

		foreach ( $files as $filename ) {
			if ( is_dir( $filename ) ) {
				$this->include_dir( $filename . '/*' );
			}

			if ( is_file( $filename ) ) {
				require_once $filename;
			}
		}
	}

	/**
	 * Validate whether the supplied seeder is a sub class of Seeder.
	 *
	 * @param string $booter_name
	 * @return void
	 */
	public function validate_seeder( string $booter_name ): void {
		if ( ! strpos( get_parent_class( $booter_name ), 'Booter' ) ) {
			WP_CLI::error(
				sprintf( '"%s" is not a booter.', $booter_name )
			);
		}
	}

	/**
	 * Run an individual booter.
	 *
	 * @param string $booter_name
	 * @return void
	 */
	public function boot( string $booter_name ): void {
		$this->validate_booter( $booter_name );

		$start_time = microtime( true );

		try {
			$booter = new $booter_name();
			$booter->run();
		} catch ( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		$run_time = round( microtime( true ) - $start_time, 2 );

		WP_CLI::success( 'Booted ' . $booter_name . ' in ' . $run_time . ' seconds' );
	}
}
