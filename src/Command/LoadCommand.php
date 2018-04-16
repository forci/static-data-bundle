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

use Forci\Bundle\StaticData\StaticData\DataLoader;
use Forci\Bundle\StaticData\StaticData\Exception\UnsupportedBundleException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadCommand extends Command {

    /** @var DataLoader */
    protected $loader;

    public function __construct(DataLoader $loader) {
        parent::__construct();
        $this->loader = $loader;
    }

    protected function configure() {
        $this
            ->setName('forci_static_data:load')
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
            }

            return;
        }

        $output->writeln('Loading StaticData for all configured bundles');

        $this->loader->loadAll();
    }
}
