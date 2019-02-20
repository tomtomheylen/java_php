<?php
include_once('includes/constants.php');
date_default_timezone_set("Asia/Bangkok");
	require_once( 'includes/binance-api-single_orig.php') ;
	$api = new Binance("key","key");

$t=time();
echo($t . "<br>");
echo(date("d M Y h:i:sa",$t));
$date = date("d M Y h:i:sa",$t);


$candle_times = array('3m', '5m');
foreach ($candle_times as $candle_times_value) {

	$total_simulations = 0;

	$totals_per_minute = array();	
	//$candle_times_value = '5m';
		
		$max_losses = 0;
		$max_winners = 0;
		$max_ratio = array(0,0,0,0,0,0,0,0);//ratio, losses, winners, price_difference sell, price_difference trail, boll_band_width,%stop_limit, percent sell_price
		
		$percent_sellprice = 1.003;//2r = 1.008 1.5r = 1.006
		for($r=0.996;$r>=0.96;$r-=0.002){
			$percent_stoploss = $r;
			
			$percent_sellprice += 0.003;//2r = 0.004 1.5r = 0.003
			//echo $percent_stoploss.' '.$percent_sellprice.'<br>';
		
			$boll_band_width = array('10', '15', '20', '25');//, '30', '35');
			foreach($boll_band_width as $boll_band_width_value) {
			
				//echo "<h3>".$candle_times_value." Boll band widh: ".$boll_band_width_value." Prices are based on the value of one unit eg. BTCUSDT amount is 1 bitcoin value is 7922usdt</h3>";
				$boll_signals = json_decode(file_get_contents('../cryptotrader/plot_data_json/'.$candle_times_value.'/boll_signal'.$boll_band_width_value.'.json'));
				$candles = json_decode(file_get_contents('../cryptotrader/plot_data_json/'.$candle_times_value.'/candles.json'));//timestamp,open,high,low,close
				$sma_big = json_decode(file_get_contents('../cryptotrader/plot_data_json/'.$candle_times_value.'/sma20.json'));

				$losses = 0;
				$winners = 0;

				$percent_difference = 0;


				for($x=1;$x<count($boll_signals);$x++){//start at $x=1 because first usually corrupt
					$total_simulations++;
					
					$key = array_search($boll_signals[$x][0], array_column($candles, 0));//boll_signals[x][0] -> x = array -> 0 = datestamp ||| candles, 0 -> datestamp. RETURNS ARRAY KEY
					$current = $candles[$key][1];
					$stop_limit = $current*$percent_stoploss;//0.995;
					$sell_price = $current*$percent_sellprice;//1.01;
					$max = 0;
					$reached_sell_price = 0;
					$reached_stop_limit = 0;
					for($i=$key;$i < count($candles);$i++){//loop calculates the max value untill it hits the stop_limit
						if($candles[$i][1] > $stop_limit){//loop as long price don't hit stop limit
							if($max < $candles[$i][1]){
								$max = $candles[$i][1];
							}
							if($max > $sell_price){//check if it reached the sell price. 
								$reached_sell_price = 1;
								//echo "<br><b>".$max."</b><br>";
							}
						}else{
							$reached_sell_price = 0;
							$reached_stop_limit = 1;
							break;//break loop when hitting stop_limit
						}
					}
					
					if($reached_sell_price == 1){//if you reached your percent goal //
											//if Sellprice > $max -> means that $max stopped counting when hitting the stop limit
						$winners++;
					}
					if($reached_stop_limit == 1){//if you hit your stop_limit
						$losses++;
					}
					if($reached_sell_price == 0 && $reached_stop_limit == 0){
						//echo "<b>Undefined!</b><br> Cycle ended before hitting stop_limit OR sell_price";
					}
				}//end main for loop

				if(($winners - $losses) > ($max_winners - $max_losses)){
					$max_losses = $losses;
					$max_winners = $winners;
					$max_ratio[1] = $losses;
					$max_ratio[2] = $winners;
					$max_ratio[5] = $boll_band_width_value;
					$max_ratio[6] = $percent_stoploss;
					$max_ratio[7] = $percent_sellprice;
				}
				
				
			}//end loop bollinger values
		
		}//for loop r
		echo '<h4><b>Candle sticks '.$candle_times_value.'</b></h4>';
		$percentage_wins = 0;
		$percentage_wins = @($max_ratio[2]/($max_ratio[1] + $max_ratio[2]))*100;
		echo '<hr><b>best win percentage: '.round($percentage_wins, 2).'%</b><br> losses: '.$max_ratio[1].'<br> 
		winners: '.$max_ratio[2].'<br>boll_band_width: '.$max_ratio[5].'<br>%stop_limit: '.((1-$max_ratio[6])*100).' %sell_price: '.(($max_ratio[7]-1)*100);


		echo "<br><b>Total simulations: ".$total_simulations."</b>";

	//// get the total capital per trade value ////////////////////////////////////////////////////////////////
	$capital_per_trade = 0;
	$capital = 0;
	$query = "SELECT * FROM capital WHERE id = '1'";
	$result = mysqli_query($connection,$query);
	while($row = mysqli_fetch_array($result)){ 
		$capital_per_trade = round((int)$row['capital_per_trade'], 0);
		echo "<h1>Usdt per trade : ".$capital_per_trade."<br>start capital: ".$row['start_capital']."<br>current capital: ".$row['current_capital']."<br>win rate: ".$row['perc_win']."%</h1>";
		$capital = $row['current_capital'];
	}

		
	//////////////////////////  BUY STUFF /////////////////////////////////////////////

	$buy_amount_usd                  =$capital_per_trade;//minimum 10
	$set_higher_buy_percent          = 5 ;//percent from percent profit 
	$pair                            = "BTCUSDT";//BTCUSDT  BNBBTC

	$time_frame_cancel				= 900;//seconds

	$time_between_trades = 600;//seconds

	$low_risk_boll_band_width        = $max_ratio[5];//get the appropriate boll width based on lowest risk result
	$low_risk_stop_percent           = $max_ratio[6];
	$low_risk_sell_percent           = $max_ratio[7];//(($max_ratio[7]-1)*100);
	$low_risk_buy_price              = $candles[count($candles) - 1][4];
	//set buy price little higher
	$low_risk_buy_percent            = 1+(((($max_ratio[7]-1)*100)/10000)*$set_higher_buy_percent);//last value is percent from sell percent. buy little higer than indicator price to prevent buying a dropping price
	$low_risk_recalculated_buy_price = ($low_risk_buy_price*$low_risk_buy_percent);//use STOP LIMIT ORDER(MAX 2 PER PAIR ALLOWED PER COIN) buy little higer than indicator price to prevent buying a dropping price
	//https://www.samco.in/knowledge-center/articles/what-is-a-stop-loss-limit-order/

	$buy_rate                        = $low_risk_buy_price;
	$buy_amount_coin                 = (1/$buy_rate)*$buy_amount_usd;

	$low_risk_sell_price = ($low_risk_buy_price*$low_risk_sell_percent);
	$low_risk_stop_price = ($low_risk_recalculated_buy_price*$low_risk_stop_percent);
	
	///////////////// CALCULATE 0.25R ///////////////////////////////////////
	// $R_buy_price = $low_risk_recalculated_buy_price;
	// $R_stop_loss_price = $low_risk_stop_price;
	// $R_capital = $capital;
	// $quantity = (($R_capital*0.25)/100)/($R_buy_price-$R_stop_loss_price);
	// echo "<b>quantity = ".$quantity."</b><br>";
	// echo "<b>buy = ".$R_buy_price."</b><br>";
	// echo "<b>stop = ".$R_stop_loss_price."</b><br>";
	// echo "<b>capital = ".$capital."</b><br>";
	/////////////////////////////////////////////////////////////////////////
	
	
	if($low_risk_boll_band_width != 0 && $percentage_wins > 50){//check if simulation not has errors AND if winpercent > 50
		$boll_signals = json_decode(file_get_contents('../cryptotrader/plot_data_json/'.$candle_times_value.'/boll_signal'.$low_risk_boll_band_width.'.json'));//get the right boll signals from simulation
		
		
		if($boll_signals[count($boll_signals) - 1][0] == $candles[count($candles) - 1][0]){//if timestamp from last index is same. means there is a signal right now
			//start buying'
			
			$last_timestamp = file_get_contents('time_last_trade_made.json');
			$time_difference = (time()-$last_timestamp);
			if($time_difference > $time_between_trades){// && $percentage_wins < 100< 100 to prevent hitting wall -> _|
				echo "hello";
				
				$R_buy_price = $low_risk_recalculated_buy_price;
				$R_stop_loss_price = $low_risk_stop_price;
				$R_capital = $capital;
				$quantity = (($R_capital*0.14)/100)/($R_buy_price-$R_stop_loss_price);
				
				
				$quantity = round($quantity, 6);//FOR BITCOIN. WILL NOT WORK WITHOUT ROUND
				$price = round($low_risk_recalculated_buy_price,2);//FOR USDT. WILL NOT WORK WITHOUT ROUND
				
				
				
				$time_stamp         = new DateTime();
				$cancel_time =			(time()+$time_frame_cancel);
				
				

				$query = "INSERT INTO trades (pair, order_id, cancel_time, status, quantity, sell_value, buy_value, stop_limit_value, perc_stoploss, perc_sell, type, date)
				VALUES ('$pair', '0', '$cancel_time', '$candle_times_value', '$quantity', '$low_risk_sell_price', '$price', '$low_risk_stop_price','$low_risk_stop_percent','$low_risk_sell_percent', 'PENDING', '$date' )";

				mysqli_query($connection,$query);
				
				file_put_contents('time_last_trade_made.json', time());

			}else{echo '<br>just wait a little';}
		
		} 
	}

}//end foreach[3 5 


?>
