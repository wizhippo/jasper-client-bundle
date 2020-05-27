<?php

namespace Wizhippo\Bundle\JasperClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wizhippo_jasper_client');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('username')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('organization')->defaultValue('')->end()
            ->end();

        return $treeBuilder;
    }
}
