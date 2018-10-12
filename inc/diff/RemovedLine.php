<?php

namespace PHPCSDiff\Diff;


class RemovedLine implements LineInterface {

	private $old_line_number;


	public function __construct( $old_line_number ) {
		$this->old_line_number = (int) $old_line_number;
	}


	/**
	 * @return array
	 */
	public function to_array() {
		return [
			'is_removed' => true,
			'old_line_number' => $this->old_line_number,
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