<?php
	//session_start();

	Class Main extends CI_Controller
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url');
//			$this->load->helper('form');
			$this->load->helper('security');
//			$this->load->library('form_validation');
			$this->load->library('session');
			$this->load->model('load_file');
		}

		public function home_admin()
		{
// Load the file, and extract it on the database
			$file = $this->load_file->load_data_file();



			$data = array(
				'name' => 'Admin',
				'file' => $file
				);
			
// Check if user is logged
			if ($this->session->userdata('logged_in') == TRUE)
		    {
		    	$this->load->view('admin_page', $data);
		    }
		    else
		    {
		    	$this->load->view('login_form', $data);
		    }
			
		}

		public function home_user()
		{
			$data = array(
				'name' => 'User'
				);

// Check if user is logged
			if ($this->session->userdata('logged_in') == TRUE)
		    {
		    	$this->load->view('user_page', $data);
		    }
		    else
		    {
		    	$this->load->view('login_form', $data);
		    }			
		}
	}