<?php

/*
 * This file is part of the ForciStaticDataBundle package.
 *
 * Copyright (c) Forci Web Consulting Ltd.
 *
 * @author Martin Kirilov <martin@forci.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Forci\Bundle\StaticDataBundle\StaticData;

use Forci\Bundle\StaticDataBundle\StaticData\Exception\UnsupportedBundleException;

class DataLoader {

    /** @var DataFinder */
    protected $finder;

    public function __construct(DataFinder $finder) {
        $this->finder = $finder;
    }

    public function loadAll() {
        $this->finder->findAll()->load();
    }

    /**
     * @param string $bundle
     *
     * @throws UnsupportedBundleException
     */
    public function loadForBundle(string $bundle) {
        $this->finder->findForBundle($bundle)->load();
    }
}
