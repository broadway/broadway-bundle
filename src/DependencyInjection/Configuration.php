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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration definition.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('broadway');

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
                    ->info('a service definition id implementing Broadway\EventStore\EventStoreInterface')
                ->end()
                ->arrayNode('saga')
                    ->children()
                        ->enumNode('repository')
                            ->values(['in_memory', 'mongodb'])
                            ->defaultValue('in_memory')
                        ->end()
                        ->arrayNode('mongodb')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('connection')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('dsn')->defaultNull()->end()
                                        ->scalarNode('database')->defaultNull()->end()
                                        ->arrayNode('options')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->scalarNode('storage_suffix')->defaultNull()->end()
                            ->end()
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
                    ->info('a service definition id implementing Broadway\ReadModel\RepositoryFactoryInterface')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
