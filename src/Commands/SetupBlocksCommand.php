<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class SetupBlocksCommand extends Command {

	protected static $defaultName = 'setup:block-build';

	protected function execute( InputInterface $input, OutputInterface $output ) {
		if ( ! file_exists( 'package.json' ) ) {
			$output->writeln( 'No package.json was found. Please run npm init first to set it up, and then run this command again.' );

			return 0;
		}

		if ( file_exists( 'webpack.config.js' ) ) {
			$output->writeln( 'The webpack.config.js file already existed so we did not update it. You should review it manually to add the block building sections to it.' );
		}
		else {
			$stubFile = $this->getStubPath() . '/webpack.config.js.stub';
			file_put_contents( 'webpack.config.js', file_get_contents( $stubFile ) );
		}

		$npmDeps = [
			'@bernskioldmedia/wp-editor-components',
			'@wordpress/block-editor',
			'@wordpress/blocks',
			'@wordpress/components',
			'@wordpress/compose',
			'@wordpress/data',
			'@wordpress/element',
			'@wordpress/i18n',
		];

		$npmDevDeps = [
			'@wordpress/eslint-plugin',
			'@wordpress/scripts',
			'copy-webpack-plugin',
			'fast-glob',
			'path',
			'replace-in-file-webpack-plugin',
			'webpackbar',
		];

		$process = Process::fromShellCommandline( 'npm install ' . implode( ' ', $npmDeps ) );
		$process->run( function( $data ) use ( &$output ) {
			$output->write( $data );
		} );

		$process = Process::fromShellCommandline( 'npm install ' . implode( ' ', $npmDeps ) . ' --D' );
		$process->run( function( $data ) use ( &$output ) {
			$output->write( $data );
		} );

		$output->writeln( 'All dependencies have been required.' );
	}

	protected function getStubPath(): string {
		return __DIR__ . '/../../stubs';
	}

}
