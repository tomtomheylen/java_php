<?php
include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");

require_once('includes/binance-api-single_orig.php');
$api = new Binance("key","Key");

$current_btc_usdt = json_decode(file_get_contents('current_btc_usdt.json'));

$orders_btc_usdt = json_decode(file_get_contents('orders_btc_usdt.json'), JSON_OBJECT_AS_ARRAY);


foreach($orders_btc_usdt as $value){
	//echo $value['symbol'];
}

$query = 'SELECT * FROM trades WHERE type = "PENDING"';
if($result = mysqli_query($connection, $query)){
	echo $current_btc_usdt."<br>";
	while($row = mysqli_fetch_array($result)){
		if($row['buy_value'] <= $current_btc_usdt ){
			
			$quantity = round($row['quantity'], 6);
			$pair = $row['pair'];
			
			$order = $api->buy($pair, $quantity, 0, "MARKET");
			//var_dump($order);
			
			if(isset($order['orderId'])){
				$orderOrderId = $order['orderId'];
				
				$id = $row['id'];
				
				
				$sql = "UPDATE trades SET type='BUY' WHERE id=".$id;
				mysqli_query($connection,$sql);

				$sql = "UPDATE trades SET order_id='$orderOrderId' WHERE id=".$id;
				mysqli_query($connection,$sql);
				var_dump($order);
			}else{
				if($order['code'] == -2010){
					$id = $row['id'];
					$sql = "UPDATE trades SET type='NO BAL' WHERE id=".$id;
					mysqli_query($connection,$sql);

					echo "no balance";
					var_dump($order);
				}else{
					echo "something went wrong buying";
					var_dump($order);
				}
					
			}
			
			
		}
	
	}	
}
?>
