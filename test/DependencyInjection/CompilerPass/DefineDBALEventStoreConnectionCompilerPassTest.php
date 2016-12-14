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

use Broadway\Bundle\BroadwayBundle\DependencyInjection\DefineDBALEventStoreConnectionCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class DefineDBALEventStoreConnectionCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DefineDBALEventStoreConnectionCompilerPass('broadway'));
    }

    /**
     * @test
     */
    public function it_aliases_the_dbal_connection()
    {
        $this->container->setParameter('broadway.event_store.dbal.connection', 'default');
        $this->container->setDefinition('doctrine.dbal.default_connection', new Definition());

        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'broadway.event_store.dbal.connection',
            'doctrine.dbal.default_connection'
        );
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid broadway config: DBAL connection "default" not found
     */
    public function it_throws_when_connection_not_defined()
    {
        $this->container->setParameter('broadway.event_store.dbal.connection', 'default');

        $this->compile();
    }
}
