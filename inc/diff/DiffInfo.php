<?php

namespace PHPCSDiff\Diff;


class DiffInfo {


	private $files = [];


	public function add_file( File $file ) {
		$this->files[] = $file;
	}


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