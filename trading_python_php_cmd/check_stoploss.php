<?php


include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");

require_once('includes/binance-api-single_orig.php');
$api = new Binance("key","key");


$orders_btc_usdt = json_decode(file_get_contents('orders_btc_usdt.json'), JSON_OBJECT_AS_ARRAY);

$current_btc_usdt = json_decode(file_get_contents('current_btc_usdt.json'));



$query = 'SELECT * FROM trades WHERE type = "SELL"';
if($result = mysqli_query($connection, $query)){
	
	while($row = mysqli_fetch_array($result)){
		foreach($orders_btc_usdt as $value){
			if($row['order_id'] == $value['orderId'] && $row['type'] == "SELL" && $row['stop_limit_value'] >= $current_btc_usdt ){
				
				$cancel_order_id = (int)$row['order_id'];
				$pair = $row['pair'];
				$cancel = $api->cancel($pair, $cancel_order_id);	
				
				if(isset($cancel['orderId'])){
					$id = (int)$row['id'];
					

					$sql = "UPDATE trades SET type='CANCEL_STOP' WHERE id=".$id;
					mysqli_query($connection,$sql);
					echo "CANCELLED STOP";
					var_dump($cancel);

				}else{
					echo "something went wrong cancelling stoploss";
					var_dump($cancel);
				}
				
				
			}
		}
	}
}



$query = 'SELECT * FROM trades WHERE type = "CANCEL_STOP"';
if($result = mysqli_query($connection, $query)){
	
	while($row = mysqli_fetch_array($result)){
				$quantity = round($row['quantity'], 6);
				$pair = $row['pair'];
				
				
				$order = $api->sell($pair, $quantity, 0, "MARKET");
				
				if(isset($order['orderId'])){
					$id = (int)$row['id'];
					

					$sql = "UPDATE trades SET type='STOPLOSS' WHERE id=".$id;
					mysqli_query($connection,$sql);
					echo "STOPLOSS EXECUTED";
					var_dump($order);

				}else{
					echo "something went wrong with stoploss";
					var_dump($order);
				}
				
				
	}
}

?>