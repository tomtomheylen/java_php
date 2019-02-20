<?php
include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");

require 'binance-api-single_orig.php';
$api = new Binance("key","key");


$orders_btc_usdt = json_decode(file_get_contents('orders_btc_usdt.json'), JSON_OBJECT_AS_ARRAY);


$query = 'SELECT * FROM trades WHERE type = "BUY"';
if($result = mysqli_query($connection, $query)){
	//$orders = $api->orders("BTCUSDT", 20);
	//var_dump($orders);
	
	while($row = mysqli_fetch_array($result)){
		//var_dump($orders_btc_usdt);
		foreach($orders_btc_usdt as $value){
			
			if($row['order_id'] == $value['orderId'] && $value['status'] == "FILLED"){
				echo "hi";
				$quantity = round($value['origQty'], 6);
				$price = round($row['sell_value'], 2);
				$pair = $row['pair'];
				
				$order = $api->sell($pair, $quantity, $price, "LIMIT");
				
				if(isset($order['orderId'])){
					$order_id = (int)$row['order_id'];
					$new_order_id = $order['orderId'];
					

					$sql = "UPDATE trades SET type='SELL' WHERE order_id=".$order_id;
					mysqli_query($connection,$sql);
					$sql = "UPDATE trades SET order_id='$new_order_id' WHERE order_id=".$order_id;
					mysqli_query($connection,$sql);

					var_dump($order);

				}else{
					
					echo "something went wrong selling";
					var_dump($order);
				}
				
				
			}
		}
	}
}




















?>