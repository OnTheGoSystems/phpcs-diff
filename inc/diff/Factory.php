<?php

namespace PHPCSDiff\Diff;


/**
 * Factory for diff-related classes, in case this project will at any point require dependency injection.
 */
class Factory {


	/**
	 * @param string $file_name
	 *
	 * @return File
	 */
	public function file( $file_name ) {
		return new File( $file_name );
	}


	/**
	 * @param int $old_line_number
	 * @param int $new_line_number
	 *
	 * @return ModifiedLine
	 */
	public function modified_line( $old_line_number, $new_line_number ) {
		return new ModifiedLine( $old_line_number, $new_line_number );
	}


	/**
	 * @param int $old_line_number
	 *
	 * @return RemovedLine
	 */
	public function removed_line( $old_line_number ) {
		return new RemovedLine( $old_line_number );
	}


	/**
	 * @param int $new_line_number
	 *
	 * @return NewLine
	 */
	public function new_line( $new_line_number ) {
		return new NewLine( $new_line_number );
	}

}