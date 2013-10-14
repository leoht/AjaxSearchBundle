<?php

namespace LeoHt\AjaxSearchBundle\Adapter;

/**
* ORM Adapter interface.
*
* @author Leo Hetsch
*/
interface AdapterInterface
{
    /**
    * Searches for the given input in properties of an entities set.
    *
    * @param string $search
    * @param string $entityName
    * @param array $properties
    * @return array The search results
    */
    public function searchInEntityProperties($search, $entityName, array $properties);
}