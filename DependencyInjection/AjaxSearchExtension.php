<?php

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

        if (true === $config['enabled']) {
            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.xml');

            $container->setParameter('leoht_ajaxsearch.auto_complete', $config['auto_complete']);
            $container->setParameter('leoht_ajaxsearch.case_sensitive', $config['case_sensitive']);
            $container->setParameter('leoht_ajaxsearch.orm', $config['orm']);

            if ('doctrine' === $config['orm'] && true === array_key_exists('entity', $config['search_in'])) {
                $container->setParameter('leoht_ajaxsearch.entity', $config['search_in']['entity']);
            } elseif ('propel' === $config['orm'] && true === array_key_exists('table', $config['search_in'])) {
                $container->setParameter('leoht_ajaxsearch.entity', $config['search_in']['table']);
            } else {
                throw new InvalidConfigurationException(sprintf("LeoHtAjaxSearchBundle : You must provide an entity name (if you use Doctrine) or a table name (if you use Propel) in the configuration. None of these was found."));
            }
            
            $container->setParameter('leoht_ajaxsearch.properties', $config['search_in']['properties']);

            $this->registerResultConfiguration($config, $container);
        }
    }

    /**
    * Process and register the results configuration section.
    *
    * @param array $config
    * @param ContainerBuilder $container
    */
    private function registerResultConfiguration(array $config, ContainerBuilder $container)
    {
        /*
        * If the attributes to display in search results are not provided in the configuration,
        * then the attributes to search in will be used by default.
        */
        if (false === array_key_exists('results', $config) || 0 == count($config['results']['display'])) {
            $config['results']['display'] = $config['search_in']['properties'];
        }

        $container->setParameter('leoht_ajaxsearch.results.pattern', $config['results']['pattern']);

        if (true === array_key_exists('provide_link', $config['results'])) {

            /*
            * To provide a link for each search result, we need route parameters to generate it.
            * If none is provided in the configuration, the only default one will be an "id" parameter
            * with the value of the "id" attribute of the searched entity.
            *
            * According to this, the default configuration is:
            *     parameters:
            *         id: { value: id }
            */
            if (false === array_key_exists('parameters', $config['results']['provide_link'])) {
                $config['results']['provide_link']['parameters'] = array(
                    'id' => 'id',
                );
            }

            /*
            * If there are route parameters that will not be retrieved from the database,
            * it will cause the URL generation to crash. To avoid this problem we also retrieve
            * the attributes that are mandatory for URL generation
            */
            foreach($config['results']['provide_link']['parameters'] as $param => $values) {

                $entityAttribute = $values['value'];

                if (false === in_array($entityAttribute, $config['results']['display'])) {
                    $config['results']['display'][] = $entityAttribute;
                }
            }

            $container->setParameter('leoht_ajaxsearch.results.provide_link', $config['results']['provide_link']);
        } else {
            $container->setParameter('leoht_ajaxsearch.results.provide_link', false);
        }

        $container->setParameter('leoht_ajaxsearch.results.display', $config['results']['display']);

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
