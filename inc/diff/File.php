<?php

namespace PHPCSDiff\Diff;


class File {


	private $file_name;

	private $is_new;


	private $lines = [];


	public function __construct( $file_name ) {
		$this->file_name = $file_name;
	}


	public function get_file_name() {
		return $this->file_name;
	}


	public function is_new( $value = null ) {
		if( null !== $value ) {
			$this->is_new = (bool) $value;
		}

		return $this->is_new;
	}


	public function add_line( LineInterface $line ) {
		$this->lines[] = $line;
	}


	public function to_array() {
		return [
			'file_name' => $this->get_file_name(),
			'is_new_file' => $this->is_new(),
			'lines_added' => $this->get_lines_added(),
			'lines_removed' => $this->get_lines_removed(),
			'lines' => array_map( function( LineInterface $line ) {
				return $line->to_array();
			}, $this->lines ),
		];
	}


	public function get_lines_added() {
		return count( array_filter( $this->lines, function( LineInterface $line ) {
			return $line->is_line_added();
		} ) );
	}


	public function get_lines_removed() {
		return count( array_filter( $this->lines, function( LineInterface $line ) {
			return $line->is_line_removed();
		} ) );
	}
}