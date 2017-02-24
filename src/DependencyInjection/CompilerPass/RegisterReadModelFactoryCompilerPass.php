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

use Broadway\ReadModel\RepositoryFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterReadModelFactoryCompilerPass extends CompilerPass
{
    public function process(ContainerBuilder $container)
    {
        $serviceId = $container->getParameter('broadway.read_model_repository_factory.service_id');

        $this->assertDefinitionImplementsInterface($container, $serviceId, RepositoryFactoryInterface::class);

        $container->setAlias('broadway.read_model', $serviceId);
    }
}
