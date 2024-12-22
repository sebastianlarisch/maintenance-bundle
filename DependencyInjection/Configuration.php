<?php

declare(strict_types=1);

namespace Larisch\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('maintenance');

        $treeBuilder->getRootNode()
            ->children()
            ->booleanNode('enabled')
                ->defaultFalse()
            ->end()
            ->scalarNode('bypass_token')
                ->defaultValue('default_secret_token')
            ->end()
            ->scalarNode("ip_addresses")
                ->defaultValue('')
            ->end()
            ->scalarNode("excluded_paths")
                ->defaultValue('')
            ->end()
            ->scalarNode("get_bypass_name")
                ->defaultValue('')
            ->end()
            ->scalarNode("get_bypass_value")
                ->defaultValue('')
            ->end()
            ->scalarNode('template_path')
                ->defaultValue('@Maintenance/maintenance/default.html.twig')
            ->end()
            ->end();

        return $treeBuilder;
    }
}
