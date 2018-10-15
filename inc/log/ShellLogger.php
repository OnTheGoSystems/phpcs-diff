<?php

namespace PHPCSDiff\Log;


class ShellLogger implements LoggerInterface {

	private $log_level;


	public function __construct( $log_level = LoggerInterface::WARNING ) {
		$this->log_level = $log_level;
	}

	/**
	 * @param $severity
	 * @param $message
	 *
	 * @return void
	 */
	public function log( $severity, $message ) {

		if( $this->log_level > $severity ) {
			return;
		}

		switch( $severity ) {
			case LoggerInterface::ERROR:
			case LoggerInterface::WARNING:
				file_put_contents('php://stderr', $message . PHP_EOL );
				break;
			default:
				file_put_contents('php://stdout', $message . PHP_EOL );
				break;
		}
	}
}