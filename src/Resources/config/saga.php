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

use Broadway\Saga\Metadata\StaticallyConfiguredSagaMetadataFactory;
use Broadway\Saga\MultipleSagaManager;
use Broadway\Saga\SagaMetadataEnricher;
use Broadway\Saga\State\InMemoryRepository;
use Broadway\Saga\State\StateManager;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('broadway.saga.state.state_manager', StateManager::class)
        ->args([
            service('broadway.saga.state.repository'),
            service('broadway.uuid.generator'),
        ]);

    $services->set('broadway.saga.metadata.factory', StaticallyConfiguredSagaMetadataFactory::class);

    $services->set('broadway.saga.metadata_enricher', SagaMetadataEnricher::class)
        ->tag('broadway.event_listener', ['event' => 'broadway.saga.post_handle', 'method' => 'postHandleSaga'])
        ->tag('broadway.metadata_enricher');

    $services->set('broadway.saga.multiple_saga_manager', MultipleSagaManager::class)
        ->args([
            service('broadway.saga.state.repository'),
            // will be populated by RegisterSagaCompilerPass:
            [],
            service('broadway.saga.state.state_manager'),
            service('broadway.saga.metadata.factory'),
            service('broadway.event_dispatcher'),
        ]);

    $services->set('broadway.saga.state.in_memory_repository', InMemoryRepository::class);
};
