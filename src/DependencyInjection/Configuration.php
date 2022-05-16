<?php

/*
 * The file is part of the WoWUltimate project 
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Author Thomas Beauchataud
 * From 11/03/2022
 */

namespace TBCD\FtpClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ftp_client');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('defaults')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('host')->end()
                            ->scalarNode('user')->end()
                            ->scalarNode('protocol')->end()
                            ->scalarNode('credentials')->end()
                            ->booleanNode('passive')->end()
                            ->booleanNode('keepAlive')->end()
                            ->integerNode('port')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('clients')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('host')->end()
                            ->scalarNode('user')->end()
                            ->scalarNode('protocol')->end()
                            ->variableNode('credentials')->end()
                            ->booleanNode('passive')->end()
                            ->booleanNode('keepAlive')->end()
                            ->integerNode('port')->end()
                            ->scalarNode('default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}