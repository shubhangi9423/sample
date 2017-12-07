<?php 
/**
  * Template Name: GCMW Reports Confirmation
 **/
session_start();
$post_id= $_SESSION['post_id'];
get_header();
 ?>
 <div id="primary">
   <div class = "logo-ribbon">
    <div id="ribbon-main" style="margin-top: 30px;">
         <div class="ribbon-front"> <img alt="" src="/wp-content/uploads/2012/09/ribbonfront.png"> </div>
         <div class="ribbon" style="font-size: 16px;">             
           <h2 class="topic" style="align:center"><?php echo "Global Website Reports Confirmation";?></h2> 
         </div>
         <div class="ribbon-back"><img alt="" src="/wp-content/uploads/2012/09/ribbonback.png"></div>
     </div>
    </div>
	<div style="margin-left:50px">
	  <div class="msg"> <?php echo $message ?></div>
	   <br />
	  <h1 class="details"><b>Check your email to download the report or contact CCMT to receive a copy.</b></h1>
	</div>
</div>

<?php
if(isset($_POST['submit'])){    
  	    $templateName= get_post_meta( $post_id, 'gcmw_email_template', true );
        $reportdata=array('id' => '',
	                      'attachement' => getFileUploadPath($_POST),
	                      'report_type'=>$_POST['reportType'],
	                      'templateName'=>$templateName,
	                      'post_id'=>$post_id);
	    $generatedPath=generateFileUploadPath($_POST);
	  
	    if($_POST['reportType']=='acharya_report') 
	      generatePDFReport($_POST,$reportdata,$generatedPath);
	    else
	     generateCSVReport($generatedPath,$reportdata,$_POST);      
   }

function generateCSVReport($fileuploadpath,$reportdata,$queryParam){
	  if($queryParam['reportType']=='balvihar_report')
	    $query= GCMW_CSV_Report::$BALVIHAR_QRY;
	  else if($queryParam['reportType']=='ecamp_report')
	     $query= GCMW_CSV_Report::$ECAMP_QRY;
	  else if($queryParam['reportType']=='acharya_conf_report')
	     $query= GCMW_CSV_Report::$ACHARYA_CONFERENCE_QRY;
	  else if($queryParam['reportType']=='donation_report')
	   	 $query= GCMW_CSV_Report::$DONATION_QRY;
  	$csv=new GCMW_CSV_Report($queryParam,$fileuploadpath,$query);
  	$csv->generateCSV();
    sendReportEmail($reportdata);
   }

function generatePDFReport($queryParam,$reportdata,$generatedPath){
      $pdf=new GCMW_Achraya_Report($queryParam,$reportdata,$generatedPath);
      global $wpdb;
      $pdf->coverPage();
      $pdf->generatePDF();
      sendReportEmail($reportdata); 
  }

function generateFileUploadPath($queryParam){
	     date_default_timezone_set('Asia/Kolkata');
		 $date=date('Y-m-d_H-i-s');
	     $filename=$queryParam['reportType']."_".$date;
	     $fileuploadpath=get_template_directory()."/../../uploads/reports/".$filename;
	     return $fileuploadpath;
  }

function getFileUploadPath($queryParam){
	     date_default_timezone_set('Asia/Kolkata');
		 $date=date('Y-m-dH-i-s');
	     $filename=$queryParam['reportType'].$date;
	     return $filepath = "wp-content/uploads/reports/" . $filename.".csv";
        if($queryParam['reportType']=='acharya_report')
         return $filepath = "wp-content/uploads/reports/" . $filename.".pdf";
  } 

function sendReportEmail($reportdata){
      global $wpdb;
      $mailData= array(
        'to_address' =>  get_post_meta( $reportdata['post_id'], 'gcmw_multireplyto_address', true ),
        'replyto_address' => get_post_meta( $reportdata['post_id'], 'gcmw_replyto_address', true ),
        'subject' => $_SESSION['reportdata']['report_type']  
        );
      $template_data= array_merge(
         $_SESSION['reportdata']=$reportdata
        );
	     $template_data['logo_image'] = get_post_meta($reportdata['post_id'], 'gcmw_logo_image', true );
	     $template_data['banner_image'] = get_post_meta($reportdata['post_id'], 'gcmw_banner_image', true );
	     $template_data['event_title'] = get_the_title();
     sendMail($reportdata['templateName'],$template_data,$mailData);
  }

  get_footer(); ?>