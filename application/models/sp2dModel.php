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

		function getspmQuery($kueri,$jenis)
		{
			
			if (isset($kueri)) {
				if($jenis=='SPM'){
					$where = "where no_spm = '$kueri'";
				}else if($jenis=='NPWP'){
					$where = "where npwp = '$kueri'";
				}else if($jenis=='Rekanan'){
					$where = "where nmrekan like '%$kueri%'";
				}
				$query = $this->db->query("SELECT no_spm, keperluan as keterangan from trhspm
							$where order by urut");
			}
			return $query->result();
			
			
		}
		function getspmDetailQuery($kueri)
		{
			$kueri = str_replace('abc','/',$kueri);
			if (isset($kueri)) {
				$query = $this->db->query("SELECT TOP 1 'SPP telah diterbitkan oleh BP/BPP SKPD' as ket, 1 as kode, no_spp as nomor,tgl_spm as tanggal from trhspm where no_spm='$kueri'
				UNION ALL
				SELECT TOP 1 'SPM telah diterbitkan oleh PPK SKPD' as ket, 2 as kode, no_spm,tgl_spm from trhspm where no_spm='$kueri'
				UNION ALL
				SELECT TOP 1 'SPM telah dibatalkan ('+b.ket_batal+')' as ket, 3 as kode, no_spm,a.tgl_spm from trhspm a INNER JOIN trhspp b on a.no_spp=b.no_spp
				where a.no_spm='$kueri' and sp2d_batal='1'
				UNION ALL
				SELECT TOP 1 'SP2D telah diterbitkan BUD' as ket, 4 as kode, no_sp2d,tgl_sp2d from trhsp2d where no_spm='$kueri'
				UNION ALL
				SELECT TOP 1 'SP2D telah diverifikasi' as ket, 5 as kode, no_sp2d,tgl_verif from trhsp2d where no_spm='$kueri' and (is_verified <>'0' OR is_verified <>'' OR is_verified =null) 
				UNION ALL
				SELECT TOP 1 'SP2D telah dibuat penguji' as ket, 6 as kode, a.no_sp2d,c.tgl_uji from trhsp2d a 
				INNER JOIN trduji b on a.no_sp2d=b.no_sp2d
				INNER JOIN trhuji c on c.no_uji=b.no_uji
				where no_spm='$kueri'
				UNION ALL
				SELECT TOP 1 'SP2D telah diotorisasi' as ket, 7 as kode, a.no_sp2d,FORMAT(tgl_kirim, 'yyyy-MM-dd')as tanggal from trhsp2d a 
				INNER JOIN trduji b on a.no_sp2d=b.no_sp2d
				INNER JOIN trhuji c on c.no_uji=b.no_uji
				where no_spm='$kueri' and status_bud='1'
				UNION ALL
				SELECT TOP 1 'SP2D pending, dalam proses pihak bank' as ket, 8 as kode, a.no_sp2d,tgl_kas_bud from trhsp2d a 
				INNER JOIN trduji b on a.no_sp2d=b.no_sp2d 
				where no_spm='$kueri' and status_bud='1' and b.status ='4'
				UNION ALL
				SELECT TOP 1 'SP2D telah tersalurkan' as ket, 9 as kode, a.no_sp2d,tgl_transfer from trhsp2d a 
				INNER JOIN trduji b on a.no_sp2d=b.no_sp2d 
				where no_spm='$kueri' and status_bud='1' and b.status ='2'");
			}
			return $query->result();
			
			
		}

		function getspmRekananQuery($kueri)
		{
			$kueri = str_replace('abc','/',$kueri);
			if (isset($kueri)) {
				$query = $this->db->query("SELECT TOP 1 
					nm_skpd,
					keperluan as ket, 
					nmrekan, npwp ,nilai from trhspm where no_spm='$kueri'
				");
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
