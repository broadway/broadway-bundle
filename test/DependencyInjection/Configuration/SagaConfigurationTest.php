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

class SagaConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function it_allows_the_saga_state_repository_to_not_be_configured(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'saga' => [
                    'enabled' => false,
                ],
            ],
            'saga'
        );
    }

    /**
     * @test
     */
    public function it_allows_the_saga_state_repository_to_be_configured(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'saga' => [
                        'state_repository' => 'my_saga_state_repository',
                    ],
                ],
            ],
            [
                'saga' => [
                    'enabled' => true,
                    'state_repository' => 'my_saga_state_repository',
                ],
            ],
            'saga'
        );
    }
}
