<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class tools {
    function reconstructArray($array)
    {
        $i = 0;
        $newArray[0] = '';
        foreach($array as $row)
        {
            $newArray[$i] = $row;
            $i++;
        }
        return $newArray;
    }
}