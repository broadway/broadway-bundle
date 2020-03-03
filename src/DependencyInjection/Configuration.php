<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration definition.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('broadway');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('broadway');
        }

        $rootNode
            ->children()
                ->arrayNode('command_handling')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->always(function (array $v) {
                            if (isset($v['logger']) && $v['logger']) {
                                // auditing requires event dispatching
                                $v['dispatch_events'] = true;
                            }

                            return $v;
                        })
                    ->end()
                    ->children()
                        ->booleanNode('dispatch_events')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('logger')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('event_store')
                    ->info('a service definition id implementing Broadway\EventStore\EventStore')
                ->end()
                ->arrayNode('saga')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('state_repository')
                            ->info('a service definition id implementing Broadway\Saga\State\RepositoryInterface')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('serializer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('payload')->defaultValue('broadway.simple_interface_serializer')->end()
                        ->scalarNode('readmodel')->defaultValue('broadway.simple_interface_serializer')->end()
                        ->scalarNode('metadata')->defaultValue('broadway.simple_interface_serializer')->end()
                    ->end()
                ->end()
                ->scalarNode('read_model')
                    ->info('a service definition id implementing Broadway\ReadModel\RepositoryFactory')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
