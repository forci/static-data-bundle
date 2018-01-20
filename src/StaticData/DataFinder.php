<?php

/*
 * This file is part of the ForciStaticDataBundle package.
 *
 * Copyright (c) Forci Web Consulting Ltd.
 *
 * Author: Martin Kirilov <martin@forci.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Forci\Bundle\StaticDataBundle\StaticData;

use Doctrine\ORM\EntityManager;
use Forci\Bundle\StaticDataBundle\StaticData\Exception\UnsupportedBundleException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DataFinder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    /** @var KernelInterface */
    protected $kernel;

    /** @var array[] */
    protected $config;

    /** @var string[] */
    protected $bundles;

    public function __construct(KernelInterface $kernel, ContainerInterface $container, array $config) {
        $this->kernel = $kernel;
        $this->container = $container;

        /** @var array $bundle */
        foreach ($config as $bundle) {
            $this->config[$bundle['bundle']] = $bundle;
            $this->bundles[] = $bundle['bundle'];
        }
    }

    public function findAll(): DataCollection {
        $collection = new DataCollection();

        foreach ($this->bundles as $bundle) {
            $collection[$bundle] = $this->findForBundle($bundle);
        }

        return $collection;
    }

    /**
     * @param string $bundle
     *
     * @return DataCollection
     *
     * @throws UnsupportedBundleException
     */
    public function findForBundle(string $bundle): DataCollection {
        if (!$this->supportsBundle($bundle)) {
            throw new UnsupportedBundleException(sprintf('Bundle "%s" is not supported. Please add "%s" to your bundle configuration.', $bundle, $bundle));
        }

        $config = $this->config[$bundle];

        $bundle = $this->kernel->getBundle($config['bundle']);

        $emId = sprintf('doctrine.orm.%s_entity_manager', $config['em']);
        /** @var EntityManager $em */
        $em = $this->container->get($emId);

        return $this->findDataForBundle($bundle, $config['directory'], $em);
    }

    public function supportsBundle(string $bundle): bool {
        return isset($this->config[$bundle]);
    }

    protected function findDataForBundle(BundleInterface $bundle, string $dirName, EntityManager $em): DataCollection {
        $collection = new DataCollection();

        if (!is_dir($dir = $bundle->getPath().DIRECTORY_SEPARATOR.$dirName)) {
            return $collection;
        }

        if (!class_exists('Symfony\Component\Finder\Finder')) {
            throw new \RuntimeException('You need the symfony/finder component to find StaticData classes.');
        }

        $finder = new Finder();
        $finder->files()->name('*Data.php')->in($dir);

        $prefix = $bundle->getNamespace().'\\'.str_replace('/', '\\', $dirName);
        foreach ($finder as $file) {
            $ns = $prefix;

            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.str_replace('/', '\\', $relativePath);
            }

            $class = $ns.'\\'.$file->getBasename('.php');

            $r = new \ReflectionClass($class);

            if ($this->container->has($class)) {
                $collection[] = $this->container->get($class);
            } elseif ($r->isSubclassOf(StaticData::class) && !$r->isAbstract()) {
                $collection[] = $r->newInstance($em, $this->container);
            }
        }

        return $collection;
    }
}
