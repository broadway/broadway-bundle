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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new BroadwayBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }

    /**
     * {@inheritdoc}
     *
     * added for Symfony 3.4, see https://github.com/symfony/http-kernel/blob/v4.4.5/Kernel.php#L411-414
     */
    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }
}
