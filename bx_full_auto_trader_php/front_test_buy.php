<?php


?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">


<script>

$(document).ready(function(){
	var interval = 0;
	var count = 0;
	function LoopForever(){
		$.ajax({
			type:'POST',
			data:'val1=1',
			url:'back_test_buy2.php',
			success:function(data) {
				//alert(data);
				$("#vals").prepend("<div>"+data+"</div>" + count);
			}
				
						
			
		});
		count++;
	}
	
	$("#on").click(function(){
		interval = self.setInterval(function(){LoopForever()},10000);
	});
	$("#off").click(function(){
		 clearInterval(interval);
	});
	
	
});
</script>

</head>
<body>
<div class="row">
	<div class="col-sm-1">
	
	</div>

	<div class="col-sm-10" id="vals">
	
	</div>
	<div class="col-sm-1">
		<button id="on">ON</button>
		<button id="off">OFF</button>
	</div>

</div>
</body>