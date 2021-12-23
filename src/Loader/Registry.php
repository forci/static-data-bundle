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

class Registry {

    /**
     * @var [$bundle => StaticDataLoaderInterface[]]
     */
    private $loaders = [];

    public function addLoader(StaticDataLoaderInterface $loader, ?string $bundle) {
        if (!$bundle) {
            $bundle = '_';
        }

        $this->loaders[$bundle][] = $loader;
    }

    /**
     * @return StaticDataLoaderInterface[]
     */
    public function all(): array {
        $all = [];

        /**
         * @var string
         * @var StaticDataLoaderInterface[] $loaders
         */
        foreach ($this->loaders as $loaders) {
            /** @var StaticDataLoaderInterface $loader */
            foreach ($loaders as $loader) {
                $all[] = $loader;
            }
        }

        return $all;
    }

    /**
     * @return StaticDataLoaderInterface[]
     *
     * @throws UnsupportedBundleException
     */
    public function forBundle(string $bundle) {
        if (!isset($this->loaders[$bundle])) {
            throw new UnsupportedBundleException();
        }

        return $this->loaders[$bundle];
    }
}
