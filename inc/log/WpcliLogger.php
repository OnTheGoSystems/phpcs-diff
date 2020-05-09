<?php


namespace PHPCSDiff\Log;


/**
 * Logger that writes to the WP_CLI output.
 *
 * @package PHPCSDiff\Log
 */
class WpcliLogger implements LoggerInterface {
	private $enable = true;

	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * @param $severity
	 * @param $message
	 *
	 * @return void
	 */
	public function log( $severity, $message ) {
		if ( ! $this->enable ) {
			return;
		}

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

	/**
	 * @param $enable boolea
	 *
	 * @return void
	 */
	public function set_enable( $enable ) {
		$this->enable = $enable;
	}

}