<?php

namespace LeoHt\AjaxSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class EngineSeekerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $engines = $container->getParameter('leoht_ajaxsearch.engines');

        foreach($engines as $engine) {
            
            $parameterPrefix = 'leoht_ajaxsearch.engines.'. $engine;

            $seekerDefinition = new Definition('LeoHt\AjaxSearchBundle\Seeker\Seeker', array(
                $container->getParameter($parameterPrefix .'.entity'),
                $container->getParameter($parameterPrefix .'.properties'),
                $container->getParameter($parameterPrefix .'.results.display')
            ));

            $seekerDefinition->addTag('leoht_ajaxsearch.seeker');

            $container->setDefinition($parameterPrefix .'.seeker', $seekerDefinition);
        }
    }
}