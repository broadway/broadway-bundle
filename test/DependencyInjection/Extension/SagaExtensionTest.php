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

class SagaExtensionTest extends AbstractExtensionTestCase
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
    public function it_does_not_configure_saga_by_default()
    {
        $this->load([]);

        $this->assertContainerBuilderNotHasService('broadway.saga.state.state_manager');
        $this->assertContainerBuilderNotHasService('broadway.saga.state.repository');
    }

    /**
     * @test
     */
    public function it_aliases_the_in_memory_saga_state_repository()
    {
        $this->load([
            'saga' => [
                'repository' => 'in_memory',
            ],
        ]);

        $this->assertContainerBuilderHasAlias(
            'broadway.saga.state.repository',
            'broadway.saga.state.in_memory_repository'
        );
    }

    /**
     * @test
     */
    public function it_uses_the_configured_storage_suffix_for_mongodb_saga_storage()
    {
        $this->load([
            'saga' => [
                'mongodb' => [
                    'storage_suffix' => 'foo_suffix'
                ]
            ]
        ]);

        $this->assertContainerBuilderHasParameter('broadway.saga.mongodb.storage_suffix', 'foo_suffix');
    }

    /**
     * @test
     */
    public function it_defaults_to_empty_string_when_no_storage_suffix_is_configured()
    {
        $this->load(['saga' => []]);

        $this->assertContainerBuilderHasParameter('broadway.saga.mongodb.storage_suffix', '');
    }

    /**
     * @test
     */
    public function it_uses_configured_connection_details()
    {
        $dsn     = 'mongodb://12.34.45.6:27018/awesome';
        $options = [
            'connectTimeoutMS' => 50
        ];

        $this->load([
            'saga' => [
                'repository' => 'mongodb',
                'mongodb'    => [
                    'connection' => [
                        'dsn'      => $dsn,
                        'database' => 'my_database',
                        'options'  => $options,
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'broadway.saga.state.mongodb_connection',
            0,
            $dsn
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'broadway.saga.state.mongodb_connection',
            1,
            $options
        );
    }
}
