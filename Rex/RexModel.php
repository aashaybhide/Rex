<?php

class RexModel{
    protected $conn;

    public function __construct(){
        $conn = Config::getSection("db_connection");
        $this->conn=mysqli_connect($conn['host'],$conn['username'],$conn['password'],$conn['database']);
        
        if (mysqli_connect_errno())
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    public function __toString() {
        return $this->id;
    }

    public function __set($name, $value) {
        $prop = "_".$name;
        $propObj = "_".$name."Id";
        if(property_exists($this, $prop))
             $this->$prop = $value;
        else
            $this->$name = $value;
    }
    
    public function __get($name) {
        $prop = "_".$name;
        $propObj = "_".$name."Id";
        if(property_exists($this, $prop))
             return $this->$prop;
        elseif(property_exists($this, $propObj)){
            $model = ucfirst($name);
            $entity = new $model();
            $entity->get($this->$propObj);
            return $entity;
        }
        else
            return $this->$name;
    }
    
    public function toCamel($str){        
        $t = explode("_", $str);
        $pascal = $t[0];
        for ($i = 1; $i < sizeof($t); $i++)
            $pascal .=ucwords($t[$i]);
        return $pascal;
    }

    public function execQuery($query){
            return mysqli_query($this->conn,$query);
    }

    private function insertRow(){
        $insert = "INSERT INTO $this->_table VALUES ()";
        if($this->execQuery($insert))
            return mysqli_insert_id($this->conn);
        else
            return null;
    }

    public function getColumnNames($tableName){
        $columnSelect = "SHOW COLUMNS FROM $tableName";
        $result = $this->execQuery($columnSelect);
        $i = 0;
        while($row = mysqli_fetch_array($result))
            $columns[$i++] = $row[0];

        return $columns;
    }
    
    static public function getEntityById($id) {
        $entity = new static();
        $entity->get($id);
        return $entity;
    }

    private function get($id) {
        $select = "SELECT * FROM $this->_table WHERE id = $id";
        $result = $this->execQuery($select);
        $row = mysqli_fetch_array($result);
        $this->mapEntity($row);        
    }
    
    protected function selectAll($condition,$column=null) {
        $fields = $column ? implode (",", $column) : "*";
        
        $select0 = "SELECT $fields FROM $this->_table WHERE";
        foreach ($condition as $key => $value) {
            $select0 .= " $key = '$value' AND";
        }
        $select = substr($select0, 0, -3); // To remove the last 'AND'
        
        return $this->getResult($select);
    }
    
    protected function getResult($select){
        $result = $this->execQuery($select);        
        if($result && $result->num_rows > 0)
            while($row = mysqli_fetch_assoc($result)){
                $model = static::$_entity;
                $entity = new $model();
                $entity->mapEntity($row);
                $entities[] = $entity;
            }
        return isset($entities) ? $entities : null;        
    }

    private function saveEntity() {
        $update = "UPDATE $this->_table SET ";
        foreach ($this->_fields as $key=>$field){
            $prop = $this->toCamel($field);
            if($key != 0)
                $update .= ", ";
            $update .= "$field = '".$this->$prop."'";
        }
        $update .= " WHERE id = $this->id";

        $this->execQuery($update);
    }
    
    public function save() {
        if(!$this->id)
            $this->id = $this->insertRow ();
        $this->saveEntity();
    }
    
    public function mapEntity($row) {
        foreach ($this->_fields as $field){
            $prop = $this->toCamel($field);
            $this->$prop = $row[$field];
        }
    }
}
?>