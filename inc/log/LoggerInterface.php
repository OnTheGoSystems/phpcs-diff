<?php

namespace PHPCSDiff\Log;

/**
 * Interface of a logger.
 *
 * @package PHPCSDiff\Log
 */
interface LoggerInterface {

	const DEBUG = 0;
	const INFO = 0;
	const WARNING = 1;
	const ERROR = 2;


	/**
	 * @param $severity
	 * @param $message
	 *
	 * @return void
	 */
	public function log( $severity, $message );

	/**
	 * @param $enable boolea
	 *
	 * @return void
	 */
	public function set_enable( $enable );

}