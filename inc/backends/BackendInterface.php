<?php

namespace PHPCSDiff\Backends;


interface BackendInterface {

	public function get_diff( $folder, $end_revision, $start_revision = null, $options = array() );


	/**
	 * Collect information about the diff
	 *
	 * @param string $diff_file full svn .diff file to be parsed for information
	 *
	 * @return array information about the diff
	 */
	public function parse_diff_for_info( $diff_file );


	public function run_phpcs_for_file_at_revision( $filename, $revision, $phpcs_command, $standards_location, $phpcs_standard );
}