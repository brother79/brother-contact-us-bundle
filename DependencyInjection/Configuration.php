<?php

namespace Brother\ContactUsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brother_contact_us');

        $treeBuilder->root('brother_contact_us')
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()

                ->arrayNode('class')->addDefaultsIfNotSet()
					->children()
						->scalarNode('model')->cannotBeEmpty()->end()
						->scalarNode('manager')->cannotBeEmpty()->end()
                    ->end()
                ->end()	

            ->end()
        ->end();
        return $treeBuilder;
    }
}
