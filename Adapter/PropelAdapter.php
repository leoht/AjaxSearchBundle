<?php

namespace LeoHt\AjaxSearchBundle\Adapter;

use \Propel;

/**
* Propel ORM adapter.
*/
class PropelAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $selectedProperties = array();

    /**
     * Constructor.
     * 
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->selectedProperties = $parameters['results.display'];
    }

    /**
     * {@inheritDoc}
     */
    public function searchInEntityProperties($search, $entityName, array $properties)
    {
        $connection = Propel::getConnection();

        $attributes = "";

        foreach($this->selectedProperties as $key => $property) {
            $attributes .= " $property";

            if ($key < count($this->selectedProperties)-1) {
                $attributes .= ", ";
            }
        }

        $sql = "SELECT $attributes FROM $entityName WHERE ";

        $queryParameters = array();

        foreach ($properties as $key => $property) {
            $sql .= " $property LIKE :$property ";

            if ($key < count($properties)-1) {
                $sql .= " OR ";
            }

            $queryParameters[':'.$property] = '%'.$search.'%';
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute($queryParameters);

        $results  = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}