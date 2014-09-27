<?php

class Config {
    public static $config = "./config.ini";
    public static $rexConfig = "./Rex/config.ini";

    public static function getSection($section){        
        return parseINI(static::$config,$section);
    }
    
    public static function getUrl($link){
        $path = static::getSection("path");
        return $path["site"].$link;
    }
    
    public static function getPath($param=null){      
        $path = parseINI(static::$rexConfig,"path");
        return $param ? arrayValue($path, $param) : $path;
    }
    
    public static function getTitle(){
        $constants = static::getSection("constants");
        return $constants['title'];
    }  
}
