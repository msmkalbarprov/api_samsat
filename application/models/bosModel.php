<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
Class BosModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getBosApbd()
		{
			$this->_get_datatables_query();
				$query = $this->db->get();
			return $query->result();
		}

	private function _get_datatables_query()
		{
			$this->db->from("sipkas_bos");
			$this->db->order_by("subkegiatan,nm_subkegiatan,akun,nm_akun");
			
		}
	
	
    
}
