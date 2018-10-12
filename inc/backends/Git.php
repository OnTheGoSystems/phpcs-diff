<?php

namespace PHPCSDiff\Backends;


use PHPCSDiff\Log\LoggerInterface;

class Git implements BackendInterface {


	private $log;

	private $repo;

	private $options;



	public function __construct( $repo, LoggerInterface $log, $options = array() ) {
		$this->repo = $repo;
		$this->log = $log;
		$this->options = $options;
	}


	public function get_diff( $directory, $end_revision, $start_revision = null, $options = array() ) {
		$arg_git_dir = ( empty( $directory ) ? '' : ' --git-dir="' . $directory . '" ' );

		if ( isset( $options['ignore-space-change'] ) && $options['ignore-space-change'] ) {
			$arg_ignore_whitespace = '--ignore-all-space';
		} else {
			$arg_ignore_whitespace = '';
		}

		$command = "git diff $arg_git_dir $arg_ignore_whitespace $start_revision $end_revision";

		$this->log->log( LoggerInterface::INFO, 'Generating a git diff between the selected commits: ' . $command );
		$diff = shell_exec( $command );

		return $diff;
	}

	/**
	 * Collect information about the diff
	 *
	 * @param string $diff_file full svn .diff file to be parsed for information
	 *
	 * @return array information about the diff
	 */
	public function parse_diff_for_info( $diff_file ) {
		// TODO: Implement parse_diff_for_info() method.
		throw new \RuntimeException( 'Not implemented.' );
	}

	public function run_phpcs_for_file_at_revision( $filename, $revision, $phpcs_command, $standards_location, $phpcs_standard ) {
		// TODO: Implement run_phpcs_for_file_at_revision() method.
		throw new \RuntimeException( 'Not implemented.' );
	}
}