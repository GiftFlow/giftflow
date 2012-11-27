<h1> Monthly Gifts and Needs </h1>
</br>

<div class= "row">

<div class = "span4">


<?php foreach($gifts_monthly as $year=>$month) { ?>
<u1>
	<h2>
	<li>20<?php echo $year;?>: </li>
	</h2>
</br>
	
</u1>

<table class = "table table-bordered">
	<tr>
		<td> Month </td>
		<td> Gifts </td>
		<td> Needs </td>
	</tr>


		
		<?php foreach($month as $month_number => $gifts) { ?>
		<tr>
				<td>
					<?php echo $x[$month_number]; ?> 
				</td>
				<td>
					<?php echo $gifts; ?>
				</td>
				<td>
					<?php echo $needs_monthly[$year][$month_number]; ?>
				</td>
		</tr>
		<?php } ?>

		


		

</table>

<?php } ?>

</div>

<div class = "span8">
</div>

</div>