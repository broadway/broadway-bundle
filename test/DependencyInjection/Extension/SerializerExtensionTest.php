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

class SerializerExtensionTest extends AbstractExtensionTestCase
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
    public function it_configures_the_serializers()
    {
        $this->load([
            'serializer' => [
                'payload' => 'my_payload_serializer',
                'readmodel' => 'my_read_model_serializer',
                'metadata' => 'my_metadata_serializer',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('broadway.serializer.payload.service_id', 'my_payload_serializer');
        $this->assertContainerBuilderHasParameter('broadway.serializer.readmodel.service_id', 'my_read_model_serializer');
        $this->assertContainerBuilderHasParameter('broadway.serializer.metadata.service_id', 'my_metadata_serializer');
    }
}
