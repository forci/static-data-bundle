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

namespace Forci\Bundle\StaticData\StaticData;

class DataCollection extends \ArrayIterator implements StaticDataInterface {

    protected $loaded = false;

    public function load() {
        if ($this->loaded) {
            return;
        }

        /** @var StaticData $data */
        foreach ($this as $data) {
            $data->load();
        }

        $this->loaded = true;
    }
}
