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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to register tagged services for an event dispatcher.
 */
class RegisterEventListenerCompilerPass implements CompilerPassInterface
{
    private $eventDispatcherId;
    private $serviceTag;

    /**
     * @param string $eventDispatcherId
     * @param string $serviceTag
     */
    public function __construct($eventDispatcherId, $serviceTag)
    {
        $this->eventDispatcherId = $eventDispatcherId;
        $this->serviceTag = $serviceTag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->eventDispatcherId) && !$container->hasAlias($this->eventDispatcherId)) {
            return;
        }

        $definition = $container->findDefinition($this->eventDispatcherId);

        foreach ($container->findTaggedServiceIds($this->serviceTag) as $id => $attributes) {
            $this->processListenerDefinition($container, $definition, $id);
        }
    }

    private function processListenerDefinition(ContainerBuilder $container, Definition $dispatcher, $listenerId)
    {
        $def = $container->getDefinition($listenerId);
        $tags = $def->getTag($this->serviceTag);

        foreach ($tags as $tag) {
            if (!isset($tag['event']) || !isset($tag['method'])) {
                throw new RuntimeException(sprintf('Event Listener tag should contain the event and method (<tag name="%s" event="event_name" method="methodToCall" />)', $this->serviceTag));
            }

            $dispatcher->addMethodCall(
                'addListener',
                [
                    $tag['event'],
                    [
                        new Reference($listenerId),
                        $tag['method'],
                    ],
                ]
            );
        }
    }
}
