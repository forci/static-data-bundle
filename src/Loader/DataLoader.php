<?php

/*
 * This file is part of the ForciStaticDataBundle package.
 *
 * Copyright (c) Forci Web Consulting Ltd.
 *
 * Author Martin Kirilov <martin@forci.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Forci\Bundle\StaticData\Loader;

use Forci\Bundle\StaticData\Loader\Exception\UnsupportedBundleException;
use Forci\Bundle\StaticData\StaticData\StaticDataLoaderInterface;

class DataLoader
{

    public function __construct(private Registry $registry)
    {
    }

    public function load(): void
    {
        $this->loadLoaders(...$this->registry->all());
    }

    /**
     * @param string $bundle
     *
     * @throws UnsupportedBundleException
     */
    public function loadForBundle(string $bundle): void
    {
        $this->loadLoaders(...$this->registry->forBundle($bundle));
    }

    private function loadLoaders(StaticDataLoaderInterface ...$loaders): void
    {
        foreach ($loaders as $loader) {
            $loader->load();
        }
    }
}
