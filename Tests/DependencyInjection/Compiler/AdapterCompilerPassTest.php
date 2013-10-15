<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) Léonard Hetsch <http://github.com/leoht> 2013
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use Doctrine\ORM\EntityManager;
use LeoHt\AjaxSearchBundle\DependencyInjection\AjaxSearchExtension;
use LeoHt\AjaxSearchBundle\DependencyInjection\Compiler\AdapterCompilerPass;
use LeoHt\AjaxSearchBundle\Tests\DependencyInjection\AjaxSearchExtensionTest;

class AdapterCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testDoctrineAdapterIsUsedWithDoctrine()
    {
        $extension = new AjaxSearchExtension();
        $config = AjaxSearchExtensionTest::getBaseConfig();

        $config = $config['leoht_ajaxsearch'];

        $container = new ContainerBuilder();
        $extension->load(array($config), $container);

        $container->registerExtension($extension);
        
        $pass = new AdapterCompilerPass();
        $pass->process($container);

        $adapterDefinition = $container->findDefinition('leoht_ajaxsearch.db.adapter');

        $this->assertTrue(false !== strpos($adapterDefinition->getClass(), 'doctrine'));
    }

    public function testPropelAdapterIsUsedWithPropel()
    {
        $extension = new AjaxSearchExtension();
        $config = AjaxSearchExtensionTest::getBaseConfig();

        $config = $config['leoht_ajaxsearch'];

        $config['orm'] = 'propel';

        $container = new ContainerBuilder();
        $extension->load(array($config), $container);

        $container->registerExtension($extension);
        
        $pass = new AdapterCompilerPass();
        $pass->process($container);

        $adapterDefinition = $container->findDefinition('leoht_ajaxsearch.db.adapter');

        $this->assertTrue(false !== strpos($adapterDefinition->getClass(), 'propel'));
    }
}