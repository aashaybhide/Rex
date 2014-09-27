<?php

class Bootstrap{
    
    public function __construct($param){
        $this->callPage(explode("/", $param));
    }

    public function callPage($arg){
        $action = (isset($arg[1]) && $arg[1]=="ajax") ? "ajaxAction" : "indexAction";
        
        $temp = explode("-", $arg[0]);
        $ctrlString = "";
        foreach ($temp as $t)
            $ctrlString .= ucwords ($t);

        $this->triggerPageController($ctrlString,$action);		
    }

    public function triggerPageController($ctrl,$action){
        AutoLoader::loadController($ctrl);
        $c = new $ctrl();
        $c->$action();
    }
}
?>