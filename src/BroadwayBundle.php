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

namespace Broadway\Bundle\BroadwayBundle;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterBusSubscribersCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterEventListenerCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterEventStoreCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterMetadataEnricherSubscriberPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterReadModelRepositoryFactoryCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterSagaCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterSagaStateRepositoryCompilerPass;
use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterSerializersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BroadwayBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterEventStoreCompilerPass());
        $container->addCompilerPass(new RegisterReadModelRepositoryFactoryCompilerPass());
        $container->addCompilerPass(new RegisterSagaStateRepositoryCompilerPass());

        $container->addCompilerPass(
            new RegisterSagaCompilerPass(
                'broadway.saga.multiple_saga_manager',
                'broadway.saga'
            )
        );
        $container->addCompilerPass(
            new RegisterBusSubscribersCompilerPass(
                'broadway.command_handling.command_bus',
                'broadway.command_handler',
                \Broadway\CommandHandling\CommandHandler::class
            )
        );
        $container->addCompilerPass(
            new RegisterBusSubscribersCompilerPass(
                'broadway.event_handling.event_bus',
                'broadway.domain.event_listener',
                \Broadway\EventHandling\EventListener::class
            )
        );
        $container->addCompilerPass(
            new RegisterEventListenerCompilerPass(
                'broadway.event_dispatcher',
                'broadway.event_listener'
            )
        );
        $container->addCompilerPass(
            new RegisterMetadataEnricherSubscriberPass(
                'broadway.metadata_enriching_event_stream_decorator',
                'broadway.metadata_enricher'
            )
        );
        $container->addCompilerPass(
            new RegisterSerializersCompilerPass()
        );
    }
}
