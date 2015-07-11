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
        $contact_usConfig = $this->processConfiguration(new Configuration(), $configs);

        // enable spam detection if AkismetBundle is registered
        // else disable spam detection
        // can be overridden by setting the brother_contact_us.spam_detection.enable config
        $brotherConfig['spam_detection'] = isset($bundles['AkismetBundle']) ? true : false;

        // add the BrotherContactUsBundle configurations
        // all options can be overridden in the app/config/config.yml file
        $container->prependExtensionConfig('brother_contact_us', $brotherConfig);
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.yml', $config['db_driver']));

        $loader->load('form.yml');

        // core config
        $container->setParameter('brother_contact_us.date_format', $config['date_format']);
        $container->setParameter('brother_contact_us.notify_admin', $config['notify_admin']);

        // mailer
        $container->setParameter('brother_contact_us.mailer.class', $config['class']['mailer']);
        $container->setParameter('brother_contact_us.mailer.email_title', $config['mailer']['email_title']);
        $container->setParameter('brother_contact_us.mailer.admin_email', $config['mailer']['admin_email']);
        $container->setParameter('brother_contact_us.mailer.sender_email', $config['mailer']['sender_email']);

        // forms
        $container->setParameter('brother_contact_us.form.entry.name', $config['form']['entry']['name']);
        $container->setParameter('brother_contact_us.form.entry.type', $config['form']['entry']['type']);
        $container->setParameter('brother_contact_us.form.entry.class', $config['form']['entry']['class']);

        // set model class
        if (isset($config['class']['model'])) {
            $container->setParameter('brother_contact_us.model.entry.class', $config['class']['model']);
        }

        // set manager class
        if (isset($config['class']['manager'])) {
            $container->setParameter('brother_contact_us.manager.entry.class', $config['class']['manager']);
        }

        // load custom mailer service if set
        if (isset($config['service']['mailer'])) {
            $container->setAlias('brother_contact_us.mailer', $config['service']['mailer']);
        }

        $this->registerDoctrineMapping($config);

    }

    public function registerDoctrineMapping(array $config)
    {
    }
}
