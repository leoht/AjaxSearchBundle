<?php

namespace LeoHt\AjaxSearchBundle\Seeker;

use LeoHt\AjaxSearchBundle\Adapter\AdapterInterface;

/**
 * {@inheritDoc}
 */
class Seeker implements SeekerInterface
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
     * @var array
     */
    private $selectProperties = array();

    /**
    * @var AdapterInterface
    */
    private $adapter;

    /**
    * Constructor.
    *
    * @param array $searchProperties
    */
    public function __construct($entityName, array $searchProperties, array $selectProperties)
    {
        $this->entityName = $entityName;
        $this->searchProperties = $searchProperties;
        $this->selectProperties = $selectProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function getResultsFor($search)
    {
        return $this->getAdapter()->searchInEntityProperties(
            $search,
            $this->getEntityName(),
            $this->getSearchProperties(),
            $this->getSelectProperties()
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

    /**
     * Gets selectProperties.
     *
     * @return array
     */
    public function getSelectProperties()
    {
        return $this->selectProperties;
    }

    /**
     * Sets selectProperties.
     *
     * @param array $selectProperties the select properties
     *
     * @return self
     */
    public function setSelectProperties(array $selectProperties)
    {
        $this->selectProperties = $selectProperties;

        return $this;
    }
}