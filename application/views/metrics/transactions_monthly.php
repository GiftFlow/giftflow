<h1>Types of Transactions Every Month</h1>
</br>

<div class= "row">

<div class = "span4">


<?php foreach($new_transactions_monthly as $year=>$month) { ?>
<u1>
	<h2>
	<li>20<?php echo $year;?>: </li>
	</h2>
</br>
	
</u1>

<table class = "table table-bordered">
	<tr>
		<td> Month </td>
		<td> New </td>
		<td> Completed </td>
		<td> Active </td>
		<td> Cancelled </td>
		<td> Declined </td>
	</tr>


		
		<?php foreach($month as $month_number => $new_transactions) { ?>
		<tr>
				<td>
					<?php echo $x[$month_number]; ?> 
				</td>
				<td>
					<?php echo $new_transactions; ?>
				</td>
				<td>
					<?php echo $completed_transactions_monthly[$year][$month_number]; ?>
				</td>
				<td>
					<?php echo $active_transactions_monthly[$year][$month_number]; ?>
				</td>
				<td>
					<?php echo $cancelled_transactions_monthly[$year][$month_number]; ?>
				</td>
				<td>
					<?php echo $declined_transactions_monthly[$year][$month_number]; ?>
				</td>
		</tr>
		<?php } ?>

		


		

</table>

<?php } ?>

</div>

<div class = "span8">
</div>