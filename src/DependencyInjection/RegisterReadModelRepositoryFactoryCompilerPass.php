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

use Broadway\ReadModel\RepositoryFactory;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterReadModelRepositoryFactoryCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container)
    {
        $serviceParameter = 'broadway.read_model_repository_factory.service_id';
        if (!$container->hasParameter($serviceParameter)) {
            $container->setAlias(
                'broadway.read_model.repository_factory',
                new Alias('broadway.read_model.in_memory.repository_factory', true)
            );

            return;
        }

        $serviceId = $container->getParameter($serviceParameter);

        $this->assertDefinitionImplementsInterface($container, $serviceId, RepositoryFactory::class);

        $container->setAlias('broadway.read_model.repository_factory', new Alias($serviceId, true));
    }
}
