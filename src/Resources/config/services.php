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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Broadway\Bundle\BroadwayBundle\Command\CommandMetadataEnricher;
use Broadway\CommandHandling\EventDispatchingCommandBus;
use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\CallableEventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventSourcing\MetadataEnrichment\MetadataEnrichingEventStreamDecorator;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\ReadModel\InMemory\InMemoryRepositoryFactory;
use Broadway\UuidGenerator\Converter\BinaryUuidConverter;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('broadway.command_handling.simple_command_bus', SimpleCommandBus::class);

    $services->set('broadway.command_handling.event_dispatching_command_bus', EventDispatchingCommandBus::class)
        ->args([
            service('broadway.command_handling.simple_command_bus'),
            service('broadway.event_dispatcher'),
        ]);

    $services->set('broadway.event_handling.event_bus', SimpleEventBus::class);

    $services->set('broadway.uuid.converter', BinaryUuidConverter::class);

    $services->set('broadway.uuid.generator', Version4Generator::class)
        ->public();

    $services->set('broadway.metadata_enriching_event_stream_decorator', MetadataEnrichingEventStreamDecorator::class);

    $services->set('broadway.metadata_enricher.console', CommandMetadataEnricher::class)
        ->tag('kernel.event_listener', ['event' => 'console.command', 'method' => 'handleConsoleCommandEvent'])
        ->tag('broadway.metadata_enricher');

    $services->set('broadway.event_dispatcher', CallableEventDispatcher::class);

    $services->set('broadway.event_store.in_memory', InMemoryEventStore::class);

    $services->set('broadway.read_model.in_memory.repository_factory', InMemoryRepositoryFactory::class);
};
