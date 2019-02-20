<?php

?>
<script>

$(document).ready(function(){
	
	rate = "NaN";
	rate_chosen = '';
	$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});

	$("#update_tables").click(function(){
		$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});
		rate = "NaN";
	});
	
	$("#min_val").click(function(){
		if(!isNaN(Math.abs($(this).html()))){
			$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});
			$(this).css({'background-color' : '#404040', 'color': '#C1E1A6'});
			rate = Math.abs($(this).html());
			rate_chosen = "Min";
		}
	});
	$("#med_val").click(function(){
		if(!isNaN(Math.abs($(this).html()))){
			$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});
			$(this).css({'background-color' : '#404040', 'color': '#C1E1A6'});
			rate = Math.abs($(this).html());
			rate_chosen = "Avg";
		}
	});
	$("#max_val").click(function(){
		if(!isNaN(Math.abs($(this).html()))){
			$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});
			$(this).css({'background-color' : '#404040', 'color': '#C1E1A6'});
			rate = Math.abs($(this).html());
			rate_chosen = "Max";
		}
	});
	$("#MAD").click(function(){
		if(!isNaN(Math.abs($(this).html()))){
			$(".trade_vals").css({'background-color' : '#efefef', 'color': 'black'});
			$(this).css({'background-color' : '#404040', 'color': '#C1E1A6'});
			rate = Math.abs($(this).html());
			rate_chosen = "M-l-d";
		}
	});
	
	
	$("#buy").click(function(){
		time_frame = $("#1_row_1 td:nth-child(1)").html();
		percent_max_min = $("#1_row_1 td:nth-child(5)").html();
		percent = Math.abs($("#percent").find("b").html());
		up_down = $("#1_row_2 td:nth-child(2)").html(); 
		u_smooth = $("#1_row_2 td:nth-child(3)").html();
		prob = $("#1_row_3 td:nth-child(2)").html();
		prob_data = $("#1_row_3 td:nth-child(3)").html();
		trend = $("#1_row_4 td:nth-child(2)").html();
		trend_data= $("#1_row_4 td:nth-child(3)").html();
		
		
		if(!isNaN(rate)){
			$.ajax({//buy stuff 555
				type:'POST',
				data:'buy=1&buy_rate='+rate+
				'&time_frame='+time_frame+
				'&percent_max_min='+percent_max_min+
				'&up_down='+up_down+
				'&u_smooth='+u_smooth+
				'&prob='+prob+
				'&prob_data='+prob_data+
				'&trend='+trend+
				'&trend_data='+encodeURIComponent(trend_data)+
				'&rate_chosen='+rate_chosen+
				'&percent='+percent,
				//dataType: 'json',
				url:'make_trades2.php',
				success:function(data) {
					alert(data);
					$("#buying_trades").append(data);
				}
			});
		}//isNaN()
		
		
	});
	
	$("#auto").click(function(){
		alert("he");
		
	});
	
	function check_is_bought(){//check_is_bought or cancel if time frame has expired
		
	}
	
	
});

</script>