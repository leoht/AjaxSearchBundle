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

use \Propel;

/*
* Propel ORM adapter.
*/
class PropelAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function searchInEntityProperties($search, $entityName, array $properties, array $selectedProperties)
    {
        $connection = Propel::getConnection();

        $attributes = "";

        foreach($selectedProperties as $key => $property) {
            $attributes .= " $property";

            if ($key < count($selectedProperties)-1) {
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