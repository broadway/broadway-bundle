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

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterSagaStateRepositoryCompilerPass;
use Broadway\Saga\State\RepositoryInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterSagaStateRepositoryCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $this->setDefinition('broadway.saga.state.in_memory_repository', new Definition(RepositoryInterface::class));

        $container->addCompilerPass(new RegisterSagaStateRepositoryCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_saga_state_repository_alias_to_in_memory_by_default()
    {
        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'broadway.saga.state.repository',
            'broadway.saga.state.in_memory_repository'
        );

        $this->assertTrue($this->container->getAlias('broadway.saga.state.repository')->isPublic());
    }

    /**
     * @test
     */
    public function it_sets_the_saga_state_repository_alias()
    {
        $this->container->setParameter(
            'broadway.saga.state.repository.service_id',
            'my_saga_state_repository'
        );

        $this->setDefinition('my_saga_state_repository', new Definition(RepositoryInterface::class));

        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'broadway.saga.state.repository',
            'my_saga_state_repository'
        );

        $this->assertTrue($this->container->getAlias('broadway.saga.state.repository')->isPublic());
    }

    /**
     * @test
     */
    public function it_throws_when_configured_saga_state_repository_has_no_definition()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service id "my_saga_state_repository" could not be found in container');
        $this->container->setParameter(
            'broadway.saga.state.repository.service_id',
            'my_saga_state_repository'
        );

        $this->compile();
    }

    /**
     * @test
     */
    public function it_throws_when_configured_saga_state_repository_does_not_implement_event_store_interface()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service "stdClass" must implement interface "Broadway\Saga\State\RepositoryInterface".');
        $this->container->setParameter(
            'broadway.saga.state.repository.service_id',
            'my_saga_state_repository'
        );

        $this->setDefinition('my_saga_state_repository', new Definition(\stdClass::class));

        $this->compile();
    }
}
