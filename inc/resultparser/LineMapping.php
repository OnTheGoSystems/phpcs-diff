<?php

namespace PHPCSDiff\ResultParser;


/**
 * Given the line changes information for a particular file, it builds a complete mapping from line numbers in the
 * old revision to the new one.
 *
 * Note that for each file, a new instance must be used.
 *
 * @package PHPCSDiff\ResultParser
 */
class LineMapping {


	/** @var int[] Numbers of lines added in the new revision */
	private $lines_added = [];

	/** @var int[] Numbers of OLD revision lines that have been removed from the new revision */
	private $lines_removed = [];

	/** @var int[] Mapping of changed lines between old and new revisions (old is key, new is value) */
	private $lines_changed_mapping = [];

	/** @var int[] Mapping of all old revision line numbers to the new ones. May contain NO_MAPPING values. */
	private $total_lines_mapping = [];

	/** @var int Indicates that an old revision line doesn't have a counterpart in the mapping. */
	const NO_MAPPING = -1;


	/**
	 * Count lines for a file.
	 *
	 * Needs to be called before any other method in this class.
	 *
	 * @param array $lines Line information produced by BackendInterface::parse_diff_for_info().
	 */
	public function count_lines( $lines ) {
		foreach( $lines as $line ) {
			if ( true === $line['is_added'] ) {
				$this->lines_added[] = intval( $line['new_line_number'] );
			} else if ( true === $line['is_removed'] ) {
				$this->lines_removed[] = intval( $line['old_line_number'] );
			} else if ( true === $line['is_context'] ) {
				$this->lines_changed_mapping[ intval( $line['old_line_number'] ) ] = intval( $line['new_line_number'] );
			}
		}
	}


	/**
	 * Check whether the file might contain anything to report at all.
	 *
	 * @return bool
	 */
	public function has_actionable_changes() {
		return ( ! empty( $this->lines_added ) || ! empty( $this->lines_changed_mapping ) );
	}


	/**
	 * Build the mapping from old to new lines.
	 *
	 * Must be called before old_line_number_to_new().
	 *
	 * @param int $last_old_line Last (highest) line number that we'll be interested about.
	 */
	public function build_mapping( $last_old_line ) {

		// This will hold the difference of new line number against the old one.
		$offset = 0;

		// For every old revision line number, find a line number in the new revision.
		for( $current_old_line = 1; $current_old_line <= $last_old_line; $current_old_line++ ) {

			if( in_array( $current_old_line, $this->lines_removed ) ) {
				// The current line has been removed in the old revision, it has no counterpart.
				$this->total_lines_mapping[ $current_old_line ] = self::NO_MAPPING;
				// All following line numbers in the new revision will be lower by 1 because of this.
				$offset--;
			}

			// If there's a newly added line at the position corresponding to $current_old_line (we need to
			// mind the offset as well!), we'll skip it and keep skipping until we reach the next line that
			// has not been newly added (changed or unchanged, that doesn't matter).
			while( in_array( $current_old_line + $offset, $this->lines_added ) ) {
				$offset++;
			}

			$this->total_lines_mapping[ $current_old_line ] = $current_old_line + $offset;
		}
	}


	/**
	 * Translate an old revision line number into the new revision.
	 *
	 * The method build_mapping() must be called before this one.
	 *
	 * Can return NO_MAPPING in case the old revision line has no counterpart in the new revision.
	 *
	 * @param int $old_line_number
	 *
	 * @return int
	 */
	public function old_line_number_to_new( $old_line_number ) {
		if( ! array_key_exists( $old_line_number, $this->total_lines_mapping ) ) {
			return self::NO_MAPPING;
		}

		return $this->total_lines_mapping[ $old_line_number ];
	}
}