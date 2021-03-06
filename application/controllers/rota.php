<?php
	//session_start();

	Class Rota extends CI_Controller
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->helper('security');
			$this->load->library('table');
			$this->load->library('session');
			$this->load->model('load_file');
			$this->load->model('login_database');
		}




		public function process_rota()
		{

// prepere the data to sent to the admin page
           	$data = array(
				'name' => 'Admin',
				'message_display' => ''
				);

// construct the table with the POST names
           	for ($i = 0; $i < $this->input->post('total_clerks'); $i++)
			{
				for ($j = 0; $j < 7; $j++)
				{
					$post_data = array(
									'till_reference' => $this->input->post('clerk_id'.$i),
									'date' => date("Y-m-d", strtotime($this->input->post('date_t'.$j))),
									'location_id' => $this->input->post('t,0,'.$i.','.$j),
									'rota_from' => $this->input->post('t,1,'.$i.','.$j),
									'rota_to' => $this->input->post('t,2,'.$i.','.$j),
									'status_id' => $this->input->post('t,3,'.$i.','.$j)
									);

// Insert the data to the database
				$this->load_file->insert_batch_data('clerks_list_rota', $post_data); // later on, this shoud be inside the loop above
	
				}
			}

			


// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('templates/back', $data);   
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }	
		}
	}
?>