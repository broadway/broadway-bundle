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

use Broadway\Auditing\CommandLogger;
use Broadway\Auditing\NullByteCommandSerializer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('broadway.auditing.command_logger', CommandLogger::class)
        ->args([
            service('broadway.auditing.logger'),
            service('broadway.auditing.serializer'),
        ])
        ->tag('broadway.event_listener', [
            'event' => 'broadway.command_handling.command_success',
            'method' => 'onCommandHandlingSuccess',
        ])
        ->tag('broadway.event_listener', [
            'event' => 'broadway.command_handling.command_failure',
            'method' => 'onCommandHandlingFailure',
        ]);

    $services->set('broadway.auditing.serializer', NullByteCommandSerializer::class);
};
