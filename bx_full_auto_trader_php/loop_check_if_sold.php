<?php
include_once('includes/constants.php');
date_default_timezone_set("Asia/Bangkok");

//################# curl buy / sell function #################################

function getorders($pairing_id,$type){
	if($ch = curl_init ()){
		$data['pairing'] = $pairing_id;//btc/thb
		$data['type'] = $type;
		$data['key'] = '570025c31e30';
		$mt = explode(' ', microtime());
		$nonce = $mt[1].substr($mt[0], 2, 6);
		$data['nonce'] = $nonce;
		$data['signature'] = hash('sha256', '570025c31e30'.$nonce.'f3fb8b6a7df6');
		// if($this->twofa != ''){
			// $data['twofa'] = $this->twofa;
		// }
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://bx.in.th/api/getorders/');
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); 
		curl_setopt ( $ch, CURLOPT_POST, count($data));
		curl_setopt ( $ch, CURLOPT_POSTFIELDS,$data);
		
		$str = curl_exec ( $ch );
	
		curl_close ( $ch );
		
		$str = json_decode($str);
	
		return $str;

	}

}//end function getorders()

$pairing_id = 1;//btc/thb
$type = "sell";

$result_bx = getorders($pairing_id,$type);

if(isset($result_bx)){//stdClass Object ( [success] => 1 [orders] => Array ( [0] => stdClass Object ( [pairing_id] => 1 [order_id] => 7540394 [order_type] => buy [rate] => 287235 [amount] => 0.00174073 [date] => 2018-02-03 23:31:08 ) ) )


	//############ validate if have response from BX and is object
	if($result_bx->success == 1){//[success] => 1//in this case it will always be success even with wrong pairing id etc. but it indicates the connection made
		// print_r($result_bx->orders);
		// echo "<br>";
		$bx_buy_orders_count = count($result_bx->orders);//count the bx results
		
		//print_r($result_bx->orders);
		//echo "<br><br>";
		
		$bx_ids = array();
		for($i=0;$i<count($result_bx->orders);$i++){
			array_push($bx_ids, $result_bx->orders[$i]->order_id);
		}
		//print_r($bx_ids);
		//echo "<br><br>";
		
		
		$query 	= 'SELECT * FROM trades_made WHERE status = "selling"';
		if($result = mysqli_query($connection, $query)){//validate if have result before continue
			$mysql_count =  mysqli_num_rows($result);//count the mysql results
			
			//if mysql result < bx result -> take action and sell the shit out the trades. else nothing
			if($mysql_count > $bx_buy_orders_count){
				while($row = mysqli_fetch_array($result)){
					
					if(!in_array($row['order_id'], $bx_ids)){
			
						$sql = "UPDATE trades_made SET status='complete' WHERE id=".$row['id'];
						mysqli_query($connection,$sql);
						
						
						break;
					}
				}
				
			}
		}

		
	}
	
}else{//no internet
	echo "no internet connection";
}











?>