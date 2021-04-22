<?php

namespace WP_Cypress\Boot;

abstract class Booter {
	/**
	 * Run an array of booters, in the order that they are called.
	 *
	 * @param array $booters
	 * @return void
	 */
	public function call( array $booters ): void {
		$command = new BootCommand();

		foreach ( $booters as $booter ) {
			$command( [ $booter ] );
		}
	}

}
