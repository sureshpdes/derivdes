<?php
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
	}
  $db_host = "localhost";  // Change as required
  $db_user = "neocode_edi";  // Change as required
  $db_pass = "a04mIr^Mk0~t";  // Change as required
  $db_name = "neocode_edi";

	 // $db_host = "localhost";  // Change as required
	 // $db_user = "root";  // Change as required
	 // $db_pass = "";  // Change as required
	 // $db_name = "edidb";

    $conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
    if(mysqli_connect_error())
        die('Connect Error');


  $_SESSION['mysql_default_offset'] = 0;
	$_SESSION['mysql_default_limit'] = 200;
	$_SESSION['mysql_total_rescount'] = 0;
	$_SESSION['mysql_table_data_count'] = 0;



     function uk_date_to_mysql_date($date){
	  $date_year=substr($date,6,4);
	  $date_month=substr($date,3,2);
	  $date_day=substr($date,0,2);
	  $date=date("Y-m-d", mktime(0,0,0,$date_month,$date_day,$date_year));
	        return $date;
	}

	 function mysql_date_to_uk_date($originalDate){
	   $newDate = date("d-m-Y H:i", strtotime($originalDate));
	   return $newDate;
	 }
	 function mysql_date_to_uk_date_without_time($originalDate){
	   $newDate = date("d-m-Y", strtotime($originalDate));
	   return $newDate;
	 }
	 $sql_em = "Select exm.exchange_master_name, em.entry_master_id,em.primary_pdf_name from entry_master em inner join template_master tm on em.template_master_id = tm.template_master_id inner join exchange_master exm on tm.exchange_master_id = exm.exchange_master_id";
	 $rs_em = $conn->query($sql_em);
	 print_r($rs_em);
?>
