<?php

namespace PHPCSDiff\Diff;


/**
 * Represents a line that has been newly added.
 *
 * @package PHPCSDiff\Diff
 */
class NewLine implements LineInterface {


	private $new_line_number;


	/**
	 * NewLine constructor.
	 *
	 * @param int $new_line_number Number of this line in the new revision.
	 */
	public function __construct( $new_line_number ) {
		$this->new_line_number = (int) $new_line_number;
	}

	/**
	 * Produce an array that is suitable for further processing.
	 *
	 * @return array
	 */
	public function to_array() {
		return [
			'is_added' => true,
			'new_line_number' => $this->new_line_number,
			'is_context' => false,
			'is_removed' => false,
		];
	}


	/**
	 * @return bool
	 */
	public function is_line_added() {
		return true;
	}


	/**
	 * @return bool
	 */
	public function is_line_removed() {
		return false;
	}
}