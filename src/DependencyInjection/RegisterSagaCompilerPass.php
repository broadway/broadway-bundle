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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterSagaCompilerPass implements CompilerPassInterface
{
    private $multipleSagaManagerService;
    private $tagName;

    /**
     * @param string $multipleSagaManagerService
     * @param string $tagName
     */
    public function __construct($multipleSagaManagerService, $tagName)
    {
        $this->multipleSagaManagerService = $multipleSagaManagerService;
        $this->tagName = $tagName;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->multipleSagaManagerService)) {
            return;
        }

        $sagas = [];

        foreach ($container->findTaggedServiceIds($this->tagName) as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['type'])) {
                    throw new \InvalidArgumentException(sprintf('Tag "%s" of service "%s" should have a "type" attribute, indicating the type of saga it represents', $this->tagName, $serviceId));
                }

                $type = $attributes['type'];
                $sagas[$type] = new Reference($serviceId);
            }
        }

        if (count($sagas) > 0) {
            $container
                ->findDefinition($this->multipleSagaManagerService)
                ->replaceArgument(1, $sagas)
                ->addTag('broadway.domain.event_listener');
        }
    }
}
