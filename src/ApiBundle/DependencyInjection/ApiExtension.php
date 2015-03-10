<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 08:15
 */

namespace ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class ApiExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ .'/../Resources/config'));
        $loader->load('services.yml');
    }
}