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

use Broadway\Bundle\BroadwayBundle\DependencyInjection\RegisterEventStoreCompilerPass;
use Broadway\EventStore\EventStore;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterEventStoreCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterEventStoreCompilerPass());
    }

    /**
     * @test
     */
    public function it_sets_the_event_store_alias_to_in_memory_by_default(): void
    {
        $this->compile();

        $this->assertContainerBuilderHasAlias('broadway.event_store', 'broadway.event_store.in_memory');
    }

    /**
     * @test
     */
    public function it_sets_the_public_event_store_alias(): void
    {
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->setDefinition('my_event_store', new Definition(EventStore::class));

        $this->compile();

        $this->assertContainerBuilderHasAlias('broadway.event_store', 'my_event_store');
        $this->assertTrue($this->container->getAlias('broadway.event_store')->isPublic());
    }

    /**
     * @test
     */
    public function it_throws_when_configured_event_store_has_no_definition(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service id "my_event_store" could not be found in container');
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->compile();
    }

    /**
     * @test
     */
    public function it_throws_when_configured_event_store_does_not_implement_event_store_interface(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Service "stdClass" must implement interface "Broadway\EventStore\EventStore".');
        $this->container->setParameter('broadway.event_store.service_id', 'my_event_store');

        $this->setDefinition('my_event_store', new Definition(\stdClass::class));

        $this->compile();
    }
}
