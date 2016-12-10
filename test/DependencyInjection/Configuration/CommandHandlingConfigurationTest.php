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

class CommandHandlingConfigurationTest extends TestCase
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
    public function it_disables_logger_and_event_dispatching_by_default()
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'command_handling' => [
                    'dispatch_events' => false,
                    'logger'          => false,
                ]
            ],
            'command_handling'
        );
    }

    /**
     * @test
     */
    public function it_enables_event_dispatching_when_logger_is_enabled()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'command_handling' => [
                        'logger'          => 'logger',
                        'dispatch_events' => false,
                    ],
                ],
            ],
            [
                'command_handling' => [
                    'dispatch_events' => true,
                    'logger'          => 'logger',
                ]
            ],
            'command_handling'
        );
    }
}
