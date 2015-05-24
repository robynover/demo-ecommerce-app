<?php
//just a placeholder for now. A customer is a type of User.
class Customer extends User implements ProfileInterface{
    /**
     * Defines which fields will be included for display of basic profile data
     * Array indices are the Field labels that will be displayed on the front end
     * '_title' is used in the heading
     *
     * @return Object
     */
  public function getProfileData(){
        $data = array(
            'First Name'=>$this->first_name,
            'Last Name'=>$this->last_name,
            'Email'=>$this->email,
            '_title'=>$this->getFullName()
        );
        return $data;
    }
}