<?php

namespace LeoHt\AjaxSearchBundle\Seeker;

/**
* Seeker is used to retrieve results for a given search input.
*
* @author Leo Hetsch
*/
interface SeekerInterface
{
    /**
     * Get results for a search string.
     * 
     * @param string $search
     * @return array the search results.
     */
    public function getResultsFor($search);
}