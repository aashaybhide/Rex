<?php

class RexForm {
    private $action;
    private $method;
    private $input;

    public function __construct($enctype="") {
        $this->method = "post";
        if($enctype)
            $this->enctype = $enctype;
    }
    
    public function action($action){
        $this->action = $action;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function addInput($name,$placeholder=""){
        $input = $this->input[] = new RexInput($name);
        $input->setDefault($placeholder);
        return $input;
    }
    
    public function addHidden($name,$value=null){
        $input = $this->input[] = new RexInput($name,"hidden");
        $input->setValue($value);
        return $input;
    }
    
    public function addButton($value,$name="aButton"){
        $input = $this->input[] = new RexInput($name,"button");
        $input->setValue($value);
        return $input;
    }
    
    public function addSubmit($value,$name="submit"){
        $input = $this->input[] = new RexInput($name,"submit");
        $input->setValue($value);
        return $input;
    }
    
    public function addFile($name){
        $input = $this->input[] = new RexInput($name,"file");
        return $input;
    }
    
    public function addText($name){
        $input = $this->input[] = new RexInput($name,"textarea");
        $input->setClass(null);
        return $input;
    }
    
    public function addEditField($name,$value){
        $input = $this->input[] = new RexInput($name);
        $input->setValue($value);   
        return $input;
    }
    
    public function addRadio($name,$options){
        $input = $this->input[] = new RexInput($name,"radio");
        $input->setOptions($options);
        return $input;
    }
    
    public function addSelect($name,$options){
        $input = $this->input[] = new RexInput($name,"select");
        $input->setOptions($options);
        return $input;
    }
    
    public function show(){
        echo $this->getForm();
    }
    
    public function showInline(){
        echo $this->getForm("");
    }
    
    public function getForm($seperator="<br>"){
        $attributes = "";
        foreach (["method","action","enctype","id"] as $att){
            $attributes .= (isset($this->$att)) ? " $att='".$this->$att."'" : null;
        }
        
        $formView = "<form $attributes>";
        foreach ($this->input as $input)
            $formView .= $input->parse($seperator);       
        $formView .= "</form>";
        
        return $formView;
    }    
}

class RexInput{
    public $type;
    public $name;
    
    public function __construct($name,$type="text") {
        $this->name = $name;
        $this->type = $type;
    }
    
    public function parse($seperator="<br>"){        
        $html = "";
        switch ($this->type){
            case "select":
                $html = "<select name='$this->name'>";
                foreach ($this->options as $val => $option)
                    $html .= "<option value='$val'>$option</option>";
                $html .= "</select>";
                break;
            case "radio":
                foreach ($this->options as $option)
                    $html .= " <input type='radio' value='$option'> $option ";
                break;
            case "textarea":
                $html .= "<textarea class='$this->class' name='$this->name'> </textarea>";
                break;
            default:
                $attributes = "";
                foreach ((array)$this as $key => $value){
                    if($key != "label")
                        $attributes .= "$key='$value' ";
                }
                $html = isset($this->label) ? "<label>".$this->label.":</label> <input $attributes>" : "<input $attributes>";
        }
//        echo $attributes;
        return ($this->type=="hidden" || $this->type=="link") ? $html : $html.$seperator;
    }
    
    public function setDefault($value){
        $this->placeholder = $value;
        return $this;
    }    
    public function setLabel($label){
        $this->label = $label;
        return $this;
    }    
    public function setClass($class){
        $this->class = $class;
        return $this;
    } 
    public function setValue($value){
        $this->value = $value;
        return $this;
    }
    public function setId($value){
        $this->id = $value;
        return $this;
    }
    public function readonly(){
        $this->readonly = "readonly";
        return $this;
    }
    public function secret(){
        $this->type = "password";
        return $this;
    }
    public function setOptions($options){
        if($this->type == "select" || $this->type == "radio"){
            $this->options = $options;
        }
    }
}
