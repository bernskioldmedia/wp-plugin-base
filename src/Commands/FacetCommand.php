<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use function Symfony\Component\String\u;

class FacetCommand extends MakeCommand {

	protected static $basePath    = '/src/Facets/';
	protected static $defaultName = 'make:facet';
	protected static $stubName    = 'facet';

	protected function configure() {
		parent::configure();
		$this->addOption( 'namespace', 's', InputOption::VALUE_OPTIONAL, 'The root plugin namespace.', 'NAMESPACE' );
	}

	protected function getReplacements( InputInterface $input ): array {
		$name = $input->getArgument( 'name' );

		$args = [
			'{{ namespace }}' => $input->getOption( 'namespace' ) . '\\Customizer',
			'{{ class }}'     => u( $name )->camel()->title()->toString(),
		];

		return $args;
	}

}
