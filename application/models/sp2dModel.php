<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
Class Sp2dModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getsp2dQuerys($kueri)
		{
			$hasil = $this->_get_datatables_query($kueri);
			return $hasil;
		}

	function getsp2dQuery($kueri)
		{
			
			if (isset($kueri)) {
				$query = $this->db->query("SELECT *
								from (
							SELECT 
							(SELECT no_uji from trduji where no_sp2d=a.no_sp2d)as no_uji,no_sp2d,no_spm,
							nm_skpd,
							isnull(nmrekan,(SELECT TOP 1 nm_rekening from ms_rekening_bank_online where a.no_rek=rekening and kd_skpd=a.kd_skpd))as penerima,
							npwp,
							CAST(jns_spp as varchar)as jns_spp,
							keperluan,
							tgl_sp2d as tgl_terbit,
							tgl_kas_bud  as tgl_transfer ,
							nilai,
							(SELECT status from trduji where no_sp2d=a.no_sp2d)as statuskirim,
							(SELECT sum(nilai) from trspmpot where no_spm=a.no_spm and a.kd_skpd=kd_skpd)as pot
							from trhsp2d a where (sp2d_batal is null OR sp2d_batal =0)  
							and ((jns_spp=6 and jenis_beban=6 ) OR jns_spp = 5)
							)z where  (no_sp2d like '%$kueri%' OR penerima like '%$kueri%' OR npwp like '%$kueri%' OR no_spm like '%$kueri%') order by CAST(SUBSTRING(no_sp2d,0,LEN(no_sp2d)-7) as int) DESC");
			}else{
				$query = $this->db->query("SELECT TOP 10 *
								from (
							SELECT 
							(SELECT no_uji from trduji where no_sp2d=a.no_sp2d)as no_uji,no_sp2d,no_spm,
							nm_skpd,
							isnull(nmrekan,(SELECT nm_rekening from ms_rekening_bank_online where a.no_rek=rekening and kd_skpd=a.kd_skpd))as penerima,
							npwp,
							CAST(jns_spp as varchar)as jns_spp,
							keperluan,
							tgl_sp2d as tgl_terbit,
							tgl_kas_bud  as tgl_transfer ,
							nilai,
							(SELECT status from trduji where no_sp2d=a.no_sp2d)as status,
							(SELECT sum(nilai) from trspmpot where no_spm=a.no_spm and a.kd_skpd=kd_skpd)as pot
							from trhsp2d a where (sp2d_batal is null OR sp2d_batal =0)  
							and ((jns_spp=6 and jenis_beban=6 ) OR jns_spp = 5)
							)z where order by CAST(SUBSTRING(no_sp2d,0,LEN(no_sp2d)-7) as int) DESC");

			}

			return $query->result();
			
			
		}
	

	function last_update()
	{	
		$this->db->select('TOP 1 tgl_kas_bud');
		$this->db->from('trhsp2d');
		$this->db->order_by('tgl_kas_bud', 'DESC');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row['tgl_kas_bud'];
	}


	
    
}
