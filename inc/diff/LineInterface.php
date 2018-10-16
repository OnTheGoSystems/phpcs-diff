<?php

namespace PHPCSDiff\Diff;


/**
 * Represents a single line in a changed code.
 *
 * This doesn't correspond with actual lines in the diff file, but with lines in a changed file from the repository
 * that is being processed. The line may have been newly added, removed or modified.
 */
interface LineInterface {


	/**
	 * Produce an array that is suitable for further processing.
	 *
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