<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Search controller.
 */
class SearchController extends ContainerAware
{
    /**
     * @Route("/", name="leoht_ajaxsearch_search")
     * @Method({"GET"})
     */
    public function searchAction(Request $request)
    {
        return Response::create(
            $this->get('templating')->render('LeoHtAjaxSearchBundle:Search:search.html.twig')
        );
    }

    /**
    * @Route("/{search}", name="leoht_ajaxsearch_result", requirements={"search": ".*"})
    * @Method({"GET"})
    */
    public function resultAction(Request $request)
    {
        $engine = $request->query->has('_engine') ? $request->get('_engine') : 'main';

        if (false === $request->isXmlHttpRequest() && 'dev' !== $this->get('kernel')->getEnvironment()) {
            return new Response('Not allowed from direct access.', 403);
        }

        $search = $request->get('search');

        $orm = $this->container->getParameter('leoht_ajaxsearch.orm');

        $seeker = $this->get('leoht_ajaxsearch.engines.'. $engine .'.seeker');

        $results = $seeker->getResultsFor($search);

        if (false !== $config = $this->container->getParameter('leoht_ajaxsearch.engines.'. $engine .'.results.provide_link')) {
            $results = $this->provideLinksToResults($results, $config);
        }

        return new JsonResponse($results);
    }

    /**
     * Gets a service from the service container.
     * @param string $id
     */
    private function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Adds a link attribute to each result using given configuration to generate URL.
     *
     * @param array $results
     * @param array $config
     * @return array
     */
    private function provideLinksToResults(array $results, array $config)
    {
        $route = $config['route'];
        $parameters = $config['parameters'];

        foreach($results as $key => $result) {

            $routeParams = array();

            foreach($parameters as $name => $value) {
                $routeParams[$name] = $result[$value];
            }

            try {
                $result['_link'] = $this->get('router')->generate($route, $routeParams);
            } catch(MissingMandatoryParametersException $e) {
                throw new InvalidConfigurationException(sprintf("URL generation failed while processing search results (parameters given to generator were : %s). Check that you have fully provided all the mandatory parameters in the 'provide_link' section. ", json_encode($routeParams)), null, $e);
            }

            $results[$key] = $result;
        }

        return $results;
    } 

}
