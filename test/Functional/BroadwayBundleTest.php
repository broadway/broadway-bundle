<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle\Functional;

use Broadway\Auditing\NullByteCommandSerializer;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventStore\Dbal\DBALEventStore;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class BroadwayBundleTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function it_boots_the_application()
    {
    }

    /**
     * @test regression test
     */
    public function it_instantiates_the_auditing_serializer()
    {
        $this->assertInstanceOf(
            NullByteCommandSerializer::class,
            $this->client->getContainer()->get('broadway.auditing.serializer')
        );
    }

    /**
     * @test regression test
     */
    public function it_instantiates_the_event_dispatcher()
    {
        $this->assertInstanceOf(
            EventDispatcher::class,
            $this->client->getContainer()->get('broadway.event_dispatcher')
        );
    }

    /**
     * @test regression test
     */
    public function it_instantiates_the_dbal_event_store()
    {
        $this->assertInstanceOf(
            DBALEventStore::class,
            $this->client->getContainer()->get('broadway.event_store.dbal')
        );
    }
}
