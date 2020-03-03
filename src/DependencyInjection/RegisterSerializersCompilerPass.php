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

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterSerializersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach (['metadata', 'payload', 'readmodel'] as $serializer) {
            $serviceParameter = sprintf('broadway.serializer.%s.service_id', $serializer);
            if (!$container->hasParameter($serviceParameter)) {
                continue;
            }

            $id = $container->getParameter($serviceParameter);

            if (!$container->hasDefinition($id)) {
                throw new \InvalidArgumentException(sprintf('Serializer with service id "%s" could not be found', $id));
            }

            $container->setAlias(
                sprintf('broadway.serializer.%s', $serializer),
                new Alias(
                    $container->getParameter(sprintf('broadway.serializer.%s.service_id', $serializer)),
                    true
                )
            );
        }
    }
}
