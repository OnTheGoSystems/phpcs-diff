<?php

namespace PHPCSDiff\Diff;


class NewLine implements LineInterface {


	private $new_line_number;


	public function __construct( $new_line_number ) {
		$this->new_line_number = (int) $new_line_number;
	}

	/**
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