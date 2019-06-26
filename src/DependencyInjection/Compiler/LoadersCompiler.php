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

namespace Forci\Bundle\StaticData\DependencyInjection\Compiler;

use Forci\Bundle\StaticData\StaticData\StaticData;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoadersCompiler implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        $definition = $container->findDefinition('forci_static_data.registry');

        $taggedServices = $container->findTaggedServiceIds('forci_static_data.loader');

        foreach ($taggedServices as $id => $tags) {
            $loaderDefinition = $container->findDefinition($id);

            if (is_a($loaderDefinition->getClass(), StaticData::class, true)) {
                $em = 'doctrine.orm.entity_manager';

                if (isset($tags[0]['em'])) {
                    $em = sprintf('doctrine.orm.%s_entity_manager', $tags[0]['em']);
                }

                $loaderDefinition->setArguments([
                    new Reference($em),
                    new Reference('service_container')
                ]);
            }

            $definition->addMethodCall(
                'addLoader',
                [new Reference($id), $tags[0]['bundle'] ?? null]
            );
        }
    }
}
