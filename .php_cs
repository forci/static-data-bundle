<?php

use Wucdbm\PhpCsFixer\Config\ConfigFactory;

$copyright = <<<COMMENT
This file is part of the ForciStaticDataBundle package.

Copyright (c) Forci Web Consulting Ltd.

Author Martin Kirilov <martin@forci.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
COMMENT;

return ConfigFactory::createCopyrightedConfig([
    __DIR__ . '/src'
], $copyright)
    ->setUsingCache(false);