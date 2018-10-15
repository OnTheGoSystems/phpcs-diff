<?php

namespace PHPCSDiff\Diff;


/**
 * Represents a line that has been removed.
 *
 * @package PHPCSDiff\Diff
 */
class RemovedLine implements LineInterface {

	private $old_line_number;


	/**
	 * RemovedLine constructor.
	 *
	 * @param int $old_line_number Number of this line in the old revision.
	 */
	public function __construct( $old_line_number ) {
		$this->old_line_number = (int) $old_line_number;
	}


	/**
	 * Produce an array that is suitable for further processing.
	 *
	 * @return array
	 */
	public function to_array() {
		return [
			'is_removed' => true,
			'old_line_number' => $this->old_line_number,
			'is_added' => false,
			'is_context' => false,
		];
	}


	/**
	 * @return bool
	 */
	public function is_line_added() {
		return false;
	}


	/**
	 * @return bool
	 */
	public function is_line_removed() {
		return false;
	}
}