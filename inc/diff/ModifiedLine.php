<?php

namespace PHPCSDiff\Diff;


/**
 * Represents a line that has been changed between two revisions.
 *
 * @package PHPCSDiff\Diff
 */
class ModifiedLine implements LineInterface {


	private $new_line_number;

	private $old_line_number;


	/**
	 * ModifiedLine constructor.
	 *
	 * @param int $old_line_number Number of this line in the old revision.
	 * @param int $new_line_number Number of this line in the new revision.
	 */
	public function __construct( $old_line_number, $new_line_number ) {
		$this->old_line_number = (int) $old_line_number;
		$this->new_line_number = (int) $new_line_number;
	}


	/**
	 * Produce an array that is suitable for further processing.
	 *
	 * @return array
	 */
	public function to_array() {
		return [
			'is_context' => true,
			'old_line_number' => $this->old_line_number,
			'new_line_number' => $this->new_line_number,
			'is_added' => false,
			'is_removed' => false,
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