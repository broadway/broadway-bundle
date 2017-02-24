<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Broadway\EventStore\EventStoreInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterEventStoreCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container)
    {
        $serviceParameter = 'broadway.event_store.service_id';
        if (! $container->hasParameter($serviceParameter)) {
            $container->setAlias('broadway.event_store', 'broadway.event_store.in_memory');

            return;
        }

        $serviceId = $container->getParameter($serviceParameter);

        $this->assertDefinitionImplementsInterface($container, $serviceId, EventStoreInterface::class);

        $container->setAlias('broadway.event_store', $serviceId);
    }
}
