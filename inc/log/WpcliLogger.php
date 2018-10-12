<?php
/**
 * Created by PhpStorm.
 * User: zaantar
 * Date: 11.10.18
 * Time: 17:12
 */

namespace PHPCSDiff\Log;


class WpcliLogger implements LoggerInterface {

	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * @param $severity
	 * @param $message
	 *
	 * @return void
	 */
	public function log( $severity, $message ) {
		switch( $severity ) {
			case LoggerInterface::ERROR:
				/** @noinspection PhpUnhandledExceptionInspection */
				\WP_CLI::error( $message );
				break;
			case LoggerInterface::WARNING:
				\WP_CLI::warning( $message );
				break;
			case LoggerInterface::INFO:
			default:
				\WP_CLI::log( $message );
				break;
		}
	}

}