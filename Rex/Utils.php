<?php

    function arrayValue($array,$key){
        return isset($array[$key])? $array[$key] : null;
    }
    
    function parseINI($file,$section="") {        
        $ini_array = parse_ini_file($file, true);
        return $section ? arrayValue($ini_array, $section) : $ini_array;
    }    

