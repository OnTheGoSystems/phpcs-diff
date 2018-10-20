<?php

namespace PHPCSDiff\ResultParser;


/**
 * Parse phpcs results for further processing.
 */
class ResultParser {


	/**
	 * @param string $raw_phpcs_output A JSON string from phpcs
	 *
	 * @return array
	 */
	public function parse( $raw_phpcs_output ) {
		$phpcs_json = json_decode( $raw_phpcs_output, true );

		if( null === $phpcs_json
			|| ! array_key_exists( 'files', $phpcs_json )
			|| ! is_array( $phpcs_json['files'] )
		) {
			throw new \RuntimeException( 'Unable to parse the phpcs output: ' . $raw_phpcs_output );
		}

		$file = array_pop( $phpcs_json['files'] );

		if( ! is_array( $file ) || ! array_key_exists( 'messages', $file ) ) {
			// Happens for perfect code :)
			return [];
		}

		if( ! is_array( $file['messages'] ) ) {
			throw new \RuntimeException( 'Unable to parse the phpcs output: ' . $raw_phpcs_output );
		}

		$output = [];

		foreach( $file['messages'] as $message ) {
			$line_number = $message['line'];
			if( ! array_key_exists( $line_number, $output ) ) {
				$output[ $line_number ] = [];
			}

			$output[ $line_number ][] = [
				'level' => $message['type'],
				'message' => $message['message'],
				'source' => $message['source'],
				'column' => $message['column'],
				'severity' => $message['severity'],
			];
		}

		return $output;
	}

}