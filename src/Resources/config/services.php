<?php

declare(strict_types=1);

use Forci\Bundle\StaticData\Command\ImportCommand;
use Forci\Bundle\StaticData\Loader\DataLoader;
use Forci\Bundle\StaticData\Loader\Registry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('forci_static_data.registry', Registry::class);

    $services->set('forci_static_data.data_loader', DataLoader::class)
        ->args([
            service('forci_static_data.registry'),
        ]);

    $services->set(ImportCommand::class)
        ->args([
            service('forci_static_data.data_loader'),
        ])
        ->tag('console.command');
};
