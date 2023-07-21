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

	function test_query()
		{
			$this->db->select("
				'001/SP2B/1.01.2.22.0.00.01.0000/I/2023' as no_sp2b,
				'2023-01-31' as tgl_sp2b,
				1 as bulan,
				'3001' as no_bukti,
				'2023-01-02' as tgl_bukti,
				'1.01.2.22.0.00.01.0000' as kd_skpd,
				'DINAS PENDIDIKAN DAN KEBUDAYAAN' as nm_skpd,
				'1.01.02.1.01.53' as kd_sub_kegiatan,
				'Pengelolaan Dana BOS Sekolah Menengah Atas' as nm_sub_kegiatan,
				'510288888888' as kd_rek6,
				'Belanja Barang dan Jasa BOS' as nm_rek6,
				'1' as kd_satdik,
				'SMA/SMK NEGERI' as nm_satdik,
				'Belanja Barang dan Jasa BOS Kinerja SMA Negeri Semester 1' as keterangan,
				'2023-01-01' as tgl_awal,
				'2023-01-31' as tgl_akhir,
				1 as tahap,
				'reguler' as jenis_bos,
				348015753 as nilai

			");
			
			$query = $this->db->get();
			return $query->result();
		}


	
    
}
