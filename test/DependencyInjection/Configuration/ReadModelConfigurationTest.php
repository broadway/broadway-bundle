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
    public function it_allows_the_read_model_repository_factory_to_not_be_configured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                []
            ],
            [],
            'read_model'
        );
    }

    /**
     * @test
     */
    public function it_allows_the_read_model_repository_factory_to_be_configured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'read_model' => 'my_read_model_repository_factory',
                ],
            ],
            [
                'read_model' => 'my_read_model_repository_factory',
            ],
            'read_model'
        );
    }
}
