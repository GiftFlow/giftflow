<h1> Messages Monthly </h1>
</br>

<div class= "row">

<div class = "span4">


<?php foreach($monthly_messages as $year=>$month) { ?>
<u1>
	<h2>
	<li>20<?php echo $year;?>: </li>
	</h2>
</br>
	
</u1>

<table class = "table table-bordered">
	<tr>
		<td> Month </td>
		<td> Number of Messages </td>
	</tr>
		<?php foreach($month as $month_number => $messages) { ?>
			<tr>
				<td>
					<?php echo $x[$month_number]; ?> 
				</td>
				<td>
					<?php echo $messages; ?>
				</td>
				
		</tr>
		<?php } ?>

</table>

<?php } ?>

</div>

<div class = "span8">
</div>

</div>