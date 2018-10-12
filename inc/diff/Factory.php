<?php

namespace PHPCSDiff\Diff;


class Factory {


	public function file( $file_name ) {
		return new File( $file_name );
	}


	public function modified_line( $old_line_number, $new_line_number ) {
		return new ModifiedLine( $old_line_number, $new_line_number );
	}


	public function removed_line( $old_line_number ) {
		return new RemovedLine( $old_line_number );
	}


	public function new_line( $new_line_number ) {
		return new NewLine( $new_line_number );
	}

}