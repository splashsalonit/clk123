<?php
	//session_start();

	Class User_Authentication extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->helper('security');
			$this->load->library('form_validation');
			$this->load->library('session');
			$this->load->model('login_database');
			$this->load->model('load_file');
		}
//show login page
		public function user_login_show()
		{
			$this->load->view('login_form');
		}
//show registration page
		public function user_registration_show()
		{
			$this->load->view('registration_form');
		}
//Validate and store registration data in database
		public function new_user_registration()
		{
//check validation for user input in sign up form
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email_value', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('registration_form');
			}
			else
			{
				$data = array (
					'name' => $this->input->post('name'),
					'user_name' => $this->input->post('username'),
					'user_email' => $this->input->post('email_value'),
					'user_password' => $this->input->post('password')
					);
				$result = $this->login_database->registration_insert($data);
				
				if ($result == TRUE)
				{
					$data['message_display'] = 'Registration Successfully! ;)';
					$this->load->view('login_form', $data);
				}
				else
				{
					$data['message_display'] = 'Already exist! :/';
					$this->load->view('registration_form', $data);
				}
			}
		}
// Check for user login progress
		public function user_login_process()
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

			if ($this->form_validation->run() == FALSE) 
			{
				$this->load->view('login_form');
			}
			else
			{
				$data = array(
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password')
					);
				$result = $this->login_database->login($data);

				if ($result == TRUE)
				{
					$sess_array = array(
						'username' => $this->input->post('username')
						);
//add user data in the session
					$this->session->set_userdata('logged_in', $sess_array);
					$result = $this->login_database->read_user_information($sess_array);

					if ($result != FALSE)
					{
						$clerks = $this->load_file->get_clerks();
						$locations = $this->load_file->get_data_extend('locations', 'locations');

// prepere the data to sent to the admin page
						$data = array(
							'name' => $result[0]->name,
							'username' => $result[0]->user_name,
							'email' => $result[0]->user_email,
							'password' => $result[0]->user_password,
							'file' => $clerks,
							'locations' => $locations,
							'message_display' => 'Loged in Successfully! ;)'
							);

						if ($data['name'] == 'Admin')
						{
							$this->load->view('admin_page', $data);
						}
						else
						{
							$this->load->view('user_page', $data);
						}
					}
				}
				else
				{
					$data['message_display'] = 'Wrong Username or Password! :/';
					$this->load->view('login_form', $data);
				}
			}
		}
		
// logout from admin page
		public function logout()
		{
//remove session data
			$sess_array = array(
				'username' => '' 
				);
			$this->session->unset_userdata('logged_in', $sess_array);
			$data['message_display'] = 'Successfully Logout! :)';
			$this->load->view('login_form', $data);
		}
	}
?>
