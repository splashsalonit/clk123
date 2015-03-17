<?php

	Class Load_File extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url', 'file');
		}

		public function load_data_file()
		{
			$path = read_file('.application/incoming/mytest.txt');
			echo $path;
		}
	}

?>