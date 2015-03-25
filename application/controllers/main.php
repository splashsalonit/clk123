<?php
	//session_start();

	Class Main extends CI_Controller
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->helper('file');
			$this->load->helper('security');
			$this->load->library('table');
			$this->load->library('session');
			$this->load->model('load_file');
			$this->load->model('login_database');
			$this->load->library('csvreader');

		}

		public function home_admin()
		{
// Load the file, from the following path
        	$filePath = './application/incoming/mytest.txt';
      
        	$csvData = $this->csvreader->parse_file($filePath);

        	$fileDate = $this->csvreader->get_file_date($filePath);

// Organise the array
			$organised_line = array(
                'till_reference'           ,
                'clerk_name'               ,
                'ibutton/mag_card_number'  ,
                'secret_sign_on_number'    ,
                'compulsions'              ,
                'allowed_functions1'       ,
                'mode_control'             ,
                'operation'                ,
                'allowed_functions2'       ,
                'allowed_functions3'       ,
                'commission1'              ,
                'commission2'              ,
                'commission3'              ,
                'commission4'              ,
                'start_clerk_range'        ,
                'end_clerk_range'          ,
                'start_table_range'        ,
                'end_table_range'          ,
                'default_price_level'      ,
                'default_menu_level'       ,
                'default_floor_plan_level' ,
                'reserved1'                ,
                'reserved2'                ,
                'reserved3'                ,
                'reserved4'                ,
                'reserved5'                ,
                'creation_date'			   ,
                );

            foreach ($csvData as $line => $value)
            {
            	$value[] = $fileDate;
               	$result[] = array_combine($organised_line, $value);
            }

// Call the model, check if the date is the same. insert the data only if they are different
            $query = $this->load_file->get_data('creation_date');
/*
// query to initialise the "clerks_list_rota" table            
		    $query_init = $this->load_file->get_data_extend('raw_data', 'till_reference');
			foreach ($query_init->result() as $row)
					{
						$this->load_file->insert_data('clerks_list_rota', $row);
					}
*/
            if ($query->num_rows() != 0 )
			{ 
	        	foreach ($query->result() as $row)
				{
					if ($row->creation_date != $fileDate )
	            	{
	            		$this->load_file->load_data_file($result);
	            	}
	            	
				}
			}
			
			$result = $this->load_file->get_clerks();
			$locations = $this->load_file->get_data_extend('locations', 'locations');

// prepere the data to sent to the admin page
           	$data = array(
				'name' => 'Admin',
				'file' => $result,
				'locations' => $locations,
				'message_display' => ''
				);
			
// Load the view as a result and check if the user is logged in
           	if ($this->login_database->is_logged())
           	{
           		$this->load->view('admin_page', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}

		public function clerk_process()
		{
// Get the post variables
			for ($i = 0; $i <= $this->input->post('last_clerk'); $i++) 
			{
				$post_data[$this->input->post($i)] =  $this->input->post('loc'.$i);
			}
// Prepare the table
			foreach ($post_data as $key => $value) 
			{
				$newdata[] = array('till_reference' => $key, 'location_id' => $value);
			}
			unset($newdata[0]);
// Insert the data to the database
			$this->load_file->update_data('default_locations', $newdata, 'till_reference');
// Prepare the data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => 'Successful Update! ;)', 
				);

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



		public function soho()
		{
// Get the week dates
			$week_start = date('z', strtotime('this week'));
			$week_end = date('z', strtotime('next week'));

			for ($i = $week_start; $i < $week_end; $i++)
			{
				$date_this[] = date('D d M', strtotime("January 1st +".($i)." days"));
				$date_next[] = date('D d M', strtotime("January 1st +".($i+7)." days"));
			}

// Get the data for the form
			// query the database by sending the date you want			
			
			$result_final = $this->load_file->get_soho_clerks_init();
			
			if($result_final == NULL)
			{
				$result_final = $this->load_file->get_soho_clerks();
			}
			echo '<pre>';
print_r($result_final);
$count = 0;
			foreach ($result_final as $key => $value) 
			{
				if (isset($result_final[$key]['status_id']))
				{
					$temp = $result_final[$key]['till_reference'];

					foreach ($result_final as $index => $elements) 
					{
						if ($result_final[$key]['till_reference'] != $temp)
						{
							$count = $count + 1;
						}

					}
			echo '<pre>';
print_r($count);
					$clerk[] =
						array(
						'id' => $result_final[$key]['till_reference'],
						'name' => $result_final[$key]['clerk_name'],
						'location_id' => $result_final[$key]['location_id'],
						'status_id' => $result_final[$key]['status_id'],
						'time_in' => date('H:i' ,strtotime($result_final[$key]['rota_from'])),
						'time_out' => date('H:i' ,strtotime($result_final[$key]['rota_to'])),
						'date' => $result_final[$key]['date'],
						'test' => $result_final[$key]['id'],
						);					
				}
				else
				{
					$clerk[] =
						array(
						'id' => $result_final[$key]['till_reference'],
						'name' => $result_final[$key]['clerk_name'],
						'location_id' => 1,
						'status_id' => 1,
						'time_in' => '09:00',
						'time_out' => '18:00'
						);
				}
			}


// Put the status and location lists
			$locations = $this->load_file->get_data_extend('locations', 'locations');
			$status = $this->load_file->get_data_extend('clerk_status', 'status');

			foreach ($locations as $key => $value) 
			{
				$loc_lst[] = $value['locations'];
			}
			
			foreach ($status as $key => $value) 
			{
				$status_lst[] = $value['status'];
			}



// Prepare data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => '', 
				'clerk' => $clerk,
				'status_lst' => $status_lst,
				'loc_lst' => $loc_lst,
				'date_this' => $date_this,
				'date_next' => $date_next,
				);

// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('soho_view', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}

		public function cr()
		{
// Prepare data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => '', 
				);




// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('cr_view', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}

		public function gd()
		{
// Prepare data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => '', 
				);




// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('gd_view', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}

		public function nr()
		{
// Prepare data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => '', 
				);




// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('nr_view', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}

		public function office()
		{
// Prepare data for the form
			$data = array(
				'name' => 'Admin',
				'message_display' => '', 
				);




// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('office_view', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}















public function home_user()
		{
// Prepare data for the form
			$data = array(
				'name' => 'User',
				'message_display' => '', 
				);




// Load the view as a result and check if the user is logged in
			if ($this->login_database->is_logged())
           	{
           		$this->load->view('user_page', $data);
           	}
		    else
		   	{
		    	$this->load->view('login_form', $data);
		    }
		}
	}