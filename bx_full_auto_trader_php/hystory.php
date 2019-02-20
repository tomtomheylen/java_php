<?php
function bx(){
	if($ch = curl_init ()){
		// $data['pairing'] = 1;//btc/thb
		// $data['amount'] = 0;
		// $data['rate'] = 0;
		
		$startdatetime = new DateTime();
		$startdatetime->modify('-2 day');
		
        $stopdatetime= new DateTime();
		
		
		$data['currency'] = 'THB';
		$data['start_date'] = $startdatetime->format('Y-m-d H:i:s');
		$data['end_date'] = $stopdatetime->format('Y-m-d H:i:s');
		//$data['type'] = "trade";
		$data['key'] = '570025c31e30';
		$mt = explode(' ', microtime());
		$nonce = $mt[1].substr($mt[0], 2, 6);
		$data['nonce'] = $nonce;
		$data['signature'] = hash('sha256', '570025c31e30'.$nonce.'f3fb8b6a7df6');
		// if($this->twofa != ''){
			// $data['twofa'] = $this->twofa;
		// }
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://bx.in.th/api/history/');
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); 
		curl_setopt ( $ch, CURLOPT_POST, count($data));
		curl_setopt ( $ch, CURLOPT_POSTFIELDS,$data);
		
		$str = curl_exec ( $ch );
		
		curl_close ( $ch );
		//return json_decode($str);
		$str= json_decode($str);
	
		return $str;

	}

}
echo "<body style='background-color:black;color:#6DBDD6;'>";
$results = bx()->transactions;
echo bx()->success."<br><br>";
for($i=0;$i<19;$i++){
	print_r($results[$i]);
	echo '<br>';
}


//$current_time = new DateTime();
$datetime = new DateTime();
$datetime->modify('-1 day');
echo "<br><br>";
echo $datetime->format('Y-m-d H:i:s');

echo '</div>';
?>