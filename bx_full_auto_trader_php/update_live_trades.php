<?php
include_once('includes/constants.php');

	echo'
	<div class="row">
		<div class="col-sm-6">
		
		<h4>Buying</h4>
		<div class="panel panel-default">
			<table class="table live">
				<thead>
					<tr>
						<th>Order Id</th>
						<th>Buy</th>
						<th>Sell</th>
						<th>% Profit</th>
					</tr>
				</thead>
				<tbody>
	';
	$query = 'SELECT * FROM trades_made WHERE status = "buying"';
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>".$row['order_id']."</td>";
		echo "<td>".json_decode($row['buy_values'])[1]."</td>";
		echo "<td>".json_decode($row['sell_values'])[2]."</td>";
		echo "<td>".json_decode($row['sell_values'])[0]."</td>";
		echo "</tr>";
	}
	echo '
				</tbody>
			</table>
			</div>
		</div>
		<div class="col-sm-6">
			<h4>Selling</h4>
			<div class="panel panel-default">
			<table class="table live">
				<thead>
					<tr>
						<th>Order_id</th>
						<th>Amount</th>
						<th>Sell</th>
					</tr>
				</thead>
				<tbody>
	';
	$query = 'SELECT * FROM trades_made WHERE status = "selling"';
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>".$row['order_id']."</td>";
		echo "<td>".json_decode($row['sell_values'])[1]."</td>";
		echo "<td>".json_decode($row['sell_values'])[2]."</td>";
		echo "</tr>";
	}
	echo '			
				</tbody>
			</table>
			</div>
		</div>
		
	</div>

	';
	
	echo'
	<div class="row">
		<div class="col-sm-6">
		<h4>Complete</h4>
		<div class="panel panel-default">
			<table class="table live">
				<thead>
					<tr>
						<th>Buy</th>
						<th>Sell</th>
						<th>% Profit</th>
						<th>profit amount</th>
					</tr>
				</thead>
				<tbody>
	';
	$query = 'SELECT * FROM trades_made WHERE status = "complete"';
	$result = mysqli_query($connection, $query);
	$total_amount = 0;
	while($row = mysqli_fetch_array($result)){
		
		echo "<tr>";
		echo "<td>".json_decode($row['buy_values'])[1]."</td>";
		echo "<td>".json_decode($row['sell_values'])[2]."</td>";
		echo "<td>".json_decode($row['sell_values'])[0]."</td>";
		echo "<td>".json_decode($row['statistical_values'])[0]."</td>";
		echo "</tr>";
		$total_amount += json_decode($row['statistical_values'])[0];
	}
	echo "<tr><td><b>Total</b></td><td></td><td></td><td><b>".$total_amount."</b></td>";
	echo '
				</tbody>
			</table>
			</div>
		</div>
		<div class="col-sm-6">
			<h4>Canceled</h4>
			<div class="panel panel-default">
			<table class="table live">
				<thead>
					<tr>
						<th>Order_id</th>
						<th>Amount</th>
						<th>Rate</th>
					</tr>
				</thead>
				<tbody>
	';
	$query = 'SELECT * FROM trades_made WHERE status = "canceled"';
	$result = mysqli_query($connection, $query);
	while($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>".$row['order_id']."</td>";
		echo "<td>".json_decode($row['sell_values'])[1]."</td>";
		echo "<td>".json_decode($row['sell_values'])[2]."</td>";
		echo "</tr>";
	}
	echo '			
				</tbody>
			</table>
			</div>
		</div>
		
	</div>

	';

?>
