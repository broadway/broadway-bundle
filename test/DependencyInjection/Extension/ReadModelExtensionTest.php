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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Extension;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\BroadwayExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ReadModelExtensionTest extends AbstractExtensionTestCase
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
    public function it_does_not_register_the_read_model_repository_factory_service_when_not_configured()
    {
        $this->load([]);

        $this->assertFalse($this->container->hasParameter('broadway.read_model_repository_factory.service_id'));
    }

    /**
     * @test
     */
    public function it_registers_the_read_model_repository_factory_service_when_configured()
    {
        $this->load([
            'read_model' => 'my_read_model_repository_factory',
        ]);

        $this->assertContainerBuilderHasParameter(
            'broadway.read_model_repository_factory.service_id',
            'my_read_model_repository_factory'
        );
    }
}
