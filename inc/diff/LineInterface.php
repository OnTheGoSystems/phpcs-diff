<?php

namespace PHPCSDiff\Diff;


interface LineInterface {


	/**
	 * @return array
	 */
	public function to_array();


	/**
	 * @return bool
	 */
	public function is_line_added();


	/**
	 * @return bool
	 */
	public function is_line_removed();

}