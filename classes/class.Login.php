<?php
require_once('class.DB.php');
require_once('PasswordHash.php');
class Login{
    private $_db;
    
    
    public function checkCredentials($email,$pw){
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)){return false;}
        if (strlen($pw)>20){return false;}
        /*
         * Using phpass open-source pw hashing class here.
         *      It provides its own salt in the has itself, 
         *      and has a function to check if the hash matches.
         * We could also store a hash and a salt in the DB
         */
        
        $hasher = new PasswordHash(8,false);
        if (!$this->_db){
            $this->_db = DB::getInstance();
        }
        //get the stored password
        $query = 'SELECT password_hash,id,first_name,is_admin FROM users WHERE email = ? LIMIT 1';
        $stmt = $this->_db->prepare($query);
        $stmt->execute(array($email));
        $row = $stmt->fetch();      
        if ($row['password_hash']){
            if($hasher->CheckPassword($pw,$row['password_hash'])){
               return array('id'=>$row['id'],'fname'=>strip_tags($row['first_name']),'is_admin'=>$row['is_admin']); 
            }
            return false;
            
        }
        return false;
    }
    
    
}
