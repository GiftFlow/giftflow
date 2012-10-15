<table class='form <?php if(!empty($classes)) echo $classes; ?>' style='<?php if(!empty($style)) echo $style; ?>'>
<?php
foreach ( $form as $key=>$item )
{
?>
<tr <?php if(($key%2)==1) echo "class='odd'"; ?>>
	<td class='meta'>
		<label><?php echo $item['title']; ?></label>
	</td>
	<td class='field'>
		<?php echo $item['input']; ?>
		<p class='note'><?php if(!empty($item['note'])) echo $item['note']; ?></p>
	</td>
</tr>
<?php  }  ?>
</table>