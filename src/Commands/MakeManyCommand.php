<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class MakeManyCommand extends Command {

	protected InputInterface  $input;
	protected OutputInterface $output;

	protected static $basePath = '/';
	protected static $stubFolderName;

	public function __construct() {
		parent::__construct();
	}

	protected function configure() {
		$this->addArgument( 'name', InputArgument::REQUIRED );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$this->input  = $input;
		$this->output = $output;

		$this->generateFiles();

		return 0;
	}

	protected function getReplacements(): array {
		return [];
	}

	protected function generateFiles() {
		$files = glob( $this->getStubPath() . '/*' );

		foreach ( $files as $filePath ) {
			$this->generateFile( $this->getFileNameFromPath( $filePath ) );
		}
	}

	protected function getFileNameFromPath( string $filePath ): string {
		return basename( $filePath );
	}

	protected function generateFile( string $fileName ) {
		$name          = $this->input->getArgument( 'name' );
		$stubFile      = $this->getStubFileContents( $fileName );
		$generatedFile = $this->replaceVariablesIn( $stubFile, $this->getReplacements( $this->input ) );
		$outputPath    = $this->getOutputPath();

		if ( ! is_dir( $outputPath ) ) {
			mkdir( $outputPath, 0755, true );
		}

		$this->createFile( $fileName, $generatedFile );
	}

	protected function getStubFileContents( string $fileName ): string {
		$fileName = $this->getStubPath() . '/' . $fileName;

		if ( file_exists( $fileName ) ) {
			return file_get_contents( $fileName );
		}

		return '';
	}

	protected function replaceVariablesIn( string $string, array $variables = [] ): string {
		$output = $string;

		foreach ( $variables as $variable => $value ) {
			$output = str_replace( $variable, $value, $output );
		}

		return $output;
	}

	protected function createFile( string $fileName, string $contents ): void {
		$fileName = str_replace( '.stub', '', $fileName );
		$path     = $this->getOutputPath( $fileName );
		file_put_contents( $path, $contents );
	}

	protected function getOutputPath(): string {
		$path = explode( '/', $this->input->getArgument( 'name' ) );

		// Remove the final name from the path.
		unset( $path[ array_pop( $path ) ] );

		// Put path back together.
		$path = implode( '/', $path );

		return getcwd() . static::$basePath . $path;
	}

	protected function getStubPath(): string {
		return __DIR__ . '/../../stubs/' . static::$stubFolderName;
	}

}
