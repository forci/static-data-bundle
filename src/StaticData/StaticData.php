<?php declare(strict_types=1);

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

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class StaticData implements StaticDataLoaderInterface {

    protected ContainerInterface $container;

    /** @var EntityManager */
    protected $em;

    final public function load(): void
    {
        $this->doLoad();
        $this->flush();
    }

    abstract public function doLoad();

    public function find(string $class, int $id) {
        return $this->em->find($class, $id);
    }

    public function persist($entity): void
    {
        $this->em->persist($entity);
    }

    public function flush(/* $entity = null */): void
    {
        $this->em->flush(/* $entity */);
    }

    public function setEm(EntityManager $em): void {
        $this->em = $em;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
