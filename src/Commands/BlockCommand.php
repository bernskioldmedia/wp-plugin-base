<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Symfony\Component\String\u;

class BlockCommand extends MakeManyCommand {

	protected static $basePath       = '/blocks/';
	protected static $defaultName    = 'make:block';
	protected static $stubFolderName = 'blocks/static';

	protected function configure() {
		$this->addArgument( 'prefix', InputArgument::REQUIRED, 'The prefix for the block, such as bm/' )
		     ->addOption( 'dynamic', 'd', InputOption::VALUE_OPTIONAL, 'If set, the block will be created as a dynamic block with PHP rendering.', false )
		     ->addOption( 'namespace', 's', InputOption::VALUE_OPTIONAL, 'The root plugin namespace.', 'NAMESPACE' );
		parent::configure();
	}

	protected function getReplacements(): array {
		$name      = $this->input->getArgument( 'name' );
		$prefix    = $this->input->getArgument( 'prefix' );
		$snakeName = u( $name )->snake()->toString();
		$kebabName = str_replace( '_', '-', $snakeName );
		$humanName = str_replace( '_', ' ', $snakeName );
		$humanName = u( $humanName )->title( true )->toString();

		$className = u( $name )->snake()->toString();
		$className = str_replace( '_', ' ', $className );
		$className = u( $className )->title( true )->toString();
		$className = str_replace( ' ', '_', $className );

		$args = [
			'{{ blockName }}'    => $prefix . '/' . $name,
			'{{ blockTitle }}'   => $humanName,
			'{{ class }}'        => u( $name )->camel()->title()->toString(),
			'{{ editorScript }}' => "$prefix-block-$kebabName",
			'{{ editorStyle }}'  => "$prefix-block-$kebabName-editor",
			'{{ style }}'        => "$prefix-block-$kebabName",
			'{{ className }}'    => $className,
			'{{ namespace }}'    => $this->input->getOption( 'namespace' ),
			'{{ blockSlug }}'    => $prefix . '_' . $snakeName,
		];


		return $args;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$this->input  = $input;
		$this->output = $output;

		if ( $this->input->getOption( 'dynamic' ) === null ) {
			self::$stubFolderName = 'blocks/dynamic';
		}

		$this->generateFiles();

		$output->writeln( "Don't forget to update the block.json file with descriptions, category, attributes and supports." );

		return 0;
	}

	protected function getOutputPath( $fileName = '' ): string {
		if ( ! empty( $fileName ) && $this->input->getOption( 'dynamic' ) === null ) {
			if ( str_contains( $fileName, '.php' ) ) {
				$className = $this->input->getArgument( 'name' );
				$className = u( $className )->snake()->toString();
				$className = str_replace( '_', ' ', $className );
				$className = u( $className )->title( true )->toString();
				$className = str_replace( ' ', '_', $className );

				return getcwd() . '/src/Blocks/' . $className . '.php';
			}
		}

		return getcwd() . static::$basePath . $this->input->getArgument( 'name' ) . '/' . $fileName;
	}

}
