<?php

namespace LeoHt\AjaxSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
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
            ->treatNullLike(array('enabled' => false))
            ->treatFalseLike(array('enabled' => false))
            ->children()
                ->booleanNode('enabled')
                    ->defaultValue(true)
                ->end()
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
                ->arrayNode('search_in')
                    ->children()
                        ->scalarNode('entity')
                        ->end()
                        ->scalarNode('table')
                        ->end()
                        ->arrayNode('properties')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        $this->addResultsNode($rootNode);

        return $treeBuilder;
    }


    private function addResultsNode(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('results')
                ->addDefaultsIfNotSet()
                ->treatNullLike(array())
                ->treatFalseLike(array())
                    ->children()
                        ->arrayNode('display')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                        ->scalarNode('pattern')
                            ->defaultValue(null)
                        ->end()
                        ->arrayNode('provide_link')
                            ->children()
                                ->scalarNode('route')
                                ->end()
                                ->arrayNode('parameters')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('value')
                                            ->end()
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
