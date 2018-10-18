# PHPCS Diff

The purpose of this project is to provide a mean of running [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) (aka PHPCS) checks on top of file(s) stored in a version control system and reporting issues introduced only in specific revision(s).

Reporting only new issues for specific revision migth be important in case the PHPCS is being introduced later in the development cycle and there are no resources for cleaning up all existing issues.

There are several different ways of installing and using the tool.

# Standalone

You can run phpcs-diff from a commandline without further requirements, you just need PHP and Composer.

## Installation

```bash
git clone https://github.com/zaantar/phpcs-diff.git phpcs-diff
cd phpcs-diff
composer install --no-dev
```

## Usage

Run the script from the directory of the git repository you want to inspect.

`../phpcs-diff/bin/phpcs-diff [options]`

Options:

- `--start_revision=<arg>`: First (older) revision number/commit hash    
- `--end_revision=<arg>`: Second (newer) revision number/commit hash    
- `--tolerance[=<arg>]`: Last level of issues that will be tolerated. Accepted values are blockers | warnings | notes | none.     
- `--standard[=<arg>]`: Name of the phpcs standard to use: 'WordPress', 'WordPress-VIP', 'WordPress-Core', 'WordPress-Docs', 'WordPress-Extra', 'Toolset'
- `--log_level[=<arg>]`: Control verbosity by passing a number from 0 (most verbose) to 2 (least verbose, only errors).
- `--ignore_space_changes`: Whitespace changes will be ignored when git is used to produce the diff.

Limitations:

- Subversion is not supported.
- Can be only used on a git repository in the current directory. 

Output:

The script will print found issues and set an exit code if any issues not ignored by the current tolerance level have occurred.

# As a wp-cli command

This repository is also a WordPress plugin that, when activated, provides a WP-CLI command `phpcs-diff`.

## Pre-requisities

Along a working [WordPress](wordpress.org) installation you'll need a [WP CLI](wp-cli.org) installed since you can interact with the plugin via WP CLI only for now.

## Installation

1. Checkout this repository to your plugins directory.
2. Run `composer install --no-dev` in the root of the repository.
3. Activate the plugin via standard WordPress administration.

## Usage

Example command run:

```bash
wp phpcs-diff --vcs="svn" --repo="hello-dolly" --start_revision=99998 --end_revision=100000
```

For more params of the command, please, run `wp help phpcs-diff` or [check the inline documentation directly](https://github.com/zaantar/phpcs-diff/blob/master/wp-cli-command.php#L17).

## Configuration

### PHPCS

If default values for running PHPCS command does not match your environment (see https://github.com/Automattic/phpcs-diff/blob/master/class-phpcs-diff.php#L5 ), you need to override those via constants located in wp-config.php of your WordPress installation:

```php
define( 'PHPCS_DIFF_COMMAND', 'phpcs' );
define( 'PHPCS_DIFF_STANDARDS', 'path/to/phpcs/standards' );
```

### SVN

Originally, this tool has been working with Subversion only, but that functionality had since been somewhat sidelined. 
Please report any features you're missing for SVN or bugs.

Note: Among other things, you will need to provide the plugin SVN credentials. 
This can be done using following constants put into `wp-config.php` file of your WordPress installation:

```php
define( 'PHPCS_DIFF_SVN_USERNAME', 'my_svn_username' );
define( 'PHPCS_DIFF_SVN_PASSWORD', 'my_svn_password' );
```