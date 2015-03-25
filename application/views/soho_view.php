<?php require_once('templates/header.php'); ?>

<div id="container">
<h4><?php echo $message_display; ?></h4>

	<h4>Soho Rota</h4>
	
	<?php echo form_open('rota/process_rota') ?>

		<?php 

	// reserved space for data preperation



			
	////////////////////////////////////////////////////////

			

// presets for the droop down times
			for ($i = 0; $i < 24; $i++)
			{
				for ($j = 0; $j <= 30; $j=$j+30)
				{
					$times[str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT)] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT);
				}
			}


			$clerk_No = count($clerk);

			$this->table->set_caption('Edit Soho Rota');

/////////// This Week data Preperation ////////////////
			$this->table->set_heading($date_this);
				
			for ($i = 0; $i < $clerk_No; $i++)
			{
				for ($j = 0; $j < 7; $j++)
				{
					$this_data[$i][$j] = 

						form_hidden('clerk_id'.$i, $clerk[$i]['id']).
						$clerk[$i]['name'].$clerk[$j]['test'].'<br>'. // clerk names in an array
						form_hidden('date_t'.$j, $date_this[$j]).
						form_label('Location: ', 'label').form_dropdown('t,0,'.$i.','.$j, $loc_lst, $clerk[$j]['location_id']).'<br>'.
						form_label('Time in: ', 'label').form_dropdown('t,1,'.$i.','.$j, $times, $clerk[$j]['time_in']).'<br>'.
						form_label('Time out: ', 'label').form_dropdown('t,2,'.$i.','.$j, $times, $clerk[$j]['time_out']).'<br>'.
						form_label('Status: ', 'label').form_dropdown('t,3,'.$i.','.$j,	$status_lst, $clerk[$j]['status_id']);

					$next_data[$i][$j] = 

						form_hidden('clerk_id'.$i, $clerk[$i]['id']).
						$clerk[$i]['name'].'<br>'. // clerk names in an array
						form_hidden('date_n'.$j, $date_next[$j]).
						form_label('Location: ', 'label').form_dropdown('n,0,'.$i.','.$j, $loc_lst, $clerk[$i]['location_id']).'<br>'.
						form_label('Time in: ', 'label').form_dropdown('n,1,'.$i.','.$j, $times, $clerk[$i]['time_in']).'<br>'.
						form_label('Time out: ', 'label').form_dropdown('n,2,'.$i.','.$j, $times, $clerk[$i]['time_out']).'<br>'.
						form_label('Status: ', 'label').form_dropdown('n,3,'.$i.','.$j, $status_lst, $clerk[$i]['status_id']);
				}
			}

			$this->table->set_caption('This Week');

			for ($i = 0; $i < $clerk_No; $i++)
			{			
				$this->table->add_row($this_data[$i]);

			}
			echo $this->table->generate();
			


/////////// Next Week data Preperation ///////////////
				
			$this->table->set_caption('Next Week');
			
			$this->table->set_heading($date_next);

			for ($i = 0; $i < $clerk_No; $i++)
			{
				$this->table->add_row($next_data[$i]);
			}
				
			echo $this->table->generate();

			echo form_hidden('total_clerks', $clerk_No);

			echo form_submit('soho', 'Submit');
		?>

	<?php echo form_close() ?>
</div>

<?php require_once('templates/footer.php'); ?>

