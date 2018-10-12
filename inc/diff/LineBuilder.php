<?php
/**
 * Created by PhpStorm.
 * User: zaantar
 * Date: 12.10.18
 * Time: 16:29
 */

namespace PHPCSDiff\Diff;


class LineBuilder {

	private $old_line;

	private $new_line;

	private $diff_factory;


	public function __construct() {
		$this->diff_factory = new Factory();
	}


	public function set_old_line( $line_number ) {
		$this->old_line = $line_number;
	}


	public function set_new_line( $line_number ) {
		$this->new_line = $line_number;
	}


	public function to_line() {
		if( null === $this->old_line ) {
			return $this->diff_factory->new_line( $this->new_line );
		} elseif( null === $this->new_line ) {
			return $this->diff_factory->removed_line( $this->old_line );
		}

		return $this->diff_factory->modified_line( $this->old_line, $this->new_line );
	}

}