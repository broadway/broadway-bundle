<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BroadwayExtension extends ConfigurableExtension
{
    /**
     * {@inheritDoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if (isset($mergedConfig['event_store'])) {
            $container->setParameter('broadway.event_store.service_id', $mergedConfig['event_store']);
        }

        if (isset($mergedConfig['read_model'])) {
            $container->setParameter('broadway.read_model_repository_factory.service_id', $mergedConfig['read_model']);
        }

        $this->loadCommandBus($mergedConfig['command_handling'], $container, $loader);
        $this->loadSerializers($mergedConfig['serializer'], $container, $loader);

        if (isset($mergedConfig['saga'])) {
            $loader->load('saga.xml');
            $this->loadSagaStateRepository($mergedConfig['saga'], $container, $loader);
        }
    }

    private function loadCommandBus(array $config, ContainerBuilder $container, LoaderInterface $loader)
    {
        if ($config['dispatch_events']) {
            $container->setAlias(
                'broadway.command_handling.command_bus',
                'broadway.command_handling.event_dispatching_command_bus'
            );

            if ($logger = $config['logger']) {
                $loader->load('auditing.xml');
                $container->setAlias('broadway.auditing.logger', $logger);
            }
        } else {
            $container->setAlias(
                'broadway.command_handling.command_bus',
                'broadway.command_handling.simple_command_bus'
            );
        }
    }

    private function loadSagaStateRepository(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        switch ($config['repository']) {
            case 'mongodb':
                $loader->load('saga/mongodb.xml');
                $container->setAlias(
                    'broadway.saga.state.repository',
                    'broadway.saga.state.mongodb_repository'
                );

                $database = 'broadway_%kernel.environment%%broadway.saga.mongodb.storage_suffix%';

                if (isset($config['mongodb']['connection'])) {
                    if (isset($config['mongodb']['connection']['database'])) {
                        $database = $config['mongodb']['connection']['database'];
                    }

                    $mongoConnection = $container->getDefinition('broadway.saga.state.mongodb_connection');

                    if (isset($config['mongodb']['connection']['dsn'])) {
                        $mongoConnection->replaceArgument(0, $config['mongodb']['connection']['dsn']);
                    }

                    if (isset($config['mongodb']['connection']['options'])) {
                        $mongoConnection->replaceArgument(1, $config['mongodb']['connection']['options']);
                    }
                }

                $container->setParameter('broadway.saga.mongodb.storage_suffix', (string) $config['mongodb']['storage_suffix']);
                $container->setParameter('broadway.saga.mongodb.database', $database);
                break;
            case 'in_memory':
                $loader->load('saga/in_memory.xml');
                $container->setAlias(
                    'broadway.saga.state.repository',
                    'broadway.saga.state.in_memory_repository'
                );
                break;
        }
    }

    private function loadSerializers(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('serializers.xml');

        foreach ($config as $serializer => $serviceId) {
            $container->setParameter(sprintf('broadway.serializer.%s.service_id', $serializer), $serviceId);
        }
    }
}
