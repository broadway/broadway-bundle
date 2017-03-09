<?php

namespace Broadway\Bundle\BroadwayBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class BroadwayBundleTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_the_bundle()
    {
        $container = new ContainerBuilder();

        $bundle = new BroadwayBundle();
        $bundle->build($container);

        $container->compile();
    }
}
