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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterReadModelRepositoryFactoryCompilerPass;
use Broadway\ReadModel\RepositoryFactory;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterReadModelRepositoryFactoryCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterReadModelRepositoryFactoryCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_read_model_repository_factory_alias_to_in_memory_by_default(): void
    {
        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'broadway.read_model.repository_factory',
            'broadway.read_model.in_memory.repository_factory'
        );

        $this->assertTrue($this->container->getAlias('broadway.read_model.repository_factory')->isPublic());
    }

    /**
     * @test
     */
    public function it_sets_the_read_model_repository_factory_alias(): void
    {
        $this->container->setParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );

        $this->setDefinition('my_read_model_repository_factory', new Definition(RepositoryFactory::class));

        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'broadway.read_model.repository_factory',
            'my_read_model_repository_factory'
        );

        $this->assertTrue($this->container->getAlias('broadway.read_model.repository_factory')->isPublic());
    }

    /**
     * @test
     */
    public function it_throws_when_configured_read_model_repository_factory_has_no_definition(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service id "my_read_model_repository_factory" could not be found in container');
        $this->container->setParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );

        $this->compile();
    }

    /**
     * @test
     */
    public function it_throws_when_configured_read_model_repository_factory_does_not_implement_event_store_interface(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service "stdClass" must implement interface "Broadway\ReadModel\RepositoryFactory".');
        $this->container->setParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );

        $this->setDefinition('my_read_model_repository_factory', new Definition(\stdClass::class));

        $this->compile();
    }
}
