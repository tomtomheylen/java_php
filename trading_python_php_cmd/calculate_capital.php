<?php
include_once('includes/constants.php');

date_default_timezone_set("Asia/Bangkok");

require_once('includes/binance-api-single_orig.php');
$api = new Binance("key","key");



$url = "https://api.binance.com/api/v1/trades?symbol=BTCUSDT&limit=1";
$contents = json_decode(file_get_contents($url));
//var_dump($contents);
$btc_price = (array)$contents[0];
$btc_price = $btc_price['price'];
$total_capital = 0;

$coin1 = 'USDT';
$coin2 = 'BTC';
$coin1_free = 0;
$coin1_locked = 0;
$coin2_free = 0;
$coin2_locked = 0;

$account = $api->account();
//var_dump($account);
//for($i=0;$i<count($account);$i++){
foreach($account as $asset){
	//var_dump($account[0][8][$i]);
	//echo $asset."<br>";
	if(is_array($asset)){
		foreach($asset as $value){
			if($value['asset'] == $coin1 ){
				//echo $value['asset']. $value['free']."<br>";//locked
				$coin1_free = $value['free'];
				$coin1_locked = $value['locked'];
				//echo $value['locked']."<br><br>";
			}
			if($value['asset'] == $coin2){
				//echo $value['asset']. $value['free']."<br>";//locked
				$coin2_free = $value['free'];
				$coin2_locked = $value['locked'];
				//echo $value['locked']."<br><br>";
			}
		}
		
	}
	
}
if($btc_price != 0){
	$coin1 = $coin1_free+$coin1_locked;
	$coin2 = ($coin2_free+$coin2_locked)*$btc_price;
	$total_capital = $coin1+$coin2;
}

$capital_per_trade = ($total_capital/100)*23;

if(isset($capital_per_trade) && !empty($capital_per_trade && $capital_per_trade != 0 ) ){
	


	$sql = "UPDATE capital SET current_capital = '$total_capital' WHERE id= 1";
	mysqli_query($connection,$sql);
	$sql = "UPDATE capital SET capital_per_trade ='$capital_per_trade' WHERE id= 1";
	mysqli_query($connection,$sql);
}
//calculate win/loss percent
$wins = 0;
$losses = 0;

$query = "SELECT * FROM trades WHERE type = 'SOLD'";
$result = mysqli_query($connection, $query);

while($row = mysqli_fetch_array($result)){
	$wins++;
	
}
$query = "SELECT * FROM trades WHERE type = 'STOPLOSS'";
$result = mysqli_query($connection, $query);

while($row = mysqli_fetch_array($result)){
	$losses++;
	
}

echo "<br><b>losses: ".$losses." <-> wins: ".$wins."</b><br>";

$percent_wins = ($wins/($losses + $wins))*100;
//echo "<br> percent_wins: ".$percent_wins;


$sql = "UPDATE capital SET perc_win = '$percent_wins' WHERE id= 1";
mysqli_query($connection,$sql);








?>