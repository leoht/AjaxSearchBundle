<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\Adapter;

use Doctrine\ORM\Query\QueryException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/*
* Doctrine ORM adapter.
*/
class DoctrineAdapter implements AdapterInterface
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;


    /**
     * Constructor.
     * 
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        if (false === array_key_exists('entity_manager', $parameters)) {
            throw new \LogicException(sprintf("The Doctrine EntityManager must be injected into the DoctrineAdapter service. Check services.yml to fix this problem."));
        }

        $this->entityManager = $parameters['entity_manager'];
    }

    /**
     * {@inheritDoc}
     */
    public function searchInEntityProperties($search, $entityName, array $properties, array $selectedProperties)
    {
        $attributes = "";

        foreach($selectedProperties as $key => $property) {
            $attributes .= " e.$property";

            if ($key < count($selectedProperties)-1) {
                $attributes .= ", ";
            }
        }

        $dql = "SELECT $attributes FROM $entityName e WHERE ";

        $queryParameters = array();

        foreach ($properties as $key => $property) {
            $dql .= " e.$property LIKE :$property ";

            if ($key < count($properties)-1) {
                $dql .= " OR ";
            }

            $queryParameters[$property] = '%'.$search.'%';
        }

        $query = $this->entityManager->createQuery($dql);

        foreach($queryParameters as $param => $value) {
            $query->setParameter($param, $value);
        }

        try {
            $results = $query->getResult();
        } catch(QueryException $e) {
            throw new InvalidConfigurationException(sprintf("Search query has failed. Maybe you have misconfigured the 'properties' configuration path and set properties that do not exist in the seached entity."), 0, $e);
        }

        return $results;
    }
}