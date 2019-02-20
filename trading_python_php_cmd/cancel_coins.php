<?php
include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");





$query = 'SELECT * FROM trades WHERE type = "PENDING"';
if($result = mysqli_query($connection, $query)){
	//var_dump($open_orders);
	while($row = mysqli_fetch_array($result)){
		
		if($row['type'] == "PENDING"){
			//echo $row['order_id']."<br>";
			$cancel_time = (int)$row['cancel_time'];
			
			echo $cancel_time."<br><br>";
			
					
			if($cancel_time < time()){
				$order_id = (int)$row['order_id'];
				$pair = $row['pair'];
				
				$sql = "UPDATE trades SET type='CANCELED' WHERE order_id=".$order_id;
				mysqli_query($connection,$sql);
			}
		}
	}
	
}


















?>