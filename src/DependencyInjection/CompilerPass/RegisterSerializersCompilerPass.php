<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass;

use Broadway\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterSerializersCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container)
    {
        foreach (['metadata', 'payload', 'readmodel'] as $serializer) {
            $serviceParameter = sprintf('broadway.serializer.%s.service_id', $serializer);
            if (!$container->hasParameter($serviceParameter)) {
                continue;
            }

            $id = $container->getParameter($serviceParameter);

            $this->assertDefinitionImplementsInterface($container, $id, SerializerInterface::class);

            $container->setAlias(
                sprintf('broadway.serializer.%s', $serializer),
                $container->getParameter(sprintf('broadway.serializer.%s.service_id', $serializer))
            );
        }
    }
}
