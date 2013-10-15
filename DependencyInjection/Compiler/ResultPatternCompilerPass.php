<?php

namespace LeoHt\AjaxSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
* Result pattern compiler pass.
*/
class ResultPatternCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $engines = $container->getParameter('leoht_ajaxsearch.engines');

        foreach($engines as $engine) {
            $pattern = $container->getParameter('leoht_ajaxsearch.engines.'. $engine .'.results.pattern');

            if (null === $pattern) {
                $pattern = $this->buildDefaultPattern($engine, $container);
            }

            $container->setParameter('leoht_ajaxsearch.engines.'. $engine .'.results.pattern', $pattern);
        } 
    }

    /**
     * Builds a default pattern to display attributes of a search result item
     *
     * @param ContainerBuilder $container
     * @return string
     */
    private function buildDefaultPattern($engine, ContainerBuilder $container)
    {
        $pattern = '';

        $selectedProperties = $container->getParameter('leoht_ajaxsearch.engines.'. $engine .'.results.display');

        foreach($selectedProperties as $key => $property) {
            $pattern .= '{'.$property.'}';
            if ($key < count($selectedProperties)) {
                $pattern .= ' | ';
            }
        }

        return $pattern;
    }

    /**
     * Adds missing properties that needs to be selected by the search query.
     * 
     * @param string $pattern
     * @param ContainerBuilder $container
     */
    // private function registerPatternProperties($pattern, ContainerBuilder $container)
    // {
    //     $matchedProperties = array();
    //     $selectedProperties = $container->getParameter('leoht_ajaxsearch.results.display');
    //     $propertiesToRegister = array();

    //     if (preg_match('#\{(.+)\}#iUs', $pattern, $matchedProperties)) {

    //         var_dump($matchedProperties);
            
    //         foreach($matchedProperties as $property) {

    //             // Registers missing properties that needs to be
    //             // selected by the search query, if they have not been
    //             // provided in the configuration
    //             if (false === in_array($property, $selectedProperties)) {
    //                 $selectedProperties[] = $property;
    //             }

    //             $propertiesToRegister[] = $property;
    //         }

    //         $container->setParameter('leoht_ajaxsearch.results.display', $selectedProperties);
    //         $container->setParameter('leoht_ajaxsearch.results.pattern.properties', $propertiesToRegister);
    //     }
    // }
}