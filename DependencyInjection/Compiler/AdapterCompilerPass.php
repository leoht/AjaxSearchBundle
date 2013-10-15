<?php

namespace LeoHt\AjaxSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
* Adapter compiler pass.
*/
class AdapterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $orm = $container->getParameter('leoht_ajaxsearch.orm');

        $adapterServiceId = 'leoht_ajaxsearch.db.'. $orm . '_adapter';

        $container->setAlias('leoht_ajaxsearch.db.adapter', $adapterServiceId);

        $seekers = $container->findTaggedServiceIds('leoht_ajaxsearch.seeker');

        foreach($seekers as $id => $attributes) {
            $seekerDefinition = $container->getDefinition($id);
            $seekerDefinition->addMethodCall(
                'setAdapter',
                array(new Reference($adapterServiceId))
            );
        }
    }
}