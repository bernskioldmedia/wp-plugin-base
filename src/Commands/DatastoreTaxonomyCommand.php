<?php

namespace BernskioldMedia\WP\PluginBase\Commands;

use BernskioldMedia\WP\PluginBase\Inflect;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use function Symfony\Component\String\u;

class DatastoreTaxonomyCommand extends MakeCommand {

	protected static $basePath    = '/src/DataStores/';
	protected static $defaultName = 'make:taxonomy';
	protected static $stubName    = 'datastore.taxonomy';

	protected function configure() {
		parent::configure();
		$this->addOption( 'namespace', 's', InputOption::VALUE_OPTIONAL, 'The root plugin namespace.', 'NAMESPACE' )
		     ->addOption( 'textdomain', 't', InputOption::VALUE_OPTIONAL, 'The plugin textdomain.', 'TEXTDOMAIN' );
	}

	protected function getReplacements( InputInterface $input ): array {
		$name            = $input->getArgument( 'name' );
		$humanName       = u( $name )->snake()->toString();
		$humanName       = str_replace( '_', ' ', $humanName );
		$humanName       = u( $humanName )->title( true )->toString();
		$humanPluralName = u( Inflect::pluralize( $humanName ) )->title()->toString();

		$args = [
			'{{ dataStoreNamespace }}'    => $input->getOption( 'namespace' ) . '\\DataStores',
			'{{ dataNamespace }}'         => $input->getOption( 'namespace' ) . '\\Data',
			'{{ class }}'                 => u( $name )->camel()->title()->toString(),
			'{{ key }}'                   => u( $name )->snake()->toString(),
			'{{ pluralName }}'            => $humanPluralName,
			'{{ singularName }}'          => $humanName,
			'{{ pluralNameLowercase }}'   => u( $humanPluralName )->lower(),
			'{{ singularNameLowercase }}' => u( $humanName )->lower(),
			'{{ textdomain }}'            => $input->getOption( 'textdomain' ),
			'{{ slug }}'                  => str_replace( '_', '-', u( $name )->snake()->toString() ),
		];

		return $args;
	}

}
