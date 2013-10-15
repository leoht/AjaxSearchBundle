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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class EngineSeekerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasParameter('leoht_ajaxsearch.engines')) {
            throw new InvalidConfigurationException(sprintf("LeoHtAjaxSearchBundle: No search engine was found in your configuration. At least one must be provided."));
        }

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