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

use Broadway\Bundle\BroadwayBundle\DependencyInjection\CompilerPass\RegisterEventStoreCompilerPass;
use Broadway\EventStore\EventStoreInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterEventStoreCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterEventStoreCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_event_store_alias()
    {
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->setDefinition('my_event_store', new Definition(EventStoreInterface::class));

        $this->compile();

        $this->assertContainerBuilderHasAlias('broadway.event_store', 'my_event_store');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Service id "my_event_store" could not be found in container
     */
    public function it_throws_when_configured_event_store_has_no_definition()
    {
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->compile();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Service "stdClass" must implement interface "Broadway\EventStore\EventStoreInterface".
     */
    public function it_throws_when_configured_event_store_does_not_implement_event_store_interface()
    {
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->setDefinition('my_event_store', new Definition(\stdClass::class));

        $this->compile();
    }

    /**
     * @test
     */
    public function compilation_should_not_fail_with_empty_container()
    {
        $this->markTestSkipped('an event store service id should be defined');
    }
}
