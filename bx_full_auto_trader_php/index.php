<html>
<head>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <!--<script type="text/javascript" src="js/jquery-1.11.3.js"></script>-->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	

<title>Trade Platform</title>
<style>

body{
 background-color:black;
 }
#middle_middle h4{
	 color:yellow;
 }
#percent{
	color:black;
}
#percent_profit{
	color:black;
}
 #middle_right{
	 background-color:#6DBDD6;
 }
#top{
	height:0%;
}
#middle{
	height:100%;
	
}
#bottom{
	height:0%;
}
#middle_left{
	height:100%;
	overflow-y:scroll;
	overflow-x:hidden;
	background-color:#404040;
	color:#C1E1A6;
}
#middle_middle{
	height:100%;
	padding-top: 2%;
	
}
#middle_right{
	height:100%;
}
#middle_chart1{
	
}
#middle_chart2{
	
}
#middle_chart5{
	border:5px solid ;
}
.strategy{
	height:15%;
	border:1px solid gray;
}
.container{
	width:100%;
}
.table-dark{
	color:black;
	background-color:#6DBDD6;
	border-color:red;
	
}
.table td, .table th, .table thead{
	border-color:red;
}
.live{
	font-size:small;
	height:10%;
	overflow-y:scroll;
	overflow-x:hidden;
	background-color:#C1E1A6;
	color:#404040;

}
#live_data{
	
}
</style>

<script>
$(document).ready(function(){
		
		
	$( "#slider1" ).slider({
		range: "max",
		min:0.5,
		max:5,
		step:0.1,
		value:0.6,
		slide: function( event, ui ) {
			$( "#amount1" ).html( "<h4>"+ ui.value +"%  &nbsp;&nbsp;&nbsp; Profit Margin<h4>" );
		}
	});
	$( "#slider2" ).slider({
		range: "max",
		min:1,
		max:1440,
		step:1,
		value:200,
		slide: function( event, ui ) {
			$( "#amount2" ).html( "<h4>"+ ui.value +"  &nbsp;&nbsp;&nbsp; Minutes first row<h4>" );
		}
	});
	$( "#slider3" ).slider({
		range: "max",
		min:0,
		max:2880,
		step:10,
		value:20,
		slide: function( event, ui ) {
			$( "#amount3" ).html( "<h4>"+ ui.value +"  &nbsp;&nbsp;&nbsp; Minutes 2nd row<h4>" );
		}
	});
	$( "#amount1" ).html( "<h4>"+ $( "#slider1" ).slider( "value" ) +"% &nbsp;&nbsp;&nbsp; Profit Margin<h4>" );
	$( "#amount2" ).html( "<h4>"+ $( "#slider2" ).slider( "value" ) +" &nbsp;&nbsp;&nbsp; Minutes 1st row<h4>" );
	$( "#amount3" ).html( "<h4>"+ $( "#slider3" ).slider( "value" ) +" &nbsp;&nbsp;&nbsp; Minutes 2nd row<h4>" );
	
	

	var loop_sell = self.setInterval(function(){Loop_check_sales()},10000);
	
	function Loop_check_sales(){
		//show date and trade value in middle left
		var chart = '';
		$.ajax({
			type:'POST',
			data:'loop_check_sell=1',
			url:'loop_check_if_bought3.php',
			success:function(data) {
				
				//$("#selling_trades").append(data);
						
			}
		});
		
		$.ajax({
			type:'POST',
			data:'loop_check_sell=1',
			url:'loop_check_if_sold.php',
			success:function(data) {
				
				//$("#done_trades").append(data);
						
			}
		});

		$.ajax({
			type:'POST',
			data:'loop_check_sell=1',
			url:'update_live_trades.php',
			success:function(data) {
				
				$("#live_data").html(data);
						
			}
		});

		$.ajax({
			type:'POST',
			data:'loop_check_sell=1',
			url:'loop_cancel2.php',
			success:function(data) {
				
				//$("#done_trades").append(data);
						
			}
		});



		
	}


	
	var interval = self.setInterval(function(){LoopForever()},3000);
	
	function LoopForever(){
		//show date and trade value in middle left
		var chart = '';
		$.ajax({
			type:'POST',
			data:'list=1&minutes=20',
			url:'analyse_trading_data3.php',
			success:function(data) {
				$("#middle_left").html('');
				$("#middle_left").html(data);
						
			}
		});
	}

	$.ajax({
		type:'POST',
		data:'chart=1&minutes=22',
		dataType: 'json',
		url:'analyse_trading_data3.php',
		success:function(data) {
			//$("#middle_left").html('');
			for(i=0;i<=4;i++){
				//$("#middle_right").append(data[i]+'<br>');
				$("#1st_row").children()[2+i].innerHTML = data[i];
			}
			
					
		}
	});
	
	$.ajax({
		type:'POST',
		data:'chart=1&minutes=90',
		dataType: 'json',
		url:'analyse_trading_data3.php',
		success:function(data) {
			//$("#middle_left").html('');
			for(i=0;i<=4;i++){
				//$("#middle_right").append(data[i]+'<br>');
				$("#2nd_row").children()[2+i].innerHTML = data[i];
			}
			
					
		}
	});
	$.ajax({
		type:'POST',
		data:'chart=1&minutes=360',
		dataType: 'json',
		url:'analyse_trading_data3.php',
		success:function(data) {
			//$("#middle_left").html('');
			for(i=0;i<=4;i++){
				//$("#middle_right").append(data[i]+'<br>');
				$("#3th_row").children()[2+i].innerHTML = data[i];
			}
			
					
		}
	});
	$.ajax({
		type:'POST',
		data:'chart=1&minutes=1440',
		dataType: 'json',
		url:'analyse_trading_data3.php',
		success:function(data) {
			//$("#middle_left").html('');
			for(i=0;i<=4;i++){
				//$("#middle_right").append(data[i]+'<br>');
				$("#4th_row").children()[2+i].innerHTML = data[i];
			}
			
					
		}
	});

	//############################### probability ################
	$.ajax({
		type:'POST',
		data:'chart=1&minutes=10',
		dataType: 'json',
		url:'analyse_trading_data3.php',
		success:function(data) {
			//$("#middle_left").html('');
			for(i=0;i<=4;i++){
				//$("#middle_right").append(data[i]+'<br>');
				$("#4th_row").children()[2+i].innerHTML = data[i];
			}
			//$("#middle_right").append("<br><br>"+data);
			//alert("hello");
					
		}
	});
	
});


</script>
<?php
	include_once('ajax_analyse_trading_data4.php');
	include_once('ajax_make_trades.php');
?>
</head>
<body>
<div class="container">
	<div class="row" id="top">


	</div>

	<div class="row" id="middle">
	
		
		<div class="col-sm-2" id="middle_left">
			
		</div>
		<div class="col-sm-7" id="middle_middle">
			<div class="row">
				<div class="col-sm-9">
				<div class="panel panel-default">
					<table class="table table-bordered table-dark">
						<thead>
							<tr>
								<th>Time frame</th>
								<th>Avg. val</th>
								<th>Max val</th>
								<th>Min val</th>
								<th>% max-min</th>
							</tr>
						</thead>
						<tbody>
							<tr id="1_row_1">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr id="2_row_1">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							
						</tbody>  
					</table>
				</div>
				</div>
				<div class="col-sm-3" style="">
					<div class="panel panel-default">
					<div style="border:1px solid gray; background-color:white;margin-righ:2%;">
						<div><center><h4><b id="percent_profit">Percent Profit</b></h4></center></div>
						<div id="percent"></center></div>
					</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-4">
					<h4><b>Up/down avarages and trend of 4 blocks</b></h4>
				</div>
				<div class="col-sm-4">
					<h4><b>Probability 10 blocks</b></h4>
				</div>
				<div class="col-sm-4">
					<h4><b>Avg.trend 10 blocks</b></h4>
				</div>
			</div>
			<div class="row" id="2nd-row">
				<div class="col-sm-4">
					<div class="panel panel-default">
					<table class="table table-bordered table-dark">
						<thead>
							<tr>
								<th>Time frame</th>
								<th>UP/<br>DOWN</th>
								<th>U/∩/smooth</th>
							</tr>
						</thead>
						<tbody>
							<tr id="1_row_2">
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr id="2_row_2">
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
				<div class="col-sm-4">
				<div class="panel panel-default">
					<table class="table table-bordered table-dark">
						<thead>
							<tr>
								<th></th>
								<th>Prob<br>.</th>
								<th>Data</th>
							</tr>
						</thead>
						<tbody>
							<tr id="1_row_3">
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr id="2_row_3">
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
				<div class="col-sm-4">
				<div class="panel panel-default">
					<table class="table table-bordered table-dark">
						<thead>
							<tr>
								<th></th>
								<th>Trend<br>.</th>
								<th>+/-</th>
							</tr>
						</thead>
						<tbody>
							<tr id="1_row_4">
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr id="2_row_4">
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
			</div>
			<div class="row" id="live_data" style="padding:1.5%;">
			
			</div>
		</div>
			
		<div class="col-sm-3" id="middle_right" style="padding:1.5%;">
			<div class="row">
				<div id="amount1"></div>
				<div id="slider1"></div>
				<div id="amount2"></div>
				<div id="slider2"></div>
				<div id="amount3"></div>
				<div id="slider3"></div>
				<div id="amount4"></div>
				<div id="slider4"></div>
				<div id="amount5"></div>
				<div id="slider5"></div>
				<br>
				<div><button id="update_tables"><b>Update Tables</b></button><b id="loading"> Loading...</b></div>
				
			</div>
			<div class="row"  style="padding:5%;margin-top:10%;">
			(based on 1/10 block from 1st row )
				<table>
					<tr>
						<td><b>Min</b></td>
						<td><b>Avg</b></td>
						<td><b>Max</b></td>
						<td><b>Moving-low down</b></td>
					</tr>
					<tr>
						<td><button class="trade_vals" id="min_val"><b>Min</b></button></td>
						<td><button class="trade_vals" id="med_val"><b>Med</b></button></td>
						<td><button class="trade_vals" id="max_val"><b>Max</b></button></td>
						<td><button class="trade_vals" id="MAD"><b>MAD</b></button></td>
					</tr>
				</table>
				<div><button id="buy"><b>Buy</b></button></div>
				<div><button id="auto"><b>Auto On/Off</b></button></div>
			</div>
		</div>

	
	</div>
	<!--
	<div class="row" id="bottom">
	
	</div>
	-->
	
</div>
</body>
</html>
