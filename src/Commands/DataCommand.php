<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use function Symfony\Component\String\u;

class DataCommand extends MakeCommand {

	protected static $basePath    = '/src/Data/';
	protected static $defaultName = 'make:data';
	protected static $stubName    = 'data';

	protected function configure() {
		parent::configure();
		$this->addOption( 'namespace', 's', InputOption::VALUE_OPTIONAL, 'The root plugin namespace.', 'NAMESPACE' )
		     ->addOption( 'type', 't', InputOption::VALUE_OPTIONAL, 'Should we generate a data class for a taxonomy or cpt. Defaults to cpt.', 'cpt' );
	}

	protected function getReplacements( InputInterface $input ): array {
		$name = $input->getArgument( 'name' );

		$args = [
			'{{ namespace }}' => $input->getOption( 'namespace' ),
			'{{ class }}'     => u( $name )->camel()->title()->toString(),
		];

		if ( 'taxonomy' === $input->getOption( 'type' ) ) {
			$args['{{ dataBaseClass }}'] = 'TaxonomyData';
		} else {
			$args['{{ dataBaseClass }}'] = 'Data';
		}

		return $args;
	}

}
