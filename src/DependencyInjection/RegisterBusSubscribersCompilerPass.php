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

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to register tagged services for an event dispatcher.
 */
class RegisterBusSubscribersCompilerPass implements CompilerPassInterface
{
    private $busService;
    private $serviceTag;
    private $subscriberInterface;

    /**
     * @param string $busService
     * @param string $serviceTag
     * @param string $subscriberInterface
     */
    public function __construct($busService, $serviceTag, $subscriberInterface)
    {
        $this->busService = $busService;
        $this->serviceTag = $serviceTag;
        $this->subscriberInterface = $subscriberInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->busService) && !$container->hasAlias($this->busService)) {
            return;
        }

        $definition = $container->findDefinition($this->busService);

        foreach ($container->findTaggedServiceIds($this->serviceTag) as $id => $attributes) {
            $def = $container->getDefinition($id);
            // Definition getClass can return a parameter
            $class = $container->getParameterBag()->resolveValue($def->getClass());

            $refClass = new ReflectionClass($class);

            if (!$refClass->implementsInterface($this->subscriberInterface)) {
                throw new InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $this->subscriberInterface));
            }

            $definition->addMethodCall('subscribe', [new Reference($id)]);
        }
    }
}
