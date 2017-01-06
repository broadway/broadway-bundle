<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;
use Broadway\Bundle\BroadwayBundle\TestCase;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;

class EventStoreConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @inheritdoc
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_configures_the_default_event_store()
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'event_store' => [
                    'type'       => 'in_memory',

                    // these are not applicable to in_memory type
                    'table'      => 'events',
                    'connection' => 'default',
                    'use_binary' => false,
                ]
            ],
            'event_store'
        );
    }

    /**
     * @test
     */
    public function it_configures_the_dbal_event_store_with_defaults()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'event_store' => [
                        'type' => 'dbal',
                    ],
                ],
            ],
            [
                'event_store' => [
                    'type'       => 'dbal',
                    'table'      => 'events',
                    'connection' => 'default',
                    'use_binary' => false,
                ],
            ],
            'event_store'
        );
    }

    /**
     * @test
     */
    public function it_configures_the_dbal_event_store()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'event_store' => [
                        'type'       => 'dbal',
                        'table'      => 'my_events_table',
                        'connection' => 'my_doctrine_connection',
                        'use_binary' => true,
                    ],
                ],
            ],
            [
                'event_store' => [
                        'type'       => 'dbal',
                        'table'      => 'my_events_table',
                        'connection' => 'my_doctrine_connection',
                        'use_binary' => true,
                ],
            ],
            'event_store'
        );
    }

    /**
     * @test
     */
    public function it_configures_a_custom_event_store()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'event_store' => [
                        'type' => 'service',
                        'id'   => 'my_event_store_service',
                    ],
                ],
            ],
            [
                'event_store' => [
                    'type' => 'service',
                    'id'   => 'my_event_store_service',

                    // these are not applicable to service type
                    'table'      => 'events',
                    'connection' => 'default',
                    'use_binary' => false,
                ]
            ],
            'event_store'
        );
    }
}
