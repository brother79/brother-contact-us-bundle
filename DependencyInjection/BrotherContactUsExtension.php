<?php

namespace Brother\ContactUsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BrotherContactUsExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');
        $brotherConfig = array();

        // get the BrotherContactUs configuration
        $configs = $container->getExtensionConfig($this->getAlias());
        $contactUsConfig = $this->processConfiguration(new Configuration(), $configs);
        $container->prependExtensionConfig('brother_contact_us', $brotherConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (empty($config['db_driver']) || !in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.yml', $config['db_driver']));

        // set model class
        if (isset($config['class']['model'])) {
            $container->setParameter('brother_contact_us.model.entry.class', $config['class']['model']);
        }

        // set manager class
        if (isset($config['class']['manager'])) {
            $container->setParameter('brother_contact_us.manager.entry.class', $config['class']['manager']);
        }


    }
}
