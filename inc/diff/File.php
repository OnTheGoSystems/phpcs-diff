<?php

namespace PHPCSDiff\Diff;


/**
 * Represents a single file in a diff.
 *
 * @package PHPCSDiff\Diff
 */
class File {


	/** @var string */
	private $file_name;


	/** @var bool */
	private $is_new;


	/** @var LineInterface[] */
	private $lines = [];


	/**
	 * File constructor.
	 *
	 * @param string $file_name File name according to VCS.
	 */
	public function __construct( $file_name ) {
		$this->file_name = $file_name;
	}


	/**
	 * @return string
	 */
	public function get_file_name() {
		return $this->file_name;
	}


	/**
	 * @param null|bool $value If a non-null value is provided, it will be set.
	 *
	 * @return bool
	 */
	public function is_new( $value = null ) {
		if( null !== $value ) {
			$this->is_new = (bool) $value;
		}

		return $this->is_new;
	}


	/**
	 * Add a new line. The order of calling this doesn't matter.
	 *
	 * @param LineInterface $line
	 */
	public function add_line( LineInterface $line ) {
		$this->lines[] = $line;
	}


	/**
	 * Produce an array that is suitable for further processing.
	 *
	 * @return array
	 */
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