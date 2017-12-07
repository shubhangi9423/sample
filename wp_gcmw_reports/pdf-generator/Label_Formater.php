<?php

class Label_Formater
{
      protected $salutation;
      protected $lastName;
      protected $centerName;
      protected $address1;
      protected $address2;
      protected $address3;
      protected $address;
      protected $pincode;
      protected $city;
      protected $state;
      protected $country;
      protected $phone;
      protected $email;
      protected $contactDetails;
    
    public function __construct($q){	
         $this->salutation=$q->aname;
         $this->lastName=$q->last_name;
         $this->centerName=$q->name;
         $this->address1=$q->address1;
         $this->address2=$q->address2;
         $this->address3=$q->address3;
         $this->pincode=$q->pincode;
         $this->country=$q->country;
         $this->state=$q->state;
         $this->city=$q->city;
         $this->phone=$q->phone;
         $this->email=$q->email;    
      }
	
	public function get_name(){
	 	 $salutation= strtoupper($this->salutation);
	 	 $lastname=strtoupper($this->lastName);
	 	 $name=$salutation." ".$lastname;
	 	 return $name;
	 }
	public function get_centerName(){
	 	if(empty($this->centerName))
	 	  $centerName="";
	 	else
	 	  $centerName="\n".trim(strtoupper($this->centerName));
	 	
	 	return $centerName;
	 }
	public function get_address(){
	 	if(trim(strtoupper($this->centerName)) == trim(strtoupper($this->address1)))
	 	  $address1="";
	 	else
	 	  $address1="\n".trim(strtoupper($this->address1));
	 	if(empty($this->address2))
	 	  $address2="";
	 	else
	 	  $address2="\n".trim(strtoupper($this->address2));
	 	if(empty($this->address3))
	 	  $address3="";
	 	else
	 	  $address3="\n".trim(strtoupper($this->address3));
	 	if(empty($this->city))
	 	  $city="";
	    else
	      $city="\n".trim(strtoupper($this->city));
        if(empty($this->pincode))
          $pincode="";
        else
          $pincode=",".trim($this->pincode);

	 	$address=$address1.$address2.$address3.$city.$pincode;
	 	return $address;
	 }
	public function get_section(){
        if(empty($this->state))
          $state="";
        else
          $state=trim(strtoupper($this->state));
        if((empty($this->country))||(trim($this->state) == trim($this->country)))
          $country="";
        else
          $country=", ".trim(strtoupper($this->country));

      return $stateCountry=$state.$country;
	 }
	public function get_contactDetails(){
		if(empty($this->phone))
		  $phone="";
		else
		  $phone="\n".trim($this->phone);
        if(empty($this->email))
          $email="";
        else
          $email="\n".trim($this->email);
        return $contactDetails=$phone.$email;
	 }
	 public function preparedText($text){
        if(empty($text)||trim($text))
          $text="";
       else
          $text="\n".trim(strtoupper($this->text));

        return $text;
	 }	
}
?>