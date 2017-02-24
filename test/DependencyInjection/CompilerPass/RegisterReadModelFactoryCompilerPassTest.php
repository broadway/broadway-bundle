<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass\RegisterReadModelFactoryCompilerPass;
use Broadway\ReadModel\RepositoryFactoryInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterReadModelCompilerCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterReadModelFactoryCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_read_model_repository_factory_alias()
    {
        $this->container->setParameter('broadway.read_model_repository_factory.service_id', 'my_read_model_repository_factory');

        $this->setDefinition('my_read_model_repository_factory', new Definition(RepositoryFactoryInterface::class));

        $this->compile();

        $this->assertContainerBuilderHasAlias('broadway.read_model', 'my_read_model_repository_factory');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Service id "my_read_model_repository_factory" could not be found in container
     */
    public function it_throws_when_configured_read_model_factory_has_no_definition()
    {
        $this->container->setParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );

        $this->compile();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Service "stdClass" must implement interface "Broadway\ReadModel\RepositoryFactoryInterface".
     */
    public function it_throws_when_configured_read_model_factory_does_not_implement_read_model_factory_interface()
    {
        $this->container->setParameter('broadway.read_model_repository_factory.service_id', 'my_read_model_repository_factory');

        $this->setDefinition('my_read_model_repository_factory', new Definition(\stdClass::class));

        $this->compile();
    }

    /**
     * @test
     */
    public function compilation_should_not_fail_with_empty_container()
    {
        $this->markTestSkipped('a read model repository factory service id should be defined');
    }
}

