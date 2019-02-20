<?php

include_once('includes/constants.php');
date_default_timezone_set("Asia/Bangkok");

//################# curl buy / sell function #################################
function buy_sell($pairing_id,$amount,$rate,$type){
	if($ch = curl_init ()){
		$data['pairing'] = $pairing_id;//btc/thb
		$data['amount'] = $amount;
		$data['rate'] = $rate;
		$data['type'] = $type;
		$data['key'] = 'xxx';
		$mt = explode(' ', microtime());
		$nonce = $mt[1].substr($mt[0], 2, 6);
		$data['nonce'] = $nonce;
		$data['signature'] = hash('sha256', 'xxx'.$nonce.'xxx');
		// if($this->twofa != ''){
			// $data['twofa'] = $this->twofa;
		// }
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://bx.in.th/api/order/');
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

}//end function buy_sell()


if(isset($_POST['buy'])){

//################## buy order ###############################
	
	
	
	//############## buy - sell - profit values #############
	//============= buy values ============
	$pairing_id = 1;//BTC / THB
	$type = "buy";
	
	$buy_amount_bath = 500;
	$buy_rate = $_POST['buy_rate'];
	//$buy_rate = 224000;
	$buy_amount_coin = (1/$buy_rate)*$buy_amount_bath;
	
	$buy_values = array($buy_amount_bath,$buy_rate,$buy_amount_coin);
	
	//======== sell values ====================
	$percent            = $_POST['percent'];
	$sell_amount_coin   = $buy_amount_coin*0.9975;
	$sell_rate          = $buy_rate*(1+($percent/100));
	
	$sell_values        = array($percent, $sell_amount_coin, $sell_rate);
	
	//                  ============= statistical data ================
	$profit             = $buy_amount_bath*(($percent-0.5)/100);
	$time_frame         = $_POST['time_frame'];
	$percent_max_min    = $_POST['percent_max_min'];
	$up_down            = $_POST['up_down'];
	$u_smooth           = $_POST['u_smooth'];
	$prob               = $_POST['prob'];
	$prob_data          = $_POST['prob_data'];
	$trend              = $_POST['trend'];
	$trend_data         = $_POST['trend_data'];
	$rate_chosen        = $_POST['rate_chosen'];
	$time_stamp         = new DateTime();
	
	$statistical_values = array($profit,$time_frame,$percent_max_min,$up_down,$u_smooth,$prob,$prob_data,$trend,
						$trend_data,$rate_chosen,$time_stamp);
						

	$cancel_time        = new DateTime("+".(round($time_frame/10,0))." minutes");//echo $cancel_time->format('Y-m-d H:i:s');

						
	$status = "";
	$order_id = "";
	
	
	//place order
	$result = buy_sell($pairing_id,$buy_amount_bath,$buy_rate,$type);
	
	//check if order has error or not
	if(isset($result)){//stdClass Object ( [success] => 1 [order_id] => 7538467 [error] => )
		
	//########## HERE IS WHERE THE SHIT IS HAPPENING. EVERYTHING ELSE IS VALIDATION ;)######################
		if($result->success == 1){//[success] => 1
		
			//prep arrays for mysql
			$cancel_time        = json_encode($cancel_time);
			$buy_values         = json_encode($buy_values);
			$sell_values        = json_encode($sell_values);
			$statistical_values = json_encode($statistical_values);
		
			$status = "buying";//buying,cancelled,selling,complete
			$order_id = $result->order_id;
			
			$query = "INSERT INTO trades_made (order_id, cancel_time, status, sell_value, buy_values, sell_values, statistical_values)
			VALUES ('$order_id', '$cancel_time', '$status', '$sell_rate', '$buy_values', '$sell_values', '$statistical_values')";

			mysqli_query($connection,$query);
			echo "buy order placed! ";
		
		}
		
	//########### HERE THE SHIT STOPS HAPPENING ############################################################
	
		elseif(!empty($result->error)){//stdClass Object ( [success] => [order_id] => 0 [error] => Amount entered in more than your 9503.06 available balance (50000.00) )
			echo $result->error;
		}
	}else{//no internet
		echo "no internet connection";
	}
	
}//END if(isset($_POST['buy'])){	
		




//------------- success ----------------

	//======>> order id = 0 
	
		//=========>> place sell order and push DB selling


	//======>> order id != 0
	
		//=========>> push DB buying
?>
