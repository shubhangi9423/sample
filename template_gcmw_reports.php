<?php 
/**
  * Template Name: GCMW Reports
  */
 get_header();
 session_start();

?>
<?php
$post_id= $post->ID;
$_SESSION = array();
$_SESSION['post_id'] = $post_id;
 if($_SESSION['reg_post_id'] == null) {
  $_SESSION['reg_post_id'] = $post_id;
 }
  ?>
<style>
div#dataforma {
    margin-left: 28px;
    margin-top:  35px;
    margin-right:28px;
}

.send {
    background-color: rgba(128, 128, 128, 0.24);
    padding: 9px;
   font-size: 22px;
   width: 927px;
}

.jobcode {
    padding-top: 10px;
}

.full-R {
    margin-top: -16px;
    margin-left: 138px;
}

.full-L {
       width: 350px;
       margin-top: 2px;
}

.content {
    height: auto !important;
    margin: 0px 25px 0 4px;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

input,text {
    border-radius: 8px !important;
}

div#page {
    margin-bottom: 96px !important;
}
.reporttype {
   margin-left: 33px;
}
.reporttypes {
    margin-left: 165px;
}
input.submit {
    margin-left: 554px;
}
div#primary {
    padding-bottom: 100px;
}
</style>

<?php 
$pdf=new GCMW_Achraya_Report('','','');
$csv=new GCMW_CSV_Report('','','');
 ?>
<div id="primary">
    <div id="ribbon-main" style="margin-top: 30px;">
                <div class="ribbon-front">
                <img alt="" src="/wp-content/uploads/2012/09/ribbonfront.png">
            </div>
            <div class="ribbon" style="font-size: 16px;">
                 <h2><?php  echo "Global Website Reports"; ?></h2>
            </div>
                <div class="ribbon-back">
                <img alt="" src="/wp-content/uploads/2012/09/ribbonback.png">
            </div>
    </div>
	<div id="dataforma">
   <div class="full-L reporttype"> Report Type:  </div>
	<div class="full-R reporttypes"><select name="select_report" id="select_report" style="height:35px;width:247px;margin-left:2px" onchange="showTab();">
					<option value="null">Select Reports</option>
					<option value="acharya_report">Acharya Report</option>
					<option value="balvihar_report">Balvihar Report</option>
					<option value="ecamp_report">Ecamp Report</option>
					<option value="acharya_conf_report">Acharya Conference Report</option>
					<option value="donation_report">Donation Report</option>
	</select>
</div>
<div class="reports" id="reports" >
 <div class="acharya_report" id="acharya_report" style="display: none;">
 <div id="dataforma">
    <?php $pdf->generateForm('acharya_report'); ?>
</div>
</div>
<div class="balvihar_report" id="balvihar_report" style="display:none;">
	  <?php $csv->generateHTMLForm('balvihar_report'); ?>
</div>
<div class="ecamp_report" id="ecamp_report" style="display:none;">
	<?php $csv->generateHTMLForm('ecamp_report'); ?>
</div>
<div class="acharya_conf_report" id="acharya_conf_report"  style="display:none;">
	 <?php $csv->generateHTMLForm('acharya_conf_report'); ?>
</div>
<div class="donation_report" id="donation_report" style="display:none;">
	<?php $csv->generateHTMLForm('donation_report'); ?>
</div>
</div>
</div>
</div>


<?php get_footer();  ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
function showTab(select_report) {
	select_report = '#' + select_report;
    $(select_report).show();
    $('#reports>div').not(select_report).hide();
}
$('#select_report').change( function() {
  	showTab( $( this ).val() );
});

$(function() {
 
$('#country').click(function() {
  jQuery('#state option').hide();
  var val = "";
  $('#country :selected').each(function() {
      val = this.value;  

    $('#state option').filter(function() {
            return this.id.indexOf( val + '_' ) === 0;
        })
   .show();
    })

    .change();
 });
})
    
</script>