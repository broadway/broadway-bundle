<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Extension;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\BroadwayExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class EventStoreExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new BroadwayExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_has_dbal_as_default_event_store()
    {
        $this->load([]);

        $this->assertTrue(
            $this->container->hasDefinition('broadway.event_store.dbal')
        );
        $this->assertContainerBuilderHasAlias(
            'broadway.event_store',
            'broadway.event_store.dbal'
        );
    }

    /**
     * @test
     */
    public function it_sets_dbal_parameters()
    {
        $this->load([
            'event_store' => [
                'dbal' => [
                    'connection' => 'my_connection',
                    'table'      => 'my_events_table',
                    'use_binary' => true,
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('broadway.event_store.dbal.connection', 'my_connection');
        $this->assertContainerBuilderHasParameter('broadway.event_store.dbal.table', 'my_events_table');
        $this->assertContainerBuilderHasParameter('broadway.event_store.dbal.use_binary', true);
    }

    /**
     * @test
     */
    public function disabling_dbal_event_store_does_not_load_its_definitions()
    {
        $this->load([
            'event_store' => [
                'dbal' => [
                    'enabled' => false,
                ],
            ],
        ]);

        $this->assertContainerBuilderNotHasService('broadway.event_store.dbal');
        $this->assertContainerBuilderNotHasService('broadway.event_store');
    }
}
