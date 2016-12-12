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
    public function it_configures_dbal_as_default_event_store()
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'event_store' => [
                    'dbal'    => [
                        'enabled'    => true,
                        'table'      => 'events',
                        'connection' => 'default',
                        'use_binary' => false,
                    ]
                ]
            ],
            'event_store'
        );
    }
}
