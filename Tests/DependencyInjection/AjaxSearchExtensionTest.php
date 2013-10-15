<?php

namespace LeoHt\AjaxSearchBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use LeoHt\AjaxSearchBundle\DependencyInjection\AjaxSearchExtension;

class AjaxSearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    
    private $configuration;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionIsThrownUnlessOneEngineSet()
    {
        $extension = new AjaxSearchExtension();
        $config = static::getBaseConfig();
        unset($config['engines']['main']);
        $extension->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionIsThrownIfDoctrineButNoEntity()
    {
        $extension = new AjaxSearchExtension();
        $config = static::getBaseConfig();
        unset($config['engines']['main']['search_in']['entity']);
        $extension->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionIsThrownIfPropelButNoTable()
    {
        $extension = new AjaxSearchExtension();
        $config = static::getBaseConfig();
        $config['orm'] = 'propel';
        unset($config['engines']['main']['search_in']['table']);
        $extension->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionIsThrownIfProvideLinkWithoutRoute()
    {
        $extension = new AjaxSearchExtension();
        $config = static::getBaseConfig();
        unset($config['engines']['main']['results']['provide_link']['route']);
        $extension->load(array($config), new ContainerBuilder());
    }


    public static function getBaseConfig()
    {
        $yaml = <<<EOF
leoht_ajaxsearch:
    orm: doctrine
    engines:
        main:
            search_in:
                entity: AcmeFooBundle:Post
                table: post
                properties: [ title, content ]
            results:
                provide_link:
                    route: show_post
                    parameters: { id: id }
EOF;

        $parser = new Parser();

        return $parser->parse($yaml);
    }

}