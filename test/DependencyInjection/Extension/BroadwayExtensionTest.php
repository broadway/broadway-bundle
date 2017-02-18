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

class BroadwayExtensionTest extends AbstractExtensionTestCase
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
    public function it_sets_the_event_store_service_id()
    {
        $this->load([
            'event_store' => 'my_event_store',
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.event_store.service_id',
            'my_event_store'
        );
    }

    /**
     * @test
     */
    public function it_sets_the_read_model_factory_service_id()
    {
        $this->load([
            'read_model' => 'my_read_model_repository_factory',
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );
    }

    /**
     * @test
     */
    public function it_sets_serializer_service_ids()
    {
        $this->load([
            'serializer' => [
                'metadata'  => 'my_metadata_serializer',
                'payload'   => 'my_payload_serializer',
                'readmodel' => 'my_readmodel_serializer',
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.serializer.metadata.service_id',
            'my_metadata_serializer'
        );

        $this->assertContainerBuilderHasParameter(
            'broadway.serializer.payload.service_id',
            'my_payload_serializer'
        );

        $this->assertContainerBuilderHasParameter(
            'broadway.serializer.readmodel.service_id',
            'my_readmodel_serializer'
        );
    }
}
