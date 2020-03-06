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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Extension;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\BroadwayExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class SagaExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions(): array
    {
        return [
            new BroadwayExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_does_not_register_the_saga_state_manager_service_when_not_configured()
    {
        $this->load([]);

        $this->assertFalse($this->container->hasDefinition('broadway.saga.state.state_manager'));
    }

    /**
     * @test
     */
    public function it_does_not_register_the_saga_state_repository_service_when_not_configured()
    {
        $this->load([]);

        $this->assertFalse($this->container->hasParameter('broadway.saga.state.repository.service_id'));
    }

    /**
     * @test
     */
    public function it_registers_the_saga_state_repository_service_when_configured()
    {
        $this->load([
            'saga' => [
                'state_repository' => 'my_saga_state_repository',
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.saga.state.repository.service_id',
            'my_saga_state_repository'
        );
    }
}
