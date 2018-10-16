<?php

namespace PHPCSDiff\Diff;


/**
 * Class that collects data about a certain code line and produces the correct type of a LineInterface object.
 *
 * @package PHPCSDiff\Diff
 */
class LineBuilder {

	private $old_line;

	private $new_line;

	/** @var Factory */
	private $diff_factory;


	public function __construct() {
		$this->diff_factory = new Factory();
	}


	/**
	 * @param int $line_number Number of this line in the old revision.
	 */
	public function set_old_line( $line_number ) {
		$this->old_line = $line_number;
	}


	/**
	 * @param int $line_number Number of this line in the new revision.
	 */
	public function set_new_line( $line_number ) {
		$this->new_line = $line_number;
	}


	/**
	 * @return LineInterface
	 */
	public function to_line() {
		if( null === $this->old_line ) {
			return $this->diff_factory->new_line( $this->new_line );
		} elseif( null === $this->new_line ) {
			return $this->diff_factory->removed_line( $this->old_line );
		}

		return $this->diff_factory->modified_line( $this->old_line, $this->new_line );
	}

}