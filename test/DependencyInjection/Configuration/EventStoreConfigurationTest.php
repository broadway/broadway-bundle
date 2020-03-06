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

class EventStoreConfigurationTest extends TestCase
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
    public function it_allows_the_event_store_to_not_be_configured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [],
            'event_store'
        );
    }

    /**
     * @test
     */
    public function it_allows_the_event_store_to_be_configured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'event_store' => 'my_event_store',
                ],
            ],
            [
                'event_store' => 'my_event_store',
            ],
            'event_store'
        );
    }
}
