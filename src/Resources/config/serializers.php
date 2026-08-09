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

use Broadway\Serializer\SimpleInterfaceSerializer;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('broadway.simple_interface_serializer', SimpleInterfaceSerializer::class);
};
