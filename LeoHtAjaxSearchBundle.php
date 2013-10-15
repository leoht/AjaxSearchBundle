<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use LeoHt\AjaxSearchBundle\DependencyInjection\AjaxSearchExtension;
use LeoHt\AjaxSearchBundle\DependencyInjection\Compiler\AdapterCompilerPass;
use LeoHt\AjaxSearchBundle\DependencyInjection\Compiler\EngineSeekerCompilerPass;

/**
 * LeoHtAjaxSearchBundle
 *
 * @author Léonard Hetsch <leo.hetsch@gmail.com>
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
