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

class EventStoreExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new BroadwayExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_does_not_register_the_event_store_service_when_not_configured(): void
    {
        $this->load([]);

        $this->assertFalse($this->container->hasParameter('broadway.event_store.service_id'));
    }

    /**
     * @test
     */
    public function it_registers_the_event_store_service_when_configured(): void
    {
        $this->load([
            'event_store' => 'my_event_store',
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.event_store.service_id',
            'my_event_store'
        );
    }
}
