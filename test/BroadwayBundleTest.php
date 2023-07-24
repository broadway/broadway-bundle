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

namespace Broadway\Bundle\BroadwayBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class BroadwayBundleTest extends WebTestCase
{
    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function it_does_not_throw_when_booting_kernel(): void
    {
        static::bootKernel();
    }

    protected static function createKernel(array $options = []): AppKernel
    {
        return new AppKernel('test', true);
    }
}
