<html>
<head>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="js/plotly.js"></script>

<title>Crypto Trader</title>
<script>
$(document).ready(function(){
	
	var start_stop = true;
	
	

	var loop_make_trades = self.setInterval(function(){Loop_make_trades()},30012);

	function Loop_make_trades(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'make_trades.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	var loop_cancel_coins = self.setInterval(function(){Loop_cancel_coins()},300000);

	function Loop_cancel_coins(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'cancel_coins.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	var loop_sell_coins = self.setInterval(function(){Loop_sell_coins()},6000);

	function Loop_sell_coins(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'sell_coins.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	var loop_buy_coins = self.setInterval(function(){Loop_buy_coins()},6000);

	function Loop_buy_coins(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'buy_coins.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	
	var loop_check_sold = self.setInterval(function(){Loop_check_sold()},6000);

	function Loop_check_sold(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'check_sold.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}
	
	
	
	var loop_check_stoploss = self.setInterval(function(){Loop_check_stoploss()},6000);

	function Loop_check_stoploss(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'check_stoploss.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}
	
	
	
	var loop_calculate_capital = self.setInterval(function(){Loop_calculate_capital()},10000);

	function Loop_calculate_capital(){
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'calculate_capital.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	var loop_get_current_prices = self.setInterval(function(){get_current_prices()},5000);

	function get_current_prices(){
		
		if(start_stop == true){
		
			$.ajax({
				type:'POST',
				data:'hi',
				url:'get_current_prices.php',
				success:function(data) {
					  
					$('#middle_left').prepend(data);
							
				}
			});
		}

	}

	
	$("#start").click(function(){
		start_stop = true;
		$(this).css('background-color', 'green');
		$("#stop").css('background-color', '#efefef');
	});
	$("#stop").click(function(){
		start_stop = false;
		$(this).css('background-color', 'red');
		$("#start").css('background-color', '#efefef');
		
	});
	
});//end document ready



</script>
<style>
.row{
	border:1px solid;
}
#main{
	margin-left:2%;
	margin-right:2%;
}
#middle{
	
}
.col-sm-4{
	border:1px solid;
	height:90%;
}
.btn { background-color: #efefef; }
#start{ background-color: green;}
#middle_left{overflow: auto;}
</style>

</head>
<body>
<div id="main">
	<div class="row">
		<button id="start" class="btn">Start</button><button id="stop" class="btn">Stop</button>
	</div>
	<div class="row" id="middle">
		<div class="col-sm-4" id="middle_left">
			hi
		</div>
		<div class="col-sm-4">
			hi 
		</div>
		<div class="col-sm-4">
			hi
		</div>
	</div>
	<div class="row">

	</div>
</div>


</body>

</html>