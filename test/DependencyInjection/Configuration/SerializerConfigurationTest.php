<?php

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
use Broadway\Bundle\BroadwayBundle\TestCase;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;

class SerializerConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @inheritdoc
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
                    'payload'   => 'broadway.simple_interface_serializer',
                    'readmodel' => 'broadway.simple_interface_serializer',
                    'metadata'  => 'broadway.simple_interface_serializer',
                ]
            ],
            'serializer'
        );
    }
}
