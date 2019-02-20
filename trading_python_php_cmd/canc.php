<?php


include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");




				$quantity = round($value['origQty'], 6);
				$price = round($row['sell_value'], 2);
				$pair = $row['pair'];
				
				$order = $api->sell($pair, $quantity, $price, "LIMIT");
?>