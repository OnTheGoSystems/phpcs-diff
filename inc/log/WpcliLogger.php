<?php


namespace PHPCSDiff\Log;


/**
 * Logger that writes to the WP_CLI output.
 *
 * @package PHPCSDiff\Log
 */
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