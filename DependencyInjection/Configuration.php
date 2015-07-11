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
                ->booleanNode('notify_admin')->defaultFalse()->end()
                ->scalarNode('date_format')->defaultValue('d/m/Y H:i:s')->end()
                ->scalarNode('user_class')->defaultValue('AppBundle\Entity\User\User')->end()
                ->arrayNode('mailer')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('admin_email')->defaultValue('admin@localhost.com')->end()
                        ->scalarNode('sender_email')->defaultValue('admin@localhost.com')->end()
                        ->scalarNode('email_title')->defaultValue('New contact_us entry from {name}')->end()
                    ->end()
                ->end()
                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('entry')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->cannotBeEmpty()->defaultValue('brother_contact_us_entry')->end()
                                ->scalarNode('type')->cannotBeEmpty()->defaultValue('brother_contact_us_entry')->end()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Brother\ContactUsBundle\Form\Type\EntryType')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('class')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('mailer')->cannotBeEmpty()->defaultValue('Brother\ContactUsBundle\Mailer\Mailer')->end()
                        ->scalarNode('model')->cannotBeEmpty()->end()
                        ->scalarNode('manager')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('service')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('mailer')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        return $treeBuilder;
    }
}
