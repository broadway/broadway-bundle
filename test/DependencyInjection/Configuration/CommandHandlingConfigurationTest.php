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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class CommandHandlingConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_disables_logger_and_event_dispatching_by_default(): void
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'command_handling' => [
                    'dispatch_events' => false,
                    'logger' => false,
                ],
            ],
            'command_handling'
        );
    }

    /**
     * @test
     */
    public function it_enables_event_dispatching_when_logger_is_enabled(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'command_handling' => [
                        'logger' => 'logger',
                        'dispatch_events' => false,
                    ],
                ],
            ],
            [
                'command_handling' => [
                    'dispatch_events' => true,
                    'logger' => 'logger',
                ],
            ],
            'command_handling'
        );
    }
}
