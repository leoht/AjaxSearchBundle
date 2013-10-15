<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/*
 * LeohAjaxSearchBundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('leoht_ajaxsearch');

        $supportedOrm = array('doctrine', 'propel');

        $rootNode
            ->children()
                ->booleanNode('auto_complete')
                    ->defaultValue(true)
                ->end()
                ->booleanNode('case_sensitive')
                    ->defaultValue(true)
                ->end()
                ->enumNode('orm')
                    ->values($supportedOrm)
                    ->defaultValue('doctrine')
                ->end()
            ->end()
        ->end();

        $this->addEnginesNode($rootNode);

        return $treeBuilder;
    }


    private function addEnginesNode(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('engines')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->arrayNode('search_in')
                            ->children()
                                ->scalarNode('entity')->end()
                                ->scalarNode('table')->end()
                                ->arrayNode('properties')
                                    ->isRequired()
                                    ->requiresAtLeastOneElement()
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('results')
                        ->addDefaultsIfNotSet()
                        ->treatNullLike(array())
                        ->treatFalseLike(array())
                            ->children()
                                ->arrayNode('display')
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('pattern')->defaultValue(null)->end()
                                ->arrayNode('provide_link')
                                    ->children()
                                        ->scalarNode('route')->end()
                                        ->arrayNode('parameters')
                                        ->useAttributeAsKey('name')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
