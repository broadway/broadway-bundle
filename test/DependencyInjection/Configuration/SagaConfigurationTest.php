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

class SagaConfigurationTest extends TestCase
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
    public function it_does_not_set_defaults()
    {
        $this->assertProcessedConfigurationEquals([], [], 'saga');
    }

    /**
     * @test
     */
    public function it_sets_in_memory_as_default_state_repository()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'saga' => [],
                ],
            ],
            [
                'saga' => [
                    'repository' => 'in_memory',
                    'mongodb'    => [
                        'storage_suffix' => null,
                        'connection'     => [
                            'dsn'      => null,
                            'database' => null,
                            'options'  => [],
                        ],
                    ],
                ],
            ],
            'saga'
        );
    }

    /**
     * @test
     */
    public function only_in_memory_and_mongodb_are_valid_state_repositories()
    {
        $this->assertConfigurationIsInvalid(
            [
                'broadway' => [
                    'saga' => [
                        'repository' => 'false_name',
                    ],
                ],
            ],
            'The value "false_name" is not allowed for path "broadway.saga.repository". Permissible values: "in_memory", "mongodb"'
        );
    }

    /**
     * @test
     */
    public function it_processes_saga_configuration_with_mongodb_repository()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'saga' => [
                        'repository' => 'mongodb',
                        'mongodb'    => [
                            'connection'     => [
                                'dsn'      => 'mongodb://12.34.45.6:27018/awesome',
                                'database' => 'my_database',
                                'options'  => [
                                    'connectTimeoutMS' => 50,
                                ],
                            ],
                            'storage_suffix' => 'my_suffix',
                        ],
                    ],
                ],
            ],
            [
                'saga' => [
                    'repository' => 'mongodb',
                    'mongodb'    => [
                        'connection'     => [
                            'dsn'      => 'mongodb://12.34.45.6:27018/awesome',
                            'database' => 'my_database',
                            'options'  => [
                                'connectTimeoutMS' => 50,
                            ],
                        ],
                        'storage_suffix' => 'my_suffix',
                    ],
                ],
            ],
            'saga'
        );
    }

    /**
     * @test
     */
    public function it_processes_saga_configuration_with_in_memory_repository()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'broadway' => [
                    'saga' => [
                        'repository' => 'in_memory',
                    ],
                ],
            ],
            [
                'saga' => [
                    'repository' => 'in_memory',
                    'mongodb'    => [
                        'storage_suffix' => null, // @todo remove this as it is not related to in memory repository
                        'connection'     => [
                            'dsn'      => null,
                            'database' => null,
                            'options'  => [],
                        ],
                    ],
                ],
            ],
            'saga'
        );
    }
}
