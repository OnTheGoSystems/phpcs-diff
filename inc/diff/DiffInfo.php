<?php

namespace PHPCSDiff\Diff;


/**
 * Holds information about a single diff.
 *
 * @package PHPCSDiff\Diff
 */
class DiffInfo {


	/** @var File[] */
	private $files = [];


	/**
	 * Add a new file to the diff.
	 *
	 * @param File $file
	 */
	public function add_file( File $file ) {
		$this->files[] = $file;
	}


	/**
	 * Produce an array that is suitable for further processing.
	 *
	 * @return array
	 */
	public function to_array() {
		$file_diffs = [];

		foreach( $this->files as $file ) {
			$file_diffs[ $file->get_file_name() ] = $file->to_array();
		}

		return [
			'file_diffs' => $file_diffs
		];
	}

}