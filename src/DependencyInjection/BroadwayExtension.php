<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/broadway-bundle package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BroadwayExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (isset($mergedConfig['event_store'])) {
            $container->setParameter('broadway.event_store.service_id', $mergedConfig['event_store']);
        }

        if (isset($mergedConfig['read_model'])) {
            $container->setParameter('broadway.read_model_repository_factory.service_id', $mergedConfig['read_model']);
        }

        $this->loadCommandBus($mergedConfig['command_handling'], $container, $loader);
        $this->loadSerializers($mergedConfig['serializer'], $container, $loader);

        if (isset($mergedConfig['saga']) && isset($mergedConfig['saga']['enabled']) && $mergedConfig['saga']['enabled']) {
            $loader->load('saga.xml');

            if (isset($mergedConfig['saga']['state_repository'])) {
                $container->setParameter(
                    'broadway.saga.state.repository.service_id',
                    $mergedConfig['saga']['state_repository']
                );
            }
        }
    }

    private function loadCommandBus(array $config, ContainerBuilder $container, LoaderInterface $loader): void
    {
        if ($config['dispatch_events']) {
            $container->setAlias(
                'broadway.command_handling.command_bus',
                new Alias('broadway.command_handling.event_dispatching_command_bus', true)
            );

            if ($logger = $config['logger']) {
                $loader->load('auditing.xml');
                $container->setAlias('broadway.auditing.logger', new Alias($logger, true));
            }
        } else {
            $container->setAlias(
                'broadway.command_handling.command_bus',
                new Alias('broadway.command_handling.simple_command_bus', true)
            );
        }
    }

    private function loadSerializers(array $config, ContainerBuilder $container, XmlFileLoader $loader): void
    {
        $loader->load('serializers.xml');

        foreach ($config as $serializer => $serviceId) {
            $container->setParameter(sprintf('broadway.serializer.%s.service_id', $serializer), $serviceId);
        }
    }
}
