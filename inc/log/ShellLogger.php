<?php

namespace PHPCSDiff\Log;


class ShellLogger implements LoggerInterface {

	private $log_level;
	private $enable = true;

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

		if( $this->log_level > $severity || ! $this->enable ) {
			return;
		}

		switch( $severity ) {
			case LoggerInterface::ERROR:
				file_put_contents('php://stderr', 'ERROR: ' .$message . PHP_EOL );
				break;
			case LoggerInterface::WARNING:
				file_put_contents('php://stderr', 'WARNING: ' .$message . PHP_EOL );
				break;
			default:
				file_put_contents('php://stdout', $message . PHP_EOL );
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