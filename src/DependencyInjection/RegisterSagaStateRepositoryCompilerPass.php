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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection;

use Broadway\Saga\State\RepositoryInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterSagaStateRepositoryCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('broadway.saga.state.in_memory_repository')) {
            return;
        }

        $serviceParameter = 'broadway.saga.state.repository.service_id';
        if (!$container->hasParameter($serviceParameter)) {
            $container->setAlias(
                'broadway.saga.state.repository',
                new Alias('broadway.saga.state.in_memory_repository', true)
            );

            return;
        }

        $serviceId = $container->getParameter($serviceParameter);
        if (false === is_string($serviceId)) {
            return;
        }

        $this->assertDefinitionImplementsInterface($container, $serviceId, RepositoryInterface::class);

        $container->setAlias(
            'broadway.saga.state.repository',
            new Alias($serviceId, true)
        );
    }
}
