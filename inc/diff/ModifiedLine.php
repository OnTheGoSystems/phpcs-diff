<?php

namespace PHPCSDiff\Diff;


class ModifiedLine implements LineInterface {

	private $new_line_number;

	private $old_line_number;


	public function __construct( $old_line_number, $new_line_number ) {
		$this->old_line_number = (int) $old_line_number;
		$this->new_line_number = (int) $new_line_number;
	}


	/**
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