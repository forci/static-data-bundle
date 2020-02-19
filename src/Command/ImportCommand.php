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

namespace Forci\Bundle\StaticData\Command;

use Forci\Bundle\StaticData\Loader\DataLoader;
use Forci\Bundle\StaticData\Loader\Exception\UnsupportedBundleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command {

    /** @var DataLoader */
    private $loader;

    public function __construct(DataLoader $loader) {
        parent::__construct('forci_static_data:load');
        $this->loader = $loader;
    }

    protected function configure() {
        $this
            ->addOption('bundle', 'b', InputOption::VALUE_OPTIONAL, 'Bundle to load for', null)
            ->setDescription('Import Static Data for Bundle(s)');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $bundle = $input->getOption('bundle');

        if ($bundle) {
            $output->writeln(sprintf('Loading StaticData for bundle "%s"', $bundle));

            try {
                $this->loader->loadForBundle($bundle);
            } catch (UnsupportedBundleException $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

                return 1;
            }

            return 0;
        }

        $output->writeln('Loading StaticData for all configured bundles');

        $this->loader->load();

        return 0;
    }
}
