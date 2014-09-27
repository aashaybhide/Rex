<?php

class RexView{
    protected $path;
    public $url;
    public $postScript;
    public $controller;
    public $result; // Added paramater to toggle display of form actions (Just for the future)

    public function __construct(){
        $this->path = Config::getPath();
        $this->result = false;
    }
    
    public function getCss($filename) {
        return Config::getUrl($this->path['css']).$filename.".css";
    }
    
    public function getJs($filename) {
        return Config::getUrl($this->path['js']).$filename.".js";
    }

    public function renderSupport($file){
        include($this->path["template"].$file.".php");
    }

    public function renderScript($file){
        include($this->path["scripts"].$file.".php");
    }
    
    // Call Default Template
    public function render($file){
        $this->renderSupport("sidebar");
        $this->renderSupport("header");
        $this->renderScript($file);
        $this->renderSupport("footer");
    }

    // Call Message Template
    public function displayMessage($message,$link = ""){
        $this->link = Config::getUrl($link);
        $this->message = $message;
        $this->render("message");
        exit();
    }
    // Call Message Template
    public function alertMessage($message,$link = ""){
        $this->link = Config::getUrl($link);
        $this->message = $message;
        $this->render("message_alert");
        exit();
    }
    
    public function redirect($url){
        echo $redirect = Config::getUrl($url);
        header("Location: $redirect");
        die();
    }
    
    public function notifyMsgs(){
        if(isset($this->errorMsg)) 
            echo "<p class = 'error_msg'>$this->errorMsg</p>"; 
        if(isset($this->successMsg)) 
            echo "<p class = 'success_msg'>$this->successMsg</p>";
    }
}
?>