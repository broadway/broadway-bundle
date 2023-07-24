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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterEventListenerCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterEventListenerCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new RegisterEventListenerCompilerPass(
                'broadway.event_dispatcher',
                'broadway.event_listener'
            )
        );
    }

    /**
     * @test
     */
    public function it_registers_event_listeners(): void
    {
        $this->setDefinition(
            'broadway.event_dispatcher',
            new Definition()
        );

        $eventListener1 = new Definition();
        $eventListener1->addTag('broadway.event_listener', [
            'event' => 'my_event',
            'method' => 'handleMyEvent',
        ]);
        $this->setDefinition('event_listener_1', $eventListener1);

        $eventListener2 = new Definition();
        $eventListener2->addTag('broadway.event_listener', [
            'event' => 'my_event',
            'method' => 'handleMyEvent',
        ]);
        $this->setDefinition('event_listener_2', $eventListener2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'broadway.event_dispatcher',
            'addListener',
            [
                'my_event',
                [
                    new Reference('event_listener_1'),
                    'handleMyEvent',
                ],
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'broadway.event_dispatcher',
            'addListener',
            [
                'my_event',
                [
                    new Reference('event_listener_2'),
                    'handleMyEvent',
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function it_throws_when_event_listeners_has_no_event_or_method(): void
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Event Listener tag should contain the event and method (<tag name="broadway.event_listener" event="event_name" method="methodToCall" />)');
        $this->setDefinition('broadway.event_dispatcher', new Definition());

        $eventListener = new Definition();
        $eventListener->addTag('broadway.event_listener');
        $this->setDefinition('event_listener', $eventListener);

        $this->compile();
    }
}
