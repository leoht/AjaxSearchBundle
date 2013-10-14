<?php

namespace LeoHt\AjaxSearchBundle\Service;

use LeoHt\AjaxSearchBundle\Adapter\AdapterInterface;

/**
* Seeker is used to retrieve results for a given search input.
*
* @author Leo Hetsch
*/
class Seeker
{

    /**
    * @var string
    */
    private $entityName;

    /**
    * @var array
    */
    private $searchProperties = array();

    /**
    * @var AdapterInterface
    */
    private $adapter;

    /**
    * Constructor.
    *
    * @param array $searchProperties
    */
    public function __construct($entityName, array $searchProperties)
    {
        $this->entityName = $entityName;
        $this->searchProperties = $searchProperties;
    }

    /**
    * Get results for a search string.
    * 
    * @param string $search
    * @return array the search results.
    */
    public function getResultsFor($search)
    {
        return $this->getAdapter()->searchInEntityProperties(
            $search,
            $this->getEntityName(),
            $this->getSearchProperties()
        );
    }

    /**
     * Gets the value of searchProperties.
     *
     * @return array
     */
    public function getSearchProperties()
    {
        return $this->searchProperties;
    }

    /**
     * Sets the value of searchProperties.
     *
     * @param array $searchProperties the search properties
     *
     * @return self
     */
    public function setSearchProperties(array $searchProperties)
    {
        $this->searchProperties = $searchProperties;

        return $this;
    }

    /**
     * Gets the adapter.
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the adapter.
     *
     * @param AdapterInterface $adapter the adapter
     *
     * @return self
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Gets the entity name.
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Sets the entity name.
     *
     * @param string $entityName the entity name
     *
     * @return self
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }
}