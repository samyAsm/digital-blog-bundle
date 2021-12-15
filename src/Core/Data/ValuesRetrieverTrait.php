<?php
/**
 * Date: 20/08/21
 * Time: 10:16
 */

namespace Dhi\BlogBundle\Core\Data;


trait ValuesRetrieverTrait
{
    /**
     * @return \DateTime
     * @throws \Exception
     */
    protected function getDate(){
        return new \DateTime();
    }

    protected function getBool($bool = null){

        if (is_null($bool))
            return false;

        $bool.= "";

        return $bool == 1
            || $bool == "1"
            || $bool == "on"
            || $bool == "true";

    }

    protected function getFloat($value){
        return floatval($value);
    }


    protected function getInt($value){
        return intval($value);
    }

    protected function stripString($text){
        return preg_replace('/\s+/', ' ', $text);
    }

    protected function getString($value){
        return $value."";
    }
}