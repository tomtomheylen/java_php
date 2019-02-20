<?php


?>
<script>
$(document).ready(function(){


//###############Update tables on button click ############


	$('#loading').hide();//hide loading element
	
	$("#update_tables").click(function(){
		var val_slider1 = $( "#slider1" ).slider( "value" );//percent
		var val_slider2 = $( "#slider2" ).slider( "value" );//1st row minutes
		var val_slider3 = $( "#slider3" ).slider( "value" );//2nd row minutes
		val_sliders = [val_slider2,val_slider3];
		
		//============== set minutes ================
		$("#percent").html("<center><h1><b>"+val_slider1+"</b></h1>");
		for(i=1;i<=2;i++){
			for(x=1;x<=2;x++){
			$("#"+x+"_row_"+i).children()[0].innerHTML = val_sliders[x-1];
			
			}
		}
		
		//================set probability trend 10 blocks =============
		$.ajax({
			type:'POST',
			data:'probability_trend=1&minutes='+val_slider2,
			dataType: 'json',
			url:'probability.php',
			success:function(data) {
				$("#1_row_4").children()[1].innerHTML = data[0];	
				$("#1_row_4").children()[2].innerHTML = data[2];
			}
		});
		$.ajax({
			type:'POST',
			data:'probability_trend=1&minutes='+val_slider3,
			dataType: 'json',
			url:'probability.php',
			success:function(data) {
				$("#2_row_4").children()[1].innerHTML = data[0];
				$("#2_row_4").children()[2].innerHTML = data[2];	
			}
		});
		
		//================ set probability ================
		$.ajax({
			type:'POST',
			data:'probability=1&minutes='+val_slider2+'&percent='+val_slider1,
			dataType: 'json',
			url:'probability.php',
			success:function(data) {
				$("#1_row_3").children()[1].innerHTML = data[0];
				$("#1_row_3").children()[2].innerHTML = data[1];	
			}
		});
		$.ajax({
			type:'POST',
			data:'probability=1&minutes='+val_slider3+'&percent='+val_slider1,
			dataType: 'json',
			url:'probability.php',
			
			beforeSend: function(){
				$('#loading').show();
			},
			complete: function(){
				$('#loading').hide();
			},
			
			success:function(data) {
				$("#2_row_3").children()[1].innerHTML = data[0];
				$("#2_row_3").children()[2].innerHTML = data[1];	
			}
		});
		
		//============== set "avg. val", "max val", "min val", "UP/DOWN" , "U/∩/smooth" =================
		$.ajax({//1st row
			type:'POST',
			data:'chart=1&minutes='+val_slider2,
			dataType: 'json',
			url:'analyse_trading_data3.php',
			success:function(data) {
				//Avg.val
				$("#1_row_1").children()[1].innerHTML = data[0];
				//Max.val
				$("#1_row_1").children()[2].innerHTML = data[1];
				//Min.val
				$("#1_row_1").children()[3].innerHTML = data[2];
				//UP/DOWN
				$("#1_row_2").children()[1].innerHTML = data[3];
				//U/∩/smooth
				$("#1_row_2").children()[2].innerHTML = data[4];
				
				//%max-min
				if(data[3] == "UP"){
					percentChange = (1 - data[2] / data[1]) * 100;
					$("#1_row_1").children()[4].innerHTML = percentChange;
				}else{
					percentChange = (1 - data[1] / data[2]) * 100;
					$("#1_row_1").children()[4].innerHTML = percentChange;
				}
			}
		});
		$.ajax({//2nd row
			type:'POST',
			data:'chart=1&minutes='+val_slider3,
			dataType: 'json',
			url:'analyse_trading_data3.php',
			success:function(data) {
				//Avg.val
				$("#2_row_1").children()[1].innerHTML = data[0];
				//Max.val
				$("#2_row_1").children()[2].innerHTML = data[1];
				//Min.val
				$("#2_row_1").children()[3].innerHTML = data[2];
				//UP/DOWN
				$("#2_row_2").children()[1].innerHTML = data[3];
				//U/∩/smooth
				$("#2_row_2").children()[2].innerHTML = data[4];
				
				//%max-min
				if(data[3] == "UP"){
					percentChange = (1 - data[2] / data[1]) * 100;
					$("#2_row_1").children()[4].innerHTML = percentChange;
				}else{
					percentChange = (1 - data[1] / data[2]) * 100;
					$("#2_row_1").children()[4].innerHTML = percentChange;
				}
				
			}
		});
		
		//===================== buy values ===========================
		ten_percent = val_slider2/10;//last block
		twenty_percent = ten_percent*2;
		last_block_minimum = '';
		$.ajax({//min medium max from 1st row
			type:'POST',
			data:'ten_percent=1&minutes='+ten_percent,
			dataType: 'json',
			url:'analyse_trading_data3.php',
			success:function(data) {
			if(data[1] >= 1000){data[1] = data[1].toFixed(0);}
				last_block_minimum = data[3];
				$("#min_val").html(data[3]);
				$("#med_val").html(data[1]);
				$("#max_val").html(data[2]);
			}
		});
		
		
		$.ajax({//2nd last block
			type:'POST',
			data:'ten_percent=1&minutes='+twenty_percent+'&start='+ten_percent,
			dataType: 'json',
			url:'analyse_trading_data3.php',
			success:function(data) {
				sec_last_minimum = data[3];
				difference = Math.abs(last_block_minimum - sec_last_minimum);
				
				val_moving_min_down = parseFloat(last_block_minimum)-difference ;//####### NOT VERIFIED ########
				//alert(last_block_minimum+ ' - ' +sec_last_minimum);
				$("#MAD").html(val_moving_min_down);
			}
		});
		
	});//end click update_tables
	
});





</script>