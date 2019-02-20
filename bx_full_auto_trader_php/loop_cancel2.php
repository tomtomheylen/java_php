<?php
include_once('includes/constants.php');
date_default_timezone_set("Asia/Bangkok");

//################# curl buy / sell function #################################
function cancel($pairing_id,$order_id){
	if($ch = curl_init ()){
		$data['pairing'] = $pairing_id;//btc/thb
		$data['order_id'] = $order_id;
		$data['key'] = '570025c31e30';
		$mt = explode(' ', microtime());
		$nonce = $mt[1].substr($mt[0], 2, 6);
		$data['nonce'] = $nonce;
		$data['signature'] = hash('sha256', '570025c31e30'.$nonce.'f3fb8b6a7df6');
		// if($this->twofa != ''){
			// $data['twofa'] = $this->twofa;
		// }
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://bx.in.th/api/cancel/');
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

}//end function cancel()


	$query = 'SELECT * FROM trades_made WHERE status = "buying"';
	if($result = mysqli_query($connection, $query)){
		
		while($row = mysqli_fetch_array($result)){
			$pairing_id = 1;//btc/thb
			$order_id = $row['order_id'];
			$cancel_time = substr($row['cancel_time'],9,26);
			$cancel_time = new DateTime($cancel_time);
			$current_time = new DateTime();
			//print_r($cancel_time);
			if($current_time >= $cancel_time){
				sleep(3);//bx wants minimum 2 seconds between trades
				$result_bx = cancel($pairing_id,$order_id);

				if(isset($result_bx)){

					//############ validate if have response from BX and is object
					if($result_bx->success == 1){
					
						$sql = "UPDATE trades_made SET status='canceled' WHERE order_id=".$row['order_id'];
						mysqli_query($connection,$sql);
					
					}
				}	
				break;

			}

		}

	}
	













	
	
	
	
	
	
	
	
	
	
	
?>