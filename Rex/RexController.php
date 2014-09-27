<?php
abstract class RexController{
    public $view;
    private $_post;

    public function __construct(){
        session_start();
        $this->view = new RexView();
        $this->view->controller = $this->getRequest("controller");
        $this->view->url = $this->getRequest("controller")."/".$this->getRequest("page");
    }   
    
    public abstract function indexAction();

    public function ajaxAction() {
        $ajaxFunc = $this->getRequest("arg")."Ajax";
        $this->$ajaxFunc();
    }

    public function defaultIndex() {
        if($this->getPost())
            $this->callPageAction("post");     
        
        $diffPage = $this->callPageAction();
        if(!$diffPage)
            $this->callPageView();        
    }
    
    public function testPrint($expression) {
        echo "<pre>";
        print_r($expression);
        echo "</pre>";
    }

    protected function loadModels($Models){
        AutoLoader::loadModels($Models);         
    }

    protected function getAction($actionWord) {
        $t = explode("-", $actionWord);
        $action = $t[0];
        for ($i = 1; $i < sizeof($t); $i++)
            $action .=ucwords($t[$i]);
        $action .= "Action";
        return $action;
    }
    
    protected function callPageView($surfix="") {
        $this->view->render($this->view->url.$surfix); 
    }

    protected function callPageAction($ext="") {
        $action = $this->getAction($this->getPage()."-".$ext);
        if($action && method_exists($this,$action))
            return $this->$action();
    }

    protected function getPost($param="") {
        if(!$this->_post)
            $this->_post = filter_input_array(INPUT_POST);
        
        return $param ? $this->_post[$param] : $this->_post;
    }   

    protected function getPage(){
        return $this->getRequest("page");
    }

    protected function getRequest($param="") {
        $request = filter_input_array(INPUT_GET);
//        print_r($request);
        $input = explode("/", $request["param"]);
        $req["controller"] = $input[0];
        $req["page"] = isset($input[1]) ? $input[1] : null;
        $req["arg"] = isset($input[2]) ? $input[2] : null;
        return $param ? (isset($req[$param]) ? $req[$param] : null) : $request["param"];
    }   

    protected function getSession($param) {
//        $input = filter_input_array(INPUT_SESSION);
        $input = $_SESSION;
        return $param ? (isset($input[$param]) ? $input[$param] : null) : $input;
    }
    
    protected function getFile($name) {
        return isset($_FILES[$name]) ? $_FILES[$name] : null;
    }
    
    protected function verifyLogin(){
        if(!$this->getSession('user_id')){
            $this->view->displayMessage("You need to log in","account/login");
            return;
        }
    }
}
?>