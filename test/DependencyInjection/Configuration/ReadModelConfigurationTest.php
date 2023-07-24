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

namespace Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;

use Broadway\Bundle\BroadwayBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class ReadModelConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_allows_the_read_model_repository_factory_to_not_be_configured(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [],
            'read_model'
        );
    }

    /**
     * @test
     */
    public function it_allows_the_read_model_repository_factory_to_be_configured(): void
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
