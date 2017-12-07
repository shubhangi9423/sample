<?php

require_once('pdf-generator/PDF_Label.php');

class GCMW_Achraya_Report extends PDF_Label {
    
    protected $reportFileName;
    protected $queryParams;
    protected $fileUploadPath;
	public function __construct($queryParams,$reportFileName,$fileUploadPath) {
		$tFormat = array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>20, 'marginTop'=>20, 'NX'=>2, 'NY'=>4, 'SpaceX'=>0, 'SpaceY'=>5, 'width'=>90, 'height'=>57.5, 'font-size'=>9);
    	parent :: __construct($tFormat);
    	$this->reportFileName=$reportFileName;
    	$this->queryParams = $queryParams;
    	$this->fileUploadPath=$fileUploadPath;
	}

	public function Header(){
		$date=date('d-M-y H:i:s');
		 if($_SERVER['HTTPS'] == 'on')
		  $protocol="https://";
		 else 
	      $protocol="http://";
	      $server= $_SERVER['HTTP_HOST'];
		$image1 = $protocol.$server."/wp-content/uploads/CCMT_LOGO.png";
		$this->Cell(65);
		$this->Image($image1,20,7,18);
	    $this->SetFont('Arial','B',15);
	    // Title
	    $this->Cell(70,20,'CHINMAYA MISSION ACHARYAS',0,0,'C');
	     $this->Cell(180);
	     $this->SetY(10);
	     $this->SetFont('Arial','',10);
	    $this->Cell(190,10,$date,0,0,'R');
	    // Line break
	    $this->Ln(30);
	}

	public function Footer(){
	    // Position at 2 cm from bottom
	    $this->SetY(-20);
	    // Arial italic 9
	    $this->SetFont('Arial','I',9);
	    $this->Cell(20);
	    $this->Cell(20,20,'for any correction please contact ccmt@chinmayamission.com',0,0);
	    // Page number
	    $this->Cell(150);
	    $this->Cell(0,20,'Page '.$this->PageNo(),0,0);
	    $this->SetY(-25);
	    $this->Cell(95);
	    $this->Cell(20,20,'FOR INTERNAL USE',0,0);   
	}

    protected function getWhereClause(){
		$selected_salutation=implode("','",$this->queryParams['salutation']);
	    if(!empty($selected_salutation))
	  	   $selectedSalutation="and aname IN('$selected_salutation')";
	    else
	       $selectedSalutation="";

        $selected_country = implode("','",$this->queryParams['country']);
	    if(!empty($selected_country))
	       $selectedCountry=  $selectedSalutation."and wa.country IN('$selected_country')";
	   	else
	       $selectedCountry=$selectedSalutation."";
	  
	    $selected_state=implode("','",$this->queryParams['state']);
	    if(!empty($selected_state))
	  	  return $selectedState=$selectedCountry."and wa.state IN('$selected_state')";
	    else 
	  	  return $selectedState=$selectedCountry."";
	}

	public function generatePDF(){
    global $wpdb;
	$this->AddPage();
    $query=$wpdb->get_results("SELECT aname,last_name,name,wa.address1,wa.address2,wa.address3,wa.pincode,wa.country,wa.state,wa.city,wa.phone,wa.email FROM wp_acharya wa 
    left join wp_location wl on wa.centre=wl.id  where  1=1 ".$this->getWhereClause()." ORDER BY country,state,aname, last_name ASC");

      $previousSection="";
      foreach($query as $q) {  
         $obj = new Label_Formater($q);   
         $currentSection=$obj->get_section();
         if($currentSection==$previousSection)
            $currentSection="";
         else
            $previousSection=$currentSection;

         $this->Line(20,20,204,20);      
         
         $name= sprintf("%s",$obj->get_name());  
         $add = sprintf("%s%s\n%s%s",$obj->get_centerName(), $obj->get_address(),$obj->get_section(),$obj->   get_contactDetails());
         $currSection=sprintf("%s",$currentSection); 
         $this->Add_Label($name,$add,$currSection);         
      }
		 $fileuploadpaths=$this->fileUploadPath.'.pdf';
		 $this->Output($fileuploadpaths,'F');
   }

  	public function coverPage(){
	   	$this->AddPage();
	    if ($_SERVER['HTTPS'] == 'on')
	    	$protocol="https://";
	    else 
	    	$protocol="http://";
	        $server= $_SERVER['HTTP_HOST'];
	    $image1 = $protocol.$server."/wp-content/uploads/logo.png";
	    $this->Image($image1,85,50,40,60);
	    $this->SetFont('Times','I',30);
	    $this->Cell(60);
	    $this->MultiCell(480,200,'ACHARYA REPORT');
	    $this->SetFont('Times','I',12);
	    $this->Cell(20);
	    $this->MultiCell(300,5,'Filters Applied:');
	    $this->Cell(20);
	    $salutation=implode(",",$this->queryParams['salutation']);
	    if($salutation!="")
	    $this->MultiCell(300,5,'Salutation:'.$salutation);
	    else
	    $this->MultiCell(300,5,'Salutation:None'); 	
	    $this->Cell(20);
	   if(!empty($this->queryParams['country'])){
	    $country=implode(",",$this->queryParams['country']);
	    if($country!="")
	    $this->MultiCell(300,5,'Country:'.$country);
	    else
	    $this->MultiCell(300,5,'Country:None'); 
	    }
	   if(!empty($this->queryParams['state'])){	
	    $this->Cell(20);
	    $state=implode(",",$this->queryParams['state']);
	    if($state!="")
	    $this->MultiCell(300,5,'State:'.$state);
	    else
	    $this->MultiCell(300,5,'State:None');
      }
    }
   
   public function generateForm($reportType){?>
   <form name="acharya_report" id="acharya_report" method = "post" action = "/gcmw-report-conformation" enctype="multipart/form-data" onsubmit="return validateForm();">
   <div class="select-style">
      <div class="full-L">
       Select Salutation:
      </div>
     <div class="full-R">
       <select name="salutation[]" id="salutation" style="width:404px;height:110px;" multiple="multiple" >
          <option value="null">Select Types</option>
          <option value="Acharya">Acharya</option>
          <option value="Br">Bramchari</option>
          <option value="Brni">Bramcharini</option>
          <option value="Swami">Swami</option>
          <option value="Swamini">Swamini</option>
        </select>
      </div>
      
      <div class="full-L">
        Select Country:
      </div>
       <div class="full-R">
       <select name="country[]" id="country" style="width:404px;height:160px;" multiple="multiple" >
            <option value="null">Select Types</option>
          <?php global $wpdb;
           $results =$wpdb->get_results("SELECT Distinct country FROM wp_acharya order by country");
           foreach($results as $country){ ?>
            <option value="<?php  echo $country->country;?>"> <?php echo $country->country;?> </option>
          <?php  }  ?>
       </select><br/>
     </div>

     <div class="full-L">
       Select State:
     </div>
     <div class="full-R">
       <select name="state[]" id="state" style="width:404px;height:160px;" multiple="multiple">
            <option value="null">Select Types</option>
          <?php global $wpdb;
                $results =$wpdb->get_results("SELECT Distinct state,country FROM wp_acharya order by state");     
                foreach($results as $state){
                 $state_country=$state->country."_".$state->state; ?>
            <option value="<?php echo $state->state;?>" id="<?php echo $state_country; ?>"><?php echo $state->state;?></option>
          <?php  }  ?>
       </select>
     </div> 
   <input type='hidden' name='reportType' id='reportType' value='<?php echo $reportType; ?>' />
   <div class ="career-contact-div" id= "career-contact-div" style="width:90%;background-color:yellow;"></div><br/>
   <input type="submit" value = "Submit" class="submit" name= "submit"  onClick="return validate()" style="height:35px;width:100px;">
  <br/>
  <br/>
</div>
</form>
<?php
  }

}
?>