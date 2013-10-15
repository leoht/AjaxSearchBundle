<?php
/*
 * This file is part of the LeoHtAjaxSearchBundle package.
 *
 * (c) 2013 Léonard Hetsch <leo.hetsch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LeoHt\AjaxSearchBundle\Seeker;

/**
 * Seeker is used to retrieve results for a given search input.
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