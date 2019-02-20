<?php
include_once('includes/constants.php');
ini_set('max_execution_time', 0);
date_default_timezone_set("Asia/Bangkok");
//echo "Array ( [trade_id] => 4172103 [rate] => 533767.23400000 [amount] => 0.00830696 [trade_date] => 2017-12-26 12:48:29 [order_id] => 6959285 [trade_type] => sell [reference_id] => 0 [seconds] => 30 ) ";
//echo '<br />';

$rate = 0;
$trade_date = 0;
$trade_type = '';
$old_date = new DateTime("2017-12-27 11:37:14");
$new_date = new DateTime();
$url = 'https://bx.in.th/api/trade/?pairing=1';
//$contents = file_get_contents($url);

$loops = 0;
while(1){
	
	if(@$contents = file_get_contents($url)){//the @ suppresses the warning
		
		$contents =  json_decode($contents, true);
		
		
		
			  
		for($i=0;$i<=9;$i++){
			
			
			$new_date = new DateTime($contents['trades'][$i]['trade_date']);
			
			if ($new_date > $old_date) {
				
				
				
				$rate = $contents['trades'][$i]['rate'];
				$trade_date = $contents['trades'][$i]['trade_date'];
				$trade_type = $contents['trades'][$i]['trade_type'];
				
				/*
				 echo "<b>verified</b>". $contents['trades'][$i]['trade_date']. " <b>Val</b> ";
				 echo $contents['trades'][$i]['rate']. " <b>Type</b> ";
				 echo $contents['trades'][$i]['trade_type'];
				 echo '<br>';
				*/
			 
				$query = "INSERT INTO trades_thb_btc (rate, trade_date, trade_type)
					VALUES ('$rate', '$trade_date', '$trade_type')";
				mysqli_query($connection,$query);

			}

			
				 
		

		}
	$old_date = new DateTime($contents['trades'][9]['trade_date']);
		
	}//end if($contents !== false){
		
	//$loops++;
	sleep(3);
}//end while loop
	 
?>