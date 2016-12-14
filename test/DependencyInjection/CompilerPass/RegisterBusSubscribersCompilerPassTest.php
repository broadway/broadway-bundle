<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterBusSubscribersCompilerPass;
use Broadway\EventHandling\EventListenerInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterBusSubscribersCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new RegisterBusSubscribersCompilerPass(
                'broadway.event_handling.event_bus',
                'broadway.domain.event_listener',
                EventListenerInterface::class
            )
        );
    }

    /**
     * @test
     */
    public function it_registers_event_bus_subscribers()
    {
        $this->setDefinition(
            'broadway.event_handling.event_bus',
            new Definition()
        );

        $eventListener1 = new Definition(EventListenerInterface::class);
        $eventListener1->addTag('broadway.domain.event_listener');
        $this->setDefinition('event_listener_1', $eventListener1);

        $eventListener2 = new Definition(EventListenerInterface::class);
        $eventListener2->addTag('broadway.domain.event_listener');
        $this->setDefinition('event_listener_2', $eventListener2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'broadway.event_handling.event_bus',
            'subscribe',
            [
                new Reference('event_listener_1'),
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'broadway.event_handling.event_bus',
            'subscribe',
            [
                new Reference('event_listener_2'),
            ]
        );
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown Bus service known as broadway.event_handling.event_bus
     */
    public function it_throws_when_no_event_bus_service_defined_or_aliased()
    {
        $this->compile();
    }

    /**
     * @test
     */
    public function compilation_should_not_fail_with_empty_container()
    {
        $this->markTestSkipped('see self::it_throws_when_no_event_bus_service_defined_or_aliased');
    }
}
