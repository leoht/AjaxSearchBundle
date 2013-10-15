<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AjaxSearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('leoht_ajaxsearch.auto_complete', $config['auto_complete']);
        $container->setParameter('leoht_ajaxsearch.case_sensitive', $config['case_sensitive']);
        $container->setParameter('leoht_ajaxsearch.orm', $config['orm']);

        $engines = array();

        foreach($config['engines'] as $engine => $engineConfig) {
            $this->registerEngineConfiguration($engine, $engineConfig, $container);
            $engines[] = $engine;
        }

        $container->setParameter('leoht_ajaxsearch.engines', $engines);
    }


    private function registerEngineConfiguration($engine, array $engineConfig, ContainerBuilder $container)
    {
        $orm = $container->getParameter('leoht_ajaxsearch.orm');

        if ('doctrine' === $orm && true === array_key_exists('entity', $engineConfig['search_in'])) {
            $container->setParameter('leoht_ajaxsearch.engines.' . $engine . '.entity', $engineConfig['search_in']['entity']);
        } elseif ('propel' === $orm && true === array_key_exists('table', $engineConfig['search_in'])) {
            $container->setParameter('leoht_ajaxsearch.engines.' . $engine . '.entity', $engineConfig['search_in']['table']);
        } else {
            throw new InvalidConfigurationException(sprintf("LeoHtAjaxSearchBundle : You must provide an entity name (if you use Doctrine) or a table name (if you use Propel) in the configuration. None of these was found."));
        }
        
        $container->setParameter('leoht_ajaxsearch.engines.' . $engine . '.properties', $engineConfig['search_in']['properties']);

        $this->registerEngineResultConfiguration($engine, $engineConfig, $container);
    }

    /**
    * Process and register the results configuration section.
    *
    * @param array $config
    * @param ContainerBuilder $container
    */
    private function registerEngineResultConfiguration($engine, array $engineConfig, ContainerBuilder $container)
    {
        /*
        * If the attributes to display in search results are not provided in the configuration,
        * then the attributes to search in will be used by default.
        */
        if (false === array_key_exists('results', $engineConfig) || 0 == count($engineConfig['results']['display'])) {
            $engineConfig['results']['display'] = $engineConfig['search_in']['properties'];
        }

        $container->setParameter('leoht_ajaxsearch.engines.'. $engine .'.results.pattern', $engineConfig['results']['pattern']);

        if (true === array_key_exists('provide_link', $engineConfig['results'])) {

            /*
            * To provide a link for each search result, we need route parameters to generate it.
            * If none is provided in the configuration, the only default one will be an "id" parameter
            * with the value of the "id" attribute of the searched entity.
            *
            * According to this, the default configuration is:
            *     parameters:
            *         id: { value: id }
            */
            if (false === array_key_exists('parameters', $engineConfig['results']['provide_link'])) {
                $engineConfig['results']['provide_link']['parameters'] = array(
                    'id' => 'id',
                );
            }

            /*
            * If there are route parameters that will not be retrieved from the database,
            * it will cause the URL generation to crash. To avoid this problem we also retrieve
            * the attributes that are mandatory for URL generation
            */
            foreach($engineConfig['results']['provide_link']['parameters'] as $param => $value) {;

                if (false === in_array($value, $engineConfig['results']['display'])) {
                    $engineConfig['results']['display'][] = $value;
                }
            }

            $container->setParameter('leoht_ajaxsearch.engines.'. $engine .'.results.provide_link', $engineConfig['results']['provide_link']);
        } else {
            $container->setParameter('leoht_ajaxsearch.engines.'. $engine .'.results.provide_link', false);
        }

        $container->setParameter('leoht_ajaxsearch.engines.'. $engine .'.results.display', $engineConfig['results']['display']);

        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getAlias()
    {
        return 'leoht_ajaxsearch';
    }
}
