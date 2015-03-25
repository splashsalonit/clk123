<?php require_once('templates/header.php'); ?>

<div id="container">
<h4><?php echo $message_display; ?></h4>
<?php 
	if (isset($file))
	{
		$file;
		foreach ($locations as $row) 
		{
			 $result[] = $row['locations'];
		}
		$loc_tag = array('none', 'soho', 'cr', 'gd', 'nr', 'office');
		$loc_list = $result;
		$result = array_combine($loc_tag, $loc_list);
		echo form_dropdown('locations', $result, 'none', 'id="locations"');
	} 
?>
<input type=button value="Go" onclick="goToNewPage()" />

	<h4>Active Clerks</h4>
	<table>
		<thead>
			<th>Name</th>
			<th>Passphrase</th>
			<th>Location</th>
			<th>Role</th>
		</thead>
	<?php

		if (isset($file))
		{
			echo form_open('main/clerk_process');
			$result = $file;
			
			foreach ($result as $element => $value) 
			{
				if ($value['secret_sign_on_number'] > 0)
				{
					echo form_hidden($value['till_reference'], $value['till_reference']);
					echo '<tr><td>' . $value['clerk_name'] . '</td><td>' . $value['secret_sign_on_number'] . '</td><td>' . form_dropdown('loc'.$value['till_reference'], $loc_list, $value['location_id'], 'id="item_location"') . '</td></tr>';	
				}
			}
			echo form_hidden('last_clerk', $element);
			echo form_submit('submit', 'Submit');
			echo form_close();
		}
		
	?>

</table>
</div>

<?php require_once('templates/footer.php'); ?>