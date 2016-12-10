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

class ReadModelConfigurationTest extends TestCase
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
    public function it_configures_elasticsearch_as_default_read_model_repository()
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'read_model' => [
                    'repository'    => 'elasticsearch',
                    'elasticsearch' => [
                        'hosts' => [
                            'localhost:9200',
                        ],
                    ],
                ],
            ],
            'read_model'
        );
    }

    /**
     * @test
     */
    public function only_in_memory_and_elasticsearch_are_valid_read_model_repositories()
    {
        $this->assertConfigurationIsInvalid(
            [
                'broadway' => [
                    'read_model' => [
                        'repository' => 'false_name',
                    ],
                ],
            ],
            'The value "false_name" is not allowed for path "broadway.read_model.repository". Permissible values: "in_memory", "elasticsearch"'
        );
    }
}
