<?php
class GCMW_CSV_Report{

    protected $fileUploadPath;
    protected $queryParams;
    public static  $BALVIHAR_QRY = "select dne.donation_id, dn.first_name, dn.last_name, dn.date,dn.email, dn.pan_num, dn.contact, dn.address1, dn.address2, dn.city, dn.state, dn.country, dn.pincode, dne.payment_method, dne.status, dne.amount,dne.transaction_no, dne.receipt_no, dne.bank_authorization_id, dne.batch_no, dne.txn_response_desc, dne.acq_response_code, dne.pymt_gw_message, dne.event_type
        from wp_donation as dn
        inner join wp_donation_event as dne
        on dn.id = dne.donation_id
        where  1=1 and dne.event_type =  'balvihar' ";
    public static  $ECAMP_QRY = "select dn. * , de.event_id, p.post_title, de.amount, de.status,de.event_type, de.transaction_no, de.receipt_no, de.bank_authorization_id, de.batch_no, de.txn_response_code, de.pymt_gw_message from  wp_donation_event de, wp_donation dn, wp_posts p
        where  1=1 
        and dn.id = de.donation_id
        and p.id = de.event_id
        and ucase( de.event_type ) =  'e-camp' ";
    public static  $ACHARYA_CONFERENCE_QRY = "select donation_id as reg_id,country,state,dn.prefix last_name, dn.first_name,center,
        email,contact, address1, city,pincode,acharya_lookup,
		registration_type, need_transport,
		arrivalFrom arrival_from,arrival_date,arrival_time,
		departureto departure_to, departure_date,departure_time,
		dn.date as created_date, dn.time as created_time,wcr.modified_date,wcr.modified_time,special_arrengment sepecial_req
		from wp_donation dn,
		wp_donation_event wde,
		wp_camp_registration wcr
		where dn.id=wde.donation_id
		and wde.id= wcr.donation_event_id
		and  1=1
		order by country,state,last_name,first_name";
    public static  $DONATION_QRY = "select dp.project_name, dne.donation_id, dn.date,dn.first_name, dn.last_name, dn.email, dn.pan_num, dn.contact, dn.address1, dn.address2, dn.city, dn.state, dn.country, dn.pincode, dne.payment_method, dne.status,dne.amount, dne.transaction_no, dne.receipt_no, dne.bank_authorization_id, dne.batch_no, dne.txn_response_desc, dne.acq_response_code, dne.pymt_gw_message, dne.event_type
        from wp_donation as dn
        inner join wp_donation_event as dne
        inner join wp_donation_project as dp
        on dn.id = dne.donation_id
        and dn.id = dp.donation_id where 1=1 and event_type='Donation' "; 
    private $query;

	public function __construct($queryParams,$fileUploadPath,$query){
     $this->fileUploadPath=$fileUploadPath;
     $this->queryParams=$queryParams;
     $this->query = $query;
	}

    public function generateCSV(){
      global $wpdb;
      //echo $this->query.$this->getWhereClause();
      $query=$wpdb->get_results($this->query.$this->getWhereClause(),ARRAY_A);
      $fp = fopen($this->fileUploadPath.'.csv', 'w');
      $i=0;
	  foreach($query as $q){
	  	if($i==0){
		    $fromdate=$this->queryParams['from_date']; 	
	   	    $todate=$this->queryParams['to_date'];
	   	    $reportType=$this->queryParams['reportType'];
	    	
	         $reportType="Report Type: ".$reportType;		
	   		 $fromDate="From Date: ".$fromdate;
	     	 $toDate="To Date: ".$todate;
		  	 $date="Report Generated Date and Time: ".date('d-M-y H:i:s');
		  	 fputcsv($fp,array($reportType,$fromDate,$toDate,$date));
		 
		  	$data=array_keys($q);
	  		 $i++;
	  		 if($i==1){
	  		 $data=array_keys($q);
	  		 fputcsv($fp,$data);
	  	    }
	  	}
	  	else 
	        fputcsv($fp,$q);
	  	    $i++;
	  } 	  
	  fclose($fp);	
    }

   public function getWhereClause(){
     $date=date('m/d/y');
   	 $fromdate=$this->queryParams['from_date']; 	
   	 $todate=$this->queryParams['to_date'];
   	 if(!empty($fromdate) & !empty($todate))
      $where=" and (dn.date>='$fromdate' and dn.date<='$todate')";
     return $where;
   }

   public function generateHTMLForm($reportType){
   ?>
	  <form name="acharya_report" id="acharya_report" method = "post" action = "/gcmw-report-conformation" enctype="multipart/form-data" onsubmit="return validateForm();">
	   <div class="select-style">
	   	 <div class="full-L">
           Select From Date:
         </div>
         <div class="full-R">
           <input type='text' class="from_date" name="from_date" id='from_date datetimepicker4' placeholder="Date Format Should be: 01/26/2016" />
        </div>
        <div class="full-L">
           Select To Date:
         </div>
        <div class="full-R">
           <input type='text' class="to_date" name="to_date"  id='to_date datetimepicker4' placeholder="Date Format Should be: 01/26/2016" />
        </div>
	   <input type='hidden' name='reportType' id='reportType' value='<?php echo $reportType; ?>' />
	   <div class ="career-contact-div" id= "career-contact-div" style="width:90%;background-color:yellow;"></div>
	   <br/>
	   <input type="submit" value = "Submit" class="submit" name= "submit"  onClick="return validate()" style="height:35px;width:100px;">
	   <br/>
	   <br/>
	   </div>
	  </form>
   <?php
    }	
}
?>