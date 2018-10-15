<?php

namespace PHPCSDiff\Backends;

use PHPCSDiff\Diff\ChunkBuilder;
use PHPCSDiff\Diff\DiffInfo;
use PHPCSDiff\Diff\Factory;
use PHPCSDiff\Log\LoggerInterface;

use SebastianBergmann\Diff as diff;

/**
 * Support for git repositories.
 *
 * Note that it may support only a subset of the functionality that is offered for SVN. It has been implemented
 * to fulfill a specific task. Feel free to extend this.
 *
 * @package PHPCSDiff\Backends
 */
class Git implements BackendInterface {


	/** @var string This is how the unified diff annotates files that didn't exist at a particular revision. */
	const DEV_NULL = '/dev/null';

	/** @var LoggerInterface */
	private $log;

	/** @var array */
	private $options;

	/** @var Factory */
	private $diff_factory;


	/**
	 * Git constructor.
	 *
	 * @param $repo_ignored
	 * @param LoggerInterface $log
	 * @param array $options
	 */
	public function __construct(
		/** @noinspection PhpUnusedParameterInspection */ $repo_ignored, LoggerInterface $log, $options = array()
	) {
		$this->log = $log;
		$this->options = $options;
		$this->diff_factory = new Factory();
	}


	/**
	 * Retrieve a diff of a given repository between two commits.
	 *
	 * @param string $directory
	 * @param string $end_commit Commit revision number.
	 * @param string $start_commit Commit revision number.
	 * @param array $options
	 *
	 * @return null|string Full diff as produced by git.
	 */
	public function get_diff( $directory, $end_commit, $start_commit = null, $options = array() ) {
		$arg_git_dir = ( empty( $directory ) ? '' : ' --git-dir="' . $directory . '" ' );

		if ( isset( $options['ignore-space-change'] ) && $options['ignore-space-change'] ) {
			$arg_ignore_whitespace = '--ignore-all-space';
		} else {
			$arg_ignore_whitespace = '';
		}

		$command = "git diff $arg_git_dir $arg_ignore_whitespace $start_commit $end_commit";

		$this->log->log( LoggerInterface::INFO, 'Generating a git diff between the selected commits: ' . $command );
		$diff = shell_exec( $command );

		return $diff;
	}

	/**
	 * Collect information about the diff.
	 *
	 * Parse the diff and then transform the obtained information into a format that is required for further processing.
	 * Specifically, we're interested about which lines have been added, removed or modified, and what are their
	 * numbers in both revisions that are being compared.
	 *
	 * @param string $diff_string full contents of the diff file to be parsed for information
	 *
	 * @return array information about the diff
	 */
	public function parse_diff_for_info( $diff_string ) {

		// Thank gods for this library!
		$parser = new diff\Parser();

		/** @var diff\Diff[] $diffs */
		$diffs = $parser->parse( $diff_string );

		$diff_info = new DiffInfo();

		foreach( $diffs as $file_diff ) {

			if( self::DEV_NULL === $file_diff->getTo() ) {
				// The file has been deleted, we don't care about it anymore.
				continue;
			}

			// Retrieve the file name from the diff.
			$matches = array();
			preg_match( '/^b\/(.*)$/m', $file_diff->getTo(), $matches );
			if( count( $matches ) !== 2 ) {
				continue;
			}
			$file_name = $matches[1];

			$file = $this->diff_factory->file( $file_name );
			$file->is_new( self::DEV_NULL === $file_diff->getFrom() );

			// Process individual chunks of the diff for the current file.
			foreach( $file_diff->getChunks() as $chunk ) {
				$chunk_builder = new ChunkBuilder();
				$lines = $chunk_builder->process_chunk( $chunk );
				foreach( $lines as $line ) {
					$file->add_line( $line );
				}
			}

			$diff_info->add_file( $file );
		}

		return $diff_info->to_array();
	}


	public function run_phpcs_for_file_at_revision( $filename, $revision, $phpcs_command, $standards_location, $phpcs_standard ) {
		$this->log->log( LoggerInterface::DEBUG, sprintf(
			'Running phpcs for %s at %s...',
			$filename, $revision
		) );

		$git_command = sprintf(
			'git show --format=raw \'%s:%s\'',
			$revision,
			ltrim( $filename, '/' )
		);

		$phpcs_command = sprintf(
			'%s --runtime-set installed_paths %s --standard=%s --stdin-path=%s -',
			escapeshellcmd( $phpcs_command ),
			escapeshellarg( $standards_location ),
			escapeshellarg( $phpcs_standard ),
			escapeshellarg( $filename )
		);

		$command_string = "$git_command | $phpcs_command";

		return shell_exec( $command_string );
	}
}