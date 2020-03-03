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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class SerializerConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_configures_default_serializers()
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'serializer' => [
                    'payload' => 'broadway.simple_interface_serializer',
                    'readmodel' => 'broadway.simple_interface_serializer',
                    'metadata' => 'broadway.simple_interface_serializer',
                ],
            ],
            'serializer'
        );
    }

    /**
     * @test
     */
    public function it_configures_custom_serializers()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'serializer' => [
                        'payload' => 'my_payload_serializer',
                        'readmodel' => 'my_read_model_serializer',
                        'metadata' => 'my_metadata_serializer',
                    ],
                ],
            ],
            [
                'serializer' => [
                    'payload' => 'my_payload_serializer',
                    'readmodel' => 'my_read_model_serializer',
                    'metadata' => 'my_metadata_serializer',
                ],
            ],
            'serializer'
        );
    }
}
