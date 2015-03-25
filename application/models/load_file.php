<?php

	Class Load_File extends CI_Model
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
		}

		public function load_data_file($data)
		{
			foreach ($data as $lines => $line) 
			{				
				$this->db->insert('raw_data', $line);
			}
			
			if ($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			else
				{
					return FALSE;
				} 
		}

		public function insert_data($table, $data)
		{		
			
			$this->db->insert($table, $data);
			
			
			if ($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			else
				{
					return FALSE;
				} 
		}

		public function insert_batch_data($table, $data)
		{		
			
			$this->db->insert($table, $data);
			
			
			if ($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			else
				{
					return FALSE;
				} 
		}

		public function update_data($table, $data, $field)
		{ 	
		 	$this->db->update_batch($table, $data, $field); 

		 	if ($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			else
				{
					return FALSE;
				} 
		}

		public function get_data($columns)
		{
			$this->db->select($columns);
			$this->db->from('raw_data');
			$this->db->where('secret_sign_on_number >', 0 );
			$this->db->order_by('creation_date', 'desc');

			$query = $this->db->get();

			return $query;
		}

		public function get_data_extend($table, $columns)
		{
			$this->db->select($columns);
			$this->db->from($table);
			
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_clerks()
		{
			$this->db->select('raw_data.till_reference, raw_data.secret_sign_on_number, raw_data.clerk_name, locations.locations, default_locations.location_id');
			$this->db->from('raw_data');
			$this->db->join('default_locations', 'default_locations.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('locations', 'locations.location_id = default_locations.location_id', 'inner');
			$this->db->where('raw_data.secret_sign_on_number >', 0);

			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_soho_clerks_init()
		{
			$this->db->select('clerks_list_rota.till_reference, clerks_list_rota.id, clerks_list_rota.date, clerks_list_rota.location_id, clerks_list_rota.rota_from, clerks_list_rota.rota_to, clerks_list_rota.status_id, raw_data.clerk_name');
			$this->db->from('clerks_list_rota');
			$this->db->join('raw_data', 'clerks_list_rota.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('default_locations', 'default_locations.till_reference = clerks_list_rota.till_reference', 'inner');
			$this->db->join('locations', 'locations.location_id = default_locations.location_id', 'inner');
			$this->db->join('clerk_status', 'clerks_list_rota.status_id = clerk_status.status_id', 'inner');
			$this->db->where('raw_data.secret_sign_on_number >', 0);
			$this->db->where('default_locations.location_id =', 1);
			$this->db->group_by('clerks_list_rota.id');
			//$this->db->where('clerks_list_rota.date =', $date);

			

			$query = $this->db->get();
		//	echo '<pre>';
		//	print_r($query->result_array());
			return $query->result_array();

			
/*	just in case of a bigger screw up ////// :P
			$this->db->select('clerks_list_rota.till_reference, clerks_list_rota.date, clerks_list_rota.location_id, clerks_list_rota.rota_from, clerks_list_rota.rota_to, clerks_list_rota.status_id, raw_data.clerk_name, locations.locations, clerk_status.status');
			$this->db->from('clerks_list_rota');
			$this->db->join('raw_data', 'clerks_list_rota.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('default_locations', 'default_locations.till_reference = clerks_list_rota.till_reference', 'inner');
			$this->db->join('locations', 'locations.location_id = default_locations.location_id', 'inner');
			$this->db->join('clerk_status', 'clerks_list_rota.status_id = clerk_status.status_id', 'inner');
			$this->db->where('raw_data.secret_sign_on_number >', 0);
			$this->db->where('default_locations.location_id =', 1);
			$this->db->order_by('clerks_list_rota.date', 'desc');
			$this->db->group_by('clerks_list_rota.till_reference');
*/
		}

		public function get_soho_clerks()
		{
			$this->db->select('raw_data.till_reference, raw_data.clerk_name, locations.locations');
			$this->db->from('raw_data');
			//$this->db->join('raw_data', 'clerks_list_rota.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('default_locations', 'default_locations.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('locations', 'locations.location_id = default_locations.location_id', 'inner');
			//$this->db->join('clerk_status', 'clerks_list_rota.status_id = clerk_status.status_id', 'inner');
			$this->db->where('raw_data.secret_sign_on_number >', 0);
			$this->db->where('default_locations.location_id =', 1);
			//$this->db->order_by('clerks_list_rota.date', 'desc');
			//$this->db->group_by('clerks_list_rota.till_reference');
			
			$query = $this->db->get();

			return $query->result_array();
/*	just in case of a bigger screw up ////// :P
			$this->db->select('clerks_list_rota.till_reference, clerks_list_rota.date, clerks_list_rota.location_id, clerks_list_rota.rota_from, clerks_list_rota.rota_to, clerks_list_rota.status_id, raw_data.clerk_name, locations.locations, clerk_status.status');
			$this->db->from('clerks_list_rota');
			$this->db->join('raw_data', 'clerks_list_rota.till_reference = raw_data.till_reference', 'inner');
			$this->db->join('default_locations', 'default_locations.till_reference = clerks_list_rota.till_reference', 'inner');
			$this->db->join('locations', 'locations.location_id = default_locations.location_id', 'inner');
			$this->db->join('clerk_status', 'clerks_list_rota.status_id = clerk_status.status_id', 'inner');
			$this->db->where('raw_data.secret_sign_on_number >', 0);
			$this->db->where('default_locations.location_id =', 1);
			$this->db->order_by('clerks_list_rota.date', 'desc');
			$this->db->group_by('clerks_list_rota.till_reference');
*/
		}

	}

?>