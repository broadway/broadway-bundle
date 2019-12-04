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

class CommandHandlingExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions() : array
    {
        return [
            new BroadwayExtension(),
        ];
    }


    /**
     * @test
     */
    public function it_creates_a_public_alias_to_the_simple_command_bus()
    {
        $this->load([]);

        $this->assertContainerBuilderHasAlias(
            'broadway.command_handling.command_bus',
            'broadway.command_handling.simple_command_bus'
        );

        $this->assertTrue($this->container->getAlias('broadway.command_handling.command_bus')->isPublic());
    }

    /**
     * @test
     */
    public function it_creates_a_public_alias_for_the_logging_command_bus()
    {
        $this->load([
            'command_handling' => [
                'logger' => 'my_service',
            ]
        ]);

        $this->assertContainerBuilderHasAlias(
            'broadway.command_handling.command_bus',
            'broadway.command_handling.event_dispatching_command_bus'
        );

        $this->assertTrue($this->container->getAlias('broadway.command_handling.command_bus')->isPublic());
    }

    /**
     * @test
     */
    public function it_creates_a_public_alias_for_the_auditing_logger()
    {
        $this->load([
            'command_handling' => [
                'logger' => 'my_service',
            ]
        ]);

        $this->assertContainerBuilderHasAlias(
            'broadway.auditing.logger',
            'my_service'
        );

        $this->assertTrue($this->container->getAlias('broadway.auditing.logger')->isPublic());
    }

    /**
     * @test
     */
    public function it_can_enable_the_event_dispatching_command_bus_but_not_the_logger()
    {
        $this->load([
            'command_handling' => [
                'dispatch_events' => true,
                'logger'          => 'my_service',
            ]
        ]);

        $this->assertContainerBuilderHasAlias(
            'broadway.command_handling.command_bus',
            'broadway.command_handling.event_dispatching_command_bus'
        );

        $this->assertFalse($this->container->hasAlias('broadway.auditing.command_logger'));
    }
}
