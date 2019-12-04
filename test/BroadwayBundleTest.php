<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Bundle\BroadwayBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class BroadwayBundleTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_does_not_throw_when_booting_kernel()
    {
        static::bootKernel();
    }

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = [])
    {
        return new AppKernel('test', true);
    }
}
