<?php
/**
 * Created by PhpStorm.
 * User: zaantar
 * Date: 12.10.18
 * Time: 16:30
 */

namespace PHPCSDiff\Diff;


use SebastianBergmann\Diff\Chunk;
use SebastianBergmann\Diff\Line;

class ChunkBuilder {

	private $lines = [];


	/**
	 * @param Chunk $chunk
	 *
	 * @return LineInterface[]
	 */
	public function process_chunk( Chunk $chunk ) {
		$old_line_offset = $chunk->getStart();
		$new_line_offset = $chunk->getEnd();

		$current_old_line_relative = 0;
		$current_new_line_relative = 0;

		/** @var Line $line */
		foreach( $chunk->getLines() as $line ) {
			switch( $line->getType() ) {
				case Line::REMOVED:
					$this->line( $current_old_line_relative )
						->set_old_line( $old_line_offset + $current_old_line_relative );
					$current_old_line_relative++;
					break;
				case Line::ADDED:
					$this->line( $current_new_line_relative )
						->set_new_line( $new_line_offset + $current_new_line_relative );
					$current_new_line_relative++;
					break;
				case Line::UNCHANGED:
					$current_old_line_relative++;
					$current_new_line_relative++;
					break;
			}
		}

		return array_map( function( LineBuilder $line_builder ) {
			return $line_builder->to_line();
		}, $this->lines );
	}


	/**
	 * @param $relative_line_number
	 *
	 * @return LineBuilder
	 */
	private function line( $relative_line_number ) {
		if( ! array_key_exists( $relative_line_number, $this->lines ) ) {
			$this->lines[ $relative_line_number ] = new LineBuilder();
		}

		return $this->lines[ $relative_line_number ];
	}


}