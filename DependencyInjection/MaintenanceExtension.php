<?php

declare(strict_types=1);

namespace Larisch\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class MaintenanceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('maintenance.enabled', $config['enabled']);
        $container->setParameter('maintenance.cookie_bypass_token', $config['cookie_bypass_token']);
        $container->setParameter('maintenance.ip_addresses', $config['ip_addresses']);
        $container->setParameter('maintenance.excluded_paths', $config['excluded_paths']);
        $container->setParameter('maintenance.template_path', $config['template_path']);
        $container->setParameter('maintenance.get_bypass_name', $config['get_bypass_name']);
        $container->setParameter('maintenance.get_bypass_value', $config['get_bypass_value']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
