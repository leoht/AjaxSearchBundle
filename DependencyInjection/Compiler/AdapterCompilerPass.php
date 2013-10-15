<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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