<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterSerializersCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterSerializersCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterSerializersCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_public_serializer_aliases()
    {
        $this->setDefinition('my_serializer', new Definition());

        $this->container->setParameter('broadway.serializer.payload.service_id', 'my_serializer');
        $this->container->setParameter('broadway.serializer.readmodel.service_id', 'my_serializer');
        $this->container->setParameter('broadway.serializer.metadata.service_id', 'my_serializer');

        $this->compile();

        $this->assertContainerBuilderHasAlias('broadway.serializer.payload', 'my_serializer');
        $this->assertContainerBuilderHasAlias('broadway.serializer.readmodel', 'my_serializer');
        $this->assertContainerBuilderHasAlias('broadway.serializer.metadata', 'my_serializer');

        $this->assertTrue($this->container->getAlias('broadway.serializer.payload')->isPublic());
        $this->assertTrue($this->container->getAlias('broadway.serializer.readmodel')->isPublic());
        $this->assertTrue($this->container->getAlias('broadway.serializer.metadata')->isPublic());
    }

    /**
     * @test
     */
    public function it_throws_when_serializer_has_no_definition()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Serializer with service id "my_serializer" could not be found');
        $this->container->setParameter('broadway.serializer.payload.service_id', 'my_serializer');
        $this->container->setParameter('broadway.serializer.readmodel.service_id', 'my_serializer');
        $this->container->setParameter('broadway.serializer.metadata.service_id', 'my_serializer');

        $this->compile();
    }
}
