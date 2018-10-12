<?php

namespace PHPCSDiff\Backends;


use PHPCSDiff\Diff\ChunkBuilder;
use PHPCSDiff\Diff\DiffInfo;
use PHPCSDiff\Diff\Factory;
use PHPCSDiff\Log\LoggerInterface;

use SebastianBergmann\Diff as diff;

class Git implements BackendInterface {


	const DEV_NULL = '/dev/null';

	private $log;

	private $repo;

	private $options;

	private $diff_factory;



	public function __construct( $repo, LoggerInterface $log, $options = array() ) {
		$this->repo = $repo;
		$this->log = $log;
		$this->options = $options;
		$this->diff_factory = new Factory();
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
	 * @param string $diff_string full contents of the diff file to be parsed for information
	 *
	 * @return array information about the diff
	 */
	public function parse_diff_for_info( $diff_string ) {
		$parser = new diff\Parser();

/*		$diff_string = <<<DIFF
diff --git a/docs/changelog.txt b/docs/changelog.txt
index d7307d9..3e97ad6 100644
--- a/docs/changelog.txt
+++ b/docs/changelog.txt
@@ -3,2 +11,8 @@
-= develop =
-* [types-1760] Make sure that when a particular association was created, the client will get a proper reply.
+adding some lines on top
+aaa
+bbb
+ccc
+
+* replace
+* some
+* lines
DIFF;
*/
		/** @var diff\Diff[] $diffs */
		$diffs = $parser->parse( $diff_string );

		$diff_info = new DiffInfo();

		foreach( $diffs as $file_diff ) {

			if( self::DEV_NULL === $file_diff->getTo() ) {
				// The file has been deleted, we don't care about it anymore.
				continue;
			}

			$matches = array();
			preg_match( '/^b\/(.*)$/m', $file_diff->getTo(), $matches );
			if( count( $matches ) !== 2 ) {
				continue;
			}
			$file_name = $matches[1];

			$file = $this->diff_factory->file( $file_name );
			$file->is_new( self::DEV_NULL === $file_diff->getFrom() );

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