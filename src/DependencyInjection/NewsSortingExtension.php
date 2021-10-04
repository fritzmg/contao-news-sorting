<?php

declare(strict_types=1);

/*
 * This file is part of the NewsSorting Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\NewsSortingBundle\DependencyInjection;

use InspiredMinds\NewsSortingBundle\EventListener\NewsCategoriesNewsSortingListener;
use InspiredMinds\NewsSortingBundle\EventListener\NewsSortingListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the Bundle extension.
 */
class NewsSortingExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \Exception if something went wrong
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($container->getParameter('kernel.bundles')['CodefogNewsCategoriesBundle'])) {
            $container->removeDefinition(NewsSortingListener::class);
        } else {
            $container->removeDefinition(NewsCategoriesNewsSortingListener::class);
        }
    }
}
