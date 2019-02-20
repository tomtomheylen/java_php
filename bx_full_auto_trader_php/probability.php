<?php

include_once('includes/constants.php');

if(isset($_POST['probability_trend'])){
	$minutes = $_POST['minutes'];
	echo json_encode(get_trend($minutes));
	
}
if(isset($_POST['probability'])){
	$minutes = $_POST['minutes'];
	$percent = $_POST['percent'];
	echo json_encode(probability_pos_trades_10_blocks($minutes,$percent));
	
}
//data:'probability=1&minutes='+val_slider3+'&percent='+val_slider1,

if(isset($_POST['chart'])){
	$result = find_trend(0,$_POST['minutes'],1);
	echo json_encode($result);
}

//support function. RETURNS arrays of RAW data. array([0]trade_id, [1]trade_date, [2]rate, [3]trade_type)
function get_trading_data($min_start = 0,$min_stop = 5){//VERIFIED
	
	$trade_id = array(); $trade_date = array(); $rate = array(); $trade_type = array();
	
	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	$query = 'SELECT * FROM trades_thb_btc WHERE trade_date BETWEEN NOW() - INTERVAL '.$min_stop.' MINUTE  AND NOW() - INTERVAL '.$min_start.' MINUTE ORDER BY trade_date DESC';
    $result = mysqli_query($connection, $query);
    while($row = mysqli_fetch_array($result)){
        
		//echo $row['id']." ".$row['trade_date']." ".$row['rate']." ".$row['trade_type']." <br>";
		array_push($trade_id, $row['id']);
		array_push($trade_date, $row['trade_date']);
		array_push($rate, $row['rate']);
		array_push($trade_type, $row['trade_type']);
		
		//print_r($rate);
		
		
             
	}
	return array(
		$trade_id,
		$trade_date,
		$rate,
		$trade_type		
	);
}

//support function for get_trend() //RETURNS (array()) 10 average blocks, oldest first //verified
function get_10_avg_blocks($minutes = 10){//VERIFIED//
	$data = array();
	$_10 = $minutes/10;
	$_10_avg_blocks = array();
	for($i=0;$i<$minutes;$i += $_10){
		//echo $i."<br>";
		$last = $i+$_10;
		array_push($data, get_trading_data($i,$last)[2]);//[2] = rate [1] = trade_date
		
	}
	foreach ($data as $value) {
		//check data
		//foreach ($value as $result) { echo $result."<br>";}echo "<br>";
		
		$average = array_sum($value) / count($value);
		array_push($_10_avg_blocks, $average);
	}
	//print_r($_10_avg_blocks);
	//echo "<br><br>";
	return array_reverse($_10_avg_blocks);//change from newest first to oldest first
}

//gets the trend of consecutive 10 time blocks
//RETURNS -> (int) value between 10 and 190. 100 is treshold, 190 = consecutive increment, 10 = consecutive decrement
function get_trend($minutes = 10){//VERIFIED compares if next block is < || > than current block and add them -1-1+1-1+1+1,...
	$result = 10;
	//print_r(get_10_avg_blocks($minutes));// returns oldest block first
	$data = get_10_avg_blocks($minutes);
	$U_D = array();
	for($i=0;$i<=9;$i++){
		//echo "<br>". current($data) . "<br>";
		$current = current($data);
		$next = next($data);
		if($current < $next){
			//echo $current.' < '.$next.' +1<br>';
			array_push($U_D, "+");
			$result++;
		}elseif($current > $next){
			$result--;
			//echo $current.' > '.$next.' -1<br>';
			array_push($U_D, "-");
		}
		
		

	}
	//echo "<br> result: ".$result."<br>startval = 10";
	//echo "<br>% =".floor(($result/10)*100);
	return array(
		floor(($result/10)*100),
		$data,
		$U_D
	);

	
}

//NOT USED !!gets the trend of all consecutive rates
function get_trend_up_down(){//!! oldest first NOT VERIFIED/NOT USED !! //compares if next value is < || > than current value and add them -1-1+1-1+1+1,...
	//for each value that is > than previous, +1
	//for each value that is < than previous, -1
	//first value is the array lenght
	
	//get the data
	$data = get_trading_data(0,1)[2];//2 is rate
	print_r($data);
	$start_val = count($data);
	$result = $start_val;
	$last_val = $data[0];
	echo "<br>";
	for($i=2;$i<=count($data);$i++){
		//echo "<br>". current($data) . "<br>";
		$current = current($data);
		$next = next($data);
		if($current < $next){
			echo $current.' < '.$next.' +1<br>';
			
			$result++;
		}elseif($current > $next){
			$result--;
			echo $current.' > '.$next.' -1<br>';
		}
		
		

	}
	/*
	foreach ($data as $value) {
		if($value >= $result){
			$result++;
		}else{
			$result--;
		}
	}
	*/
	echo "<br> lenght: ".$start_val."<br> result: ".$result;
	echo "<br>% =".floor(($result/$start_val)*100);

}

//Possible trades over all data. RETURNS (int) amount of possible trades
//support function for "probability_pos_trades_10_blocks"
function possible_trades($start_time = 0, $stop_time = 10,$percent = 1.5){//VERIFIED
	$get_data = get_trading_data($start_time,$stop_time)[2];//newest values first
	//$data = $get_data;
	$data = array_reverse($get_data);//oldest first
	//print_r($data);
	//echo "<br><br>";
	//print_r($data);
	
	$possiblilities = 0;
	$possible = 0;
	for($i=0;$i<count($data);$i++){
		$treshold = $data[$i]*(1+($percent/100));
		
		for($x=$i;$x<count($data);$x++){
			//echo "<br> i = ".$i." x = ".$x;
			//echo ' current value = ['.$i.']'.$data[$i].' found value = ['.$x.']'.$data[$x];
			if($data[$x]>= $treshold){
				$possible = 1;
				//echo " possible";
			}

			
		}
		//echo "<br>";
		if($possible == 1){//limit 1 result for every value
			$possiblilities++;
			//echo "<br>possible <br>";
			
		}
		$possible = 0;
		//echo $data[$i]."<br>";
	}
	//echo $possiblilities;
	return $possiblilities;
	//print_r($data);
}

//RETURNS probability array([0] possibible trades between 0 and 10 blocks,[1]arary with values for each block 
function probability_pos_trades_10_blocks($minutes,$percent){//VERIFIED
	$data = array();
	$_10 = $minutes/10;
	$_10_avg_blocks = array();
	for($i=0;$i<$minutes;$i += $_10){
		//echo $i."<br>";
		$last = $i+$_10;
		array_push($data, possible_trades($i,$last,$percent));//[2] = rate [1] = trade_date
		
	}
	
	$total_pos_neg=0;
	foreach ($data as $value) {
		//echo $value."<br>";
		if($value>0){
			$total_pos_neg++;
		}
		
	}
	//
	return array(
	$total_pos_neg,
	$data
	);
}



 /*


//----------------------------DESCRIPTION----------------------------
$timespan = 15;//minutes
$percent = 0.6;//percent profit

//############### probability_pos_trades_10_blocks #############
echo"<h2>Probability</h2>
	<u>Description</u><br><br>
	RETURNS probability array([0] possibible trades between 0 and 10 blocks,[1]arary with values for each block<br><br>
";
echo "<b>probability_pos_trades_10_blocks(timespan,%)[0] returns:</b><br>";
echo probability_pos_trades_10_blocks($timespan,$percent)[0];
echo "<br><br>";
echo "<b>probability_pos_trades_10_blocks(timespan,%)[1] returns:</b><br>";
print_r(probability_pos_trades_10_blocks($timespan,$percent)[1]);
echo "<br><br>";
	
	echo "first value is oldest block, last value is youngest block<br><br>";
	foreach (probability_pos_trades_10_blocks($timespan,$percent)[1] as $value) {
		echo $value. " - ";
		
		
	}


//####################get_trend######

echo"<h2>Trend</h2>
	<u>Description</u><br><br>
	gets the trend of consecutive 10 time blocks averages
	RETURNS -> (int) value between 10 and 190. 100 is treshold, 190 = consecutive increment, 10 = consecutive decrement
";

echo "<br><br><b>get_trend(timespan)[0]</b> returns:";
echo "<br>".get_trend($timespan)[0];

echo "<br><br><b>get_trend(timespan)[1]</b> returns:<br>";

print_r(get_trend($timespan)[1]);

echo "<br><br><b>get_trend(timespan)[2]</b> returns:<br>";

print_r(get_trend($timespan)[2]);


  */
?>

















