<?php
AutoLoader::loadRex();
new Bootstrap($_REQUEST['param']);

class AutoLoader {
    public static $rexConfig = "./Rex/config.ini";
    public static $rexPath = "./Rex/";
    
    private static function parseINI($file,$section){        
        $ini_array = parse_ini_file($file, true);
        return isset($ini_array[$section]) ? $ini_array[$section] : null;
    }
    
    public static function loadRex(){
        $rexFiles = static::parseINI(static::$rexConfig,"rex");
        foreach ($rexFiles as $file) {
            include(static::$rexPath.$file.".php");
        }
    }
    
    public static function loadModels($Models){
        $path = static::parseINI(static::$rexConfig,"path");        
        foreach ($Models as $model) 
            include($path['models'].$model.".php");
    }
    
    public static function loadController($controller){
        $path = static::parseINI(static::$rexConfig,"path");        
        include($path['controllers'].$controller.".php");  
    }
}

?>