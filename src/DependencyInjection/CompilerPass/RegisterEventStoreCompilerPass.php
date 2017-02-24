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

use Broadway\EventStore\EventStoreInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterEventStoreCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container)
    {
        $serviceId = $container->getParameter('broadway.event_store.service_id');

        $this->assertDefinitionImplementsInterface($container, $serviceId, EventStoreInterface::class);

        $container->setAlias('broadway.event_store', $serviceId);
    }
}
