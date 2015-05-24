<?php
require_once 'PasswordHash.php';
require_once 'ProfileInterface.php';
/**
 * Represents a row in the customer table 
 */
class User extends DBObjectBase{
    
  protected $id,$first_name,$last_name,$email,$password_hash,$is_admin;
  protected $_tablename = 'users';
 
  protected $_db_fields = array(
                            'id',
                            'last_name',
                            'first_name',
                            'email',
                            'password_hash',
                            'is_admin'
                          );
          
  public function __construct($id = null) {
      if ((int)$id){
            //set the id
            $this->id = $id;
            //get details from db
            $data_row = $this->getRowById($this->id);
            //populate properties
            $this->setProperties($data_row);
      }
  } 
  
  public function newAccount($email,$fname,$lname,$password){
      $this->first_name=$fname;
      $this->last_name = $lname;
      $this->email = $email;
      if (!$this->validateData()){
          return false;
      }
      if (strlen($password)>20){return false;}
     
      $hasher = new PasswordHash(8,false); //create a hash
      $hash = $hasher->HashPassword($password);
      
      $this->password_hash = $hash;
      try{
          $this->save();
          return true;
      } catch(PDOException $e){ //get errors, such as if email already exists in DB
          if ($e->getCode() == 1062){
              $this->error_msg = 'Email already exists in Database';
          }
          return false;
      }    
  }
  
  
  
  
 /**
  * Check that names contain only allowed characters
  * Accounts for hypenated name, apostrophe, and initials: O'Brien, Stein-Browne, A.J.
  * @param string $name
  * @return boolean 
  */
  
  private function validateName($name){
      
      $pattern = '#\A[a-zA-Z\'-\.]+\Z#';
      if (preg_match($pattern, trim($name))){
          return true;
      }
      return false;
  }
  
  public function getFullName(){
      return $this->first_name.' '.$this->last_name;
  }
   
  
   //find out if user is an admin without loading all the user data
   public function isAdmin($user_id = null){
       return $this->is_admin;
   }
   
   public function validateData(){
        //email must be valid
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        //name must pass reg ex
        if (!$this->validateName($this->first_name) || !$this->validateName($this->last_name) ){
            return false;
        }
        
        return true;
    }
   
   
}