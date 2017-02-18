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

class ReadModelExtensionTest extends AbstractExtensionTestCase
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
    public function it_sets_in_memory_as_default_read_model_repository_factory()
    {
        $this->load([]);

        $this->assertContainerBuilderHasAlias(
            'broadway.read_model.repository_factory',
            'broadway.read_model.in_memory.repository_factory'
        );
    }

    /**
     * @test
     */
    public function it_sets_elasticsearch_parameters()
    {
        $options = [
            'hosts' => [
                'localhost:9200',
            ],
        ];

        $this->load([
            'read_model' => [
                'repository'    => 'elasticsearch',
                'elasticsearch' => $options,
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'broadway.elasticsearch.client',
            0,
            $options
        );
    }

    /**
     * @test
     */
    public function it_sets_in_memory_as_read_model_repository_factory()
    {
        $this->load(
            [
                'read_model' => [
                    'repository' => 'in_memory',
                ],
            ]
        );

        $this->assertContainerBuilderHasAlias(
            'broadway.read_model.repository_factory',
            'broadway.read_model.in_memory.repository_factory'
        );
    }
}
