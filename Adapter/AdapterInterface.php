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

/*
* ORM Adapter interface.
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
    public function searchInEntityProperties($search, $entityName, array $properties, array $selectedProperties);
}