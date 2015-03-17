<?php

	Class Load_File extends CI_Model
	{
		public function __construct() 
		{
			parent::__construct();
//load helper libraries
			$this->load->helper('url');
			$this->load->helper('file');
		}

		public function load_data_file()
		{
			$file_path = 'http://localhost/clk123/application/incoming';



			if (read_file($file_path))
			{
				$path = read_file($file_path);
			}
			else
			{
				$path = 'error! file not loaded! :(';
			}
			
			$path = get_filenames('$file_path');
			
			return $path;
		}
	}

?>