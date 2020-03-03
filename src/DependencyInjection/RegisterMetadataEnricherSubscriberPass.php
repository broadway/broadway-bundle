<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers metadata enrichers.
 */
class RegisterMetadataEnricherSubscriberPass implements CompilerPassInterface
{
    private $enricherTag;
    private $enrichingStreamDecoratorServiceId;

    /**
     * @param string $enrichingStreamDecoratorServiceId
     * @param string $enricherTag
     */
    public function __construct($enrichingStreamDecoratorServiceId, $enricherTag)
    {
        $this->enrichingStreamDecoratorServiceId = $enrichingStreamDecoratorServiceId;
        $this->enricherTag = $enricherTag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (
            !$container->hasDefinition($this->enrichingStreamDecoratorServiceId)
            &&
            !$container->hasAlias($this->enrichingStreamDecoratorServiceId)
        ) {
            return;
        }

        $definition = $container->findDefinition($this->enrichingStreamDecoratorServiceId);

        foreach ($container->findTaggedServiceIds($this->enricherTag) as $id => $attributes) {
            $definition->addMethodCall('registerEnricher', [new Reference($id)]);
        }
    }
}
