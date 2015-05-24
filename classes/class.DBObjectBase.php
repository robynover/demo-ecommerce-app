<?php
require_once('class.DB.php');
/**
 * A base class for objects that correspond to a row of a particular DB table 
 */
abstract class DBObjectBase{
    protected $id;
    protected $_tablename;
    protected $_db; 
    protected $_db_fields = array(); //holds the list of valid db fields
    protected $error_msg; //holds the human-readable error message


    /**
     * Get a row from any table, based upon id, when primary key is named "id"
     * @param type $id
     * @return boolean 
     */
    protected function getRowById($id,$alt_id_name = null){
        $id_field = 'id';
        if ($alt_id_name){
            if (!in_array($alt_id_name,$this->_db_fields)){return false;} //sanity check for name of id field
            $id_field = $alt_id_name;
        }
        $id = (int)$id;
        if ($id){
            if (!$this->_db){
                $this->_db = DB::getInstance();
            } 
            //get the user from the DB
            $query = sprintf("SELECT * FROM %s WHERE %s = ? LIMIT 1",$this->_tablename,$id_field);
            $stmt = $this->_db->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetch();      
        }
        return false;
    }
    /**
     * Set the values from the DB to the properties of the class
     * @param $row The row array from a database fetch 
     */
    protected function setProperties($row){
        if (!is_array($row)){return false;}
        foreach ($row as $key=>$value){
            if (property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
        return true;
    }
    
    public function __get($name){
        //only get properties that are in the db fielda array and are set as properties in the class
        if (in_array($name,$this->_db_fields) && property_exists($this, $name)){
            return $this->$name;
        }
        return null;
        
    }
    public function __set($name, $value) {
       if (in_array($name,$this->_db_fields) && property_exists($this, $name)){
           $this->$name = $value;
       }
    }
    
   /*
    * Insert a row in the db based on property names
    */
    protected function insertRow(){
        if (!empty($this->_db_fields)){
            $query_fields = array();
            $query_values = array();
            foreach ($this->_db_fields as $field){
                if (property_exists($this,$field)){
                    //get value of each row and add it to the query
                    if (isset($this->{$field})){
                        $query_values[] = $this->{$field};
                        $query_fields[] = $field;
                    }
                }
            }
            if (!empty($query_fields)){ 
                //set up question mark placeholders for prepared statement
                $placeholders = substr(str_repeat("?,", count($query_fields)),0,-1);
                //create list of fields
                $fields_str = implode(',', $query_fields);
                //build prepared query. 
                //Note: it is built this way because you can't set placeholders for table & field names
                $query = sprintf("INSERT INTO %s (%s) VALUES(%s)",$this->_tablename,$fields_str,$placeholders);
                if (!$this->_db){
                    $this->_db = DB::getInstance();
		}
                
                $stmt = $this->_db->prepare($query);
                if ($stmt->execute($query_values)){
                    $this->id = $this->_db->lastInsertId(); //set new id
                    return true; 
                } else{
                    $error = $stmt->errorInfo();
                    //var_dump($error);
                    throw new PDOException('db error',$error[1]);
                }
                
                    
                                    
        }
        return false;     
        }
    }
    /*
     * Update a row
     */
    protected function updateRow(){
        //validate first
        /*if (!$this->validateData()){
            //invalid data. 
            return false;
        }*/
        if (!empty($this->_db_fields)){
            $query_fields = array();
            $query_values = array();
            foreach ($this->_db_fields as $field){
                
                if (property_exists($this,$field) && $field != 'id'){
                    if (isset($this->{$field})){
                        $query_values[] = $this->{$field};
                        $query_fields[] = $field;
                    }
                }
            }
            
            if (!empty($query_fields)){
                
                if (!$this->_db){
                    $this->_db = DB::getInstance();
		}
                $set = '';
                foreach($query_fields as $field){
                    $set .= " $field = ? ,";
                }
                $set = substr($set,0,-1); //remove last comma
                $query = sprintf('UPDATE %s SET %s WHERE id=? LIMIT 1',$this->_tablename,$set);
                $stmt = $this->_db->prepare($query);
                //add id to the query array
                $query_values[]=$this->id;
                if($stmt->execute($query_values)){
                    
                    return true;
                }
                $error = $stmt->errorInfo();
                throw new PDOException('db error',$error[0]);
                
            }        
        }
        return false; 
    }

    /*
     * Store the relevant propeties into the database
     * If the row exists, update it. If it's new, create it.
     * 
     */

    public function save(){
        if ($this->id){ //if there's an id, this is an existing record
            if ($this->updateRow()){
                return true;
            } 
                      
        } else{
            if ($this->insertRow()){ 
                return true;
            }
        }
        return false;
        
    }
    
    /**
     * A placeholder for a child class's own validate method 
     */
    abstract protected function validateData();
    
}
?>