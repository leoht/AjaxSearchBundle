<?php

namespace LeoHt\AjaxSearchBundle\Service;

/**
* ResultSerializer
*/
class ResultSerializer
{
    public function serialize($results)
    {
        foreach($results as $key => $object) {

            if ( is_array( $object ))
            return $object ;
            
            if ( !is_object( $object ))
                return false ;
                
            $serial = serialize( $object ) ;
            $serial = preg_replace( '/O:\d+:".+?"/' ,'a' , $serial ) ;
            if( preg_match_all( '/s:\d+:"\\0.+?\\0(.+?)"/' , $serial, $ms, PREG_SET_ORDER )) {
                foreach( $ms as $m ) {
                    $serial = str_replace( $m[0], 's:'. strlen( $m[1] ) . ':"'.$m[1] . '"', $serial ) ;
                }
            }
            
            $results[$key] = unserialize( $serial );
        }

        return $results;
    }
}
