<?php

namespace LeoHt\AjaxSearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use LeoHt\AjaxSearchBundle\DependencyInjection\AjaxSearchExtension;
use LeoHt\AjaxSearchBundle\DependencyInjection\Compiler\AdapterCompilerPass;
use LeoHt\AjaxSearchBundle\DependencyInjection\Compiler\EngineSeekerCompilerPass;

/**
* LeoHtAjaxSearchBundle
*
* @author Leo Hetsch
*/
class LeoHtAjaxSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = new AjaxSearchExtension();

        $container->registerExtension($extension);

        $container->addCompilerPass(new EngineSeekerCompilerPass());
        $container->addCompilerPass(new AdapterCompilerPass());
        
    }
}
