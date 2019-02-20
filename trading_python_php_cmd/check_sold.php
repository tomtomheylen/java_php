<?php

include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");

$orders_btc_usdt = json_decode(file_get_contents('orders_btc_usdt.json'), JSON_OBJECT_AS_ARRAY);

$query = 'SELECT * FROM trades WHERE type = "SELL"';
if($result = mysqli_query($connection, $query)){
	//var_dump($orders);
	while($row = mysqli_fetch_array($result)){
		foreach($orders_btc_usdt as $value){
			if($row['order_id'] == $value['orderId'] && $value['status'] == "FILLED"){
					$order_id = (int)$row['order_id'];
					$sql = "UPDATE trades SET type='SOLD' WHERE order_id=".$order_id;
					mysqli_query($connection,$sql);
					echo '<br><h2>Sold for : '.$value['price'].'</h2>';
			}
		}
	}
}






?>