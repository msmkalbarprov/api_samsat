<?php  

class blud2simakda extends CI_Controller {

    function __construct() {
        parent::__construct();
    }


    function no_urut($kodeskpd=''){
			$query1 = $this->db->query("SELECT  case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
		
		select no_bukti nomor, 'Transaksi BOS BLUD' ket, kd_skpd from trhtransout_blud where  isnumeric(no_bukti)=1 AND panjar ='3' 
		union ALL
		select no_bukti nomor, 'Transaksi BOS BLUD' ket, kd_skpd from trhtransout_blud_penerimaan where  isnumeric(no_bukti)=1 AND panjar ='3' 
		union ALL
		select no_bukti nomor, 'SPB HIBAH' ket, kd_skpd from trhspb_hibah_skpd where  isnumeric(no_bukti)=1  
	
		union ALL
		select no_bukti nomor, 'SPB HIBAH' ket, kd_skpd from trhsp2b where  isnumeric(no_bukti)=1  
	
		union ALL
		select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
		select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
		select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
		select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
		select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
		select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
		select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
		select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
		select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
		select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
		select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
		select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND (status_drop !='1' OR status_drop is null) union ALL
		select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
		select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
		select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
		select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
		select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
		select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
		select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
		select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_kas)=1 union all
	select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kodeskpd'");
		
        $ii = 0;
        foreach ($query1->result() as $r_trhrka){
                 
                 $no_urut = $r_trhrka->nomor;                   
            } 
			return $no_urut;
        // $query1->free_result();   
    }

	function get_nama($kode,$hasil,$tabel,$field)
		{
			$this->db->select($hasil);
			$this->db->where($field, $kode);
			$q = $this->db->get($tabel);
			$data  = $q->result_array();
			$baris = $q->num_rows();
			return $data[0][$hasil];
		}

	function sp3b_blud(){

		

 		$result=json_decode($_POST['trhsp3b_blud'],true);
 		$result=json_encode($result);
 		$result1= json_decode($result);

 		// /*data trsp3b_blud*/
 		$result=json_decode($_POST['trsp3b_blud'],true);
 		$result=json_encode($result);
 		$result2= json_decode($result);

		//  echo $result2;
		/*data trterima*/
		
		$result=json_decode($_POST['trterima'],true);
		$result=json_encode($result);
		$result3= json_decode($result);

		// /*data hkasin*/
		$result=json_decode($_POST['hkasin'],true);
		$result=json_encode($result);
		$result4= json_decode($result);

		// // /*data trsp3b_blud*/
		$result=json_decode($_POST['dkasin'],true);
		$result=json_encode($result);
		$result5= json_decode($result);


		$bulan=$_POST['bulan'];
		$kodeskpd=$_POST['skpd'];
 		

		// HAPUS PENDAPATAN 

		$this->db->where("month(tgl_terima)", $bulan);
		$this->db->where("kd_skpd", $kodeskpd);
		$this->db->delete("tr_terima_blud");

		$this->db->query("DELETE w from trhkasin_blud e INNER JOIN trdkasin_blud w on e.no_sts=w.no_sts and e.kd_skpd=w.kd_skpd where month(e.tgl_sts)='$bulan' and e.kd_skpd='$kodeskpd'");

		// $this->db->where("month(", $bulan);
		// $this->db->where("kd_skpd", $kodeskpd);
		// $this->db->delete("tr_terima_blud");

		$this->db->where("month(tgl_sts)", $bulan);
		$this->db->where("kd_skpd", $kodeskpd);
		$this->db->delete("trhkasin_blud");


		// INSERT PENDAPATAN
		foreach($result3 as $value3){

				$no_terima 		= $value3->no_terima;
				$tgl_terima 	= $value3->tgl_terima;
				$no_tetap 		= $value3->no_tetap;
				$tgl_tetap 		= $value3->tgl_tetap;
				$sts_tetap 		= $value3->sts_tetap;
				$kd_skpd 		= $value3->kd_skpd;
				$kd_kegiatan 	= $value3->kd_kegiatan;
				$kd_rek5 		= $value3->kd_rek5;
				$kd_rek_blud 	= $value3->kd_rek_blud;
				$nilai 			= $value3->nilai;
				$keterangan 	= $value3->keterangan;
				$jenis 			= $value3->jenis;
				$kd_rek7 		= $value3->kd_rek7;

				

			$this->db->query("INSERT into
		 		tr_terima_blud (no_terima,tgl_terima,no_tetap,tgl_tetap,sts_tetap,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_blud,nilai,keterangan,jenis,kd_rek7)
				values ('$no_terima','$tgl_terima','$no_tetap','$tgl_tetap','$sts_tetap','$kd_skpd','$kd_kegiatan','$kd_rek5',
				'$kd_rek_blud','$nilai','$keterangan','$jenis','$kd_rek7')");
		}

		foreach($result4 as $value4){

			$no_sts 			= $value4->no_sts;
			$kd_skpd 			= $value4->kd_skpd;
			$tgl_sts 			= $value4->tgl_sts;
			$keterangan 		= $value4->keterangan;
			$total 				= $value4->total;
			$jns_spp 			= $value4->jns_spp;
			$pay 				= $value4->pay;
			$username 			= $value4->username;
			$tgl_update 		= $value4->tgl_update;
			$jns_trans 			= $value4->jns_trans;

			// 'no_sts' => $a['no_sts'],
		    //                     'kd_skpd' => $a['kd_skpd'],
		    //                     'tgl_sts' => $a['tgl_sts'],
		    //                     'keterangan'=> $a['keterangan'],
		    //                     'total'  => $a['total'],
		    //                     'jns_spp'=> $a['jns_spp'],
		    //                     'pay' => $a['pay'],
		    //                     'username'  => $a['username'],
		    //                     'tgl_update'   => $a['tgl_update'],
		    //                     'jns_trans'    => $a['jns_trans']



			$this->db->query("INSERT into trhkasin_blud (no_sts,kd_skpd,tgl_sts,keterangan,total,jns_spp,pay,username,tgl_update,jns_trans)
					values ('$no_sts','$kd_skpd','$tgl_sts','$keterangan','$total','$jns_spp','$pay','$username',
					'$tgl_update','$jns_trans')");
		}

		foreach($result5 as $value){

			$kd_skpd 			= $value->kd_skpd;
			$no_sts 			= $value->no_sts;
			$kd_rek5 			= $value->kd_rek5;
			$rupiah 			= $value->rupiah;
			$kd_kegiatan 		= $value->kd_kegiatan;
			$no_sp2d 			= $value->no_sp2d;
			$kd_rek_blud 		= $value->kd_rek_blud;
			$nm_rek_blud 		= $value->nm_rek_blud;
			$no_terima 			= $value->no_terima;
			$kd_rek7 			= $value->kd_rek7;


			$this->db->query("INSERT into trdkasin_blud (kd_skpd,no_sts,kd_rek5,rupiah,kd_kegiatan,no_sp2d,kd_rek_blud,nm_rek_blud,no_terima,kd_rek7)
				values ('$kd_skpd','$no_sts','$kd_rek5','$rupiah','$kd_kegiatan','$no_sp2d','$kd_rek_blud','$nm_rek_blud',
				'$no_terima','$kd_rek7')");
		}
		
		/*INSERT TRHSP3B ================================*/


		$this->db->query("DELETE from trhsp3b_blud where bulan='$bulan' and kd_skpd='$kodeskpd'");
		$this->db->query("DELETE from trhtransout_blud where month(tgl_kas)='$bulan' and kd_skpd='$kodeskpd'");
		$this->db->query("DELETE from trhtransout_blud_penerimaan where month(tgl_kas)='$bulan' and kd_skpd='$kodeskpd'");
		foreach($result1 as $value){
				
			    $no_sp3b= $value->no_sp3b;
	            $no_sp3b= $value->no_sp3b;
	            $kd_skpd= $value->kd_skpd;
				$no_urut= $this->no_urut($kodeskpd);
				$no_urut2 = $no_urut+1;
	            $keterangan= $value->keterangan;
	            $tgl_sp3b= $value->tgl_sp3b;
	            $status= $value->status;
	            $tgl_awal= $value->tgl_awal;
	            $tgl_akhir= $value->tgl_akhir; 
	            $no_lpj= $value->no_lpj;
	            $total= $value->total;
	            $skpd= $value->skpd;
				$nama_skpd = $this->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
	            $bulan= $value->bulan;
	            $tgl_update= $value->tgl_update;
	            $username= $value->username;
	            $status_bud= $value->status_bud;
	            $no_sp2b= $value->no_sp2b;
	            $tgl_sp2b= $value->tgl_sp2b;
	            $number_sp2b= $value->number_sp2b;
	            $last_update        =  date('Y-m-d H:i:s');
			$this->db->query("INSERT into
			 trhsp3b_blud (no_sp3b, kd_skpd, keterangan, tgl_sp3b, status, tgl_awal,tgl_akhir,
							no_lpj, total, skpd, bulan, tgl_update, username, status_bud, no_sp2b, tgl_sp2b, number_sp2b)

				values ('$no_sp3b','$kd_skpd','$keterangan','$tgl_sp3b','$status','$tgl_awal','$tgl_akhir','$no_lpj',
				    '$total','$skpd','$bulan','$tgl_update','$username','$status_bud','$no_sp2b','$tgl_sp2b','$number_sp2b')");
			
			
			$this->db->query("INSERT into
			 trhtransout_blud (no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,ket,username,tgl_update,kd_skpd,nm_skpd,total,jns_spp,pay,no_kas_pot,panjar,no_panjar)

				values ('$no_urut','$tgl_sp3b','$no_urut2','$tgl_sp3b','$no_sp3b','$keterangan','simblud','$last_update','$kd_skpd','$nama_skpd',
				    '$total','7','TUNAI','$no_urut','3','0')");

			$this->db->query("INSERT into
			trhtransout_blud_penerimaan (no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,ket,username,tgl_update,kd_skpd,nm_skpd,total,jns_spp,pay,no_kas_pot,panjar,no_panjar)

			values ('$no_urut','$tgl_sp3b','$no_urut','$tgl_sp3b','$no_sp3b','$keterangan','simblud','$last_update','$kd_skpd','$nama_skpd',
				'$total','7','TUNAI','$no_urut','3','0')");

		}


		/*INSERT TRSP3B ================================*/

        
		$this->db->query("DELETE from trsp3b_blud where month(tgl_sp3b)='$bulan' and kd_skpd='$kodeskpd'");
		$this->db->query("DELETE from trdtransout_blud where bulan='$bulan' and kd_skpd='$kd_skpd'");
		$this->db->query("DELETE from trdtransout_blud_penerimaan where bulan='$bulan' and kd_skpd='$kd_skpd'");
		foreach($result2 as $value){

	            $no_sp3b 	= $value->no_sp3b;
	            $no_bukti 	= $value->no_bukti;
	            $keterangan	= $value->keterangan;
	            $tgl_sp3b	= $value->tgl_sp3b;
	            $kd_rek5 	= $value->kd_rek5;
	            $nm_rek5 	= $value->nm_rek5;
	            $nilai 		= $value->nilai;
	            $kd_skpd 	= $value->kd_skpd;
	            $kd_kegiatan= $value->kd_kegiatan;
	            $no_lpj 	=  	$value->no_lpj;

			$this->db->query("INSERT into
			 trsp3b_blud (no_sp3b, no_bukti, keterangan, tgl_sp3b, kd_rek6, nm_rek6,nilai,
							kd_skpd, kd_sub_kegiatan, no_lpj)

				values ('$no_sp3b','$no_bukti','$keterangan','$tgl_sp3b','$kd_rek5','$nm_rek5','$nilai','$kd_skpd', '$kd_kegiatan','$no_lpj')");
			
			// $this->db->query("DELETE from trdtransout_blud where no_sp2b='$no_sp3b' and kd_skpd='$kd_skpd'");
			$this->db->query("INSERT into
			 trdtransout_blud (no_sp2d, kd_sub_kegiatan, kd_rek6, nm_rek6,nilai,
							kd_skpd,bulan,sumber)

				values ('$no_sp3b','$kd_kegiatan','$kd_rek5','$nm_rek5','$nilai','$kd_skpd',month('$tgl_sp3b'),'BLUD')");

			$this->db->query("INSERT into
			trdtransout_blud_penerimaan (no_sp2d, kd_sub_kegiatan, kd_rek6, nm_rek6,nilai,
						   kd_skpd,bulan,sumber)

			   values ('$no_sp3b','$kd_kegiatan','$kd_rek5','$nm_rek5','$nilai','$kd_skpd',month('$tgl_sp3b'),'BLUD')");

		}

		$this->db->query("UPDATE R SET R.no_bukti = P.no_bukti FROM trdtransout_blud AS R INNER JOIN trhtransout_blud AS P ON R.no_sp2d = P.no_sp2d WHERE R.no_sp2d<>''");
		$this->db->query("UPDATE R SET R.no_bukti = P.no_bukti FROM trdtransout_blud_penerimaan AS R INNER JOIN trhtransout_blud_penerimaan AS P ON R.no_sp2d = P.no_sp2d WHERE R.no_sp2d<>''");

		echo json_encode(1);

	} /*end function*/


	function data_anggaran_pukesmas($kd_skpd=''){
		if($kd_skpd==''){
			$filter="";
		}else{
			$filter="and kd_skpd='$kd_skpd'";
		}

		$sql="SELECT left(no_trdrka,22) kd_skpd, kd_sub_kegiatan, kd_rek6, nm_rek6,
			sum(nilai) nilai, sum(nilai_sempurna) geser, sum(nilai_ubah) ubah from trdrka WHERE left(kd_skpd,4)='1.02' and right(kd_rek6,4)='9999'
			$filter
			GROUP BY kd_sub_kegiatan, kd_rek6,left(no_trdrka,22),nm_rek6
			ORDER BY kd_skpd";
		$exe=$this->db->query($sql);

        $data1 = array();
        $ii = 0;
        foreach($exe->result_array() as $a)
        { 	           
            $data1[] = array(
                        'id' => $ii, 
                        'kd_skpd' => $a['kd_skpd'],
                        'kegiatan' => $a['kd_sub_kegiatan'],
                        'kd_rek5'=> $a['kd_rek6'],
                        'nm_rek5'=> $a['nm_rek6'],
                        'nilai'  => $a['nilai'],
                        'nilai_sempurna'=> $a['geser'],
                        'nilai_ubah' => $a['ubah']
                        );
                        $ii++;
        }


	    echo json_encode($data1);
	

	}



	function jurnal_blud(){
 		$result=json_decode($_POST['data_header'],true);
 		$result=json_encode($result);
 		$data_h= json_decode($result);

 		/*data trsp3b_blud*/
 		$result=json_decode($_POST['data_detail'],true);
 		$result=json_encode($result);
 		$data_d= json_decode($result);


 		$bulan=$_POST['bulan'];

		/*INSERT header ju ================================*/

		$tgl_update=date("Y-m-d h:i:s");
		$this->db->query("DELETE trhju_pkd where tabel='99' and reev='99' and month(tgl_voucher)='$bulan'");
		foreach($data_h as $value){
			    $no_voucher= $value->no_voucher;
	            $tgl_voucher= $value->tgl_voucher;
	            $kd_skpd= $value->kd_skpd;
	            $nm_skpd= $value->nm_skpd;
	            $debet= $value->debet;
	            $kredit= $value->kredit;
	            $username= $value->username;
	            $keterangan= $value->keterangan;
	            $map_real= $value->map_real;
	            $tabel= $value->tabel;
	            $reev= $value->reev;

			$this->db->query("INSERT into
			 trhju_pkd (no_voucher, tgl_voucher, kd_skpd, nm_skpd, ket, tgl_update, username, kd_unit, map_real, total_d, total_k, tabel, reev)

				values ('$no_voucher','$tgl_voucher', '$kd_skpd', '$nm_skpd', '$keterangan', '$tgl_update', '$username', '$kd_skpd', '$map_real', '$debet', '$kredit', '$tabel','$reev' )");
		}


		/*INSERT detail ju ================================*/

        
		$this->db->query("DELETE trdju_pkd where map_real='99' and kd_subkegiatan='$bulan'");

		foreach($data_d as $value){
				$no_voucher 	= $value->no_voucher;
				$kd_kegiatan 	= $value->kd_kegiatan;
				$kd_skpd		= $value->kd_skpd;
				$rek			= $value->rek;
				$rk 			= $value->rk;
				$debet 			= $value->debet;
				$kredit			= $value->kredit;
				$map_real		= $value->map_real;
				$bulan			= $value->bulan;

			$this->db->query("INSERT into
			 trdju_pkd (no_voucher, kd_sub_kegiatan, kd_unit, kd_rek6, rk, debet, kredit, map_real, kd_subkegiatan)

				values ('$no_voucher', '$kd_kegiatan', '$kd_skpd', '$rek', '$rk', '$debet', '$kredit', '$map_real', '$bulan')");
		}
			$this->db->query("UPDATE a set
				a.nm_rek6=b.nm_rek6
				from trdju_pkd a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6
				where map_real='99' and kd_subkegiatan='$bulan'
				");

			$this->db->query("UPDATE a set
				a.nm_sub_kegiatan=b.nm_sub_kegiatan
				from trdju_pkd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
				where map_real='99' and kd_subkegiatan='$bulan'
				");
		echo json_encode(1);

	} /*end function*/

	function data_lraapbn(){

		$sql="SELECT tgl_bukti,a.kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,
			a.kd_rek6 ,(select nm_rek6 from ms_rek6 where a.kd_rek6 = kd_rek6)nm_rek6,sum(anggaran)anggaran,sum(debet)debet,sum(kredit)kredit
			from
			(
				select tgl_bukti,a.kd_skpd,kd_rek6 ,sum(nilai)debet, 0 kredit
				from trdtransout a inner join trhtransout b ON a.no_bukti = b.no_bukti AND b.kd_skpd = a.kd_skpd
				where a.kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000')  AND MONTH(b.tgl_bukti) <= 12 and year(b.tgl_bukti)=2023 and left(a.kd_rek6,1)='5' and right(kd_rek6,4)!='9999'
				group by tgl_bukti,a.kd_skpd,kd_rek6
				union all
				SELECT  tgl_sts,a.kd_skpd,kd_rek6,0 debet , isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) kredit
				FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000') and month(a.tgl_sts) <=12  and year(a.tgl_sts)=2023 AND  LEFT(b.kd_rek6, 1) = '4' and right(kd_rek6,4)!='9999'
				group by tgl_sts,a.kd_skpd,kd_rek6,a.jns_trans
				union all
				select tgl_bukti,a.kd_skpd,c.map_lo kd_rek6 ,sum(nilai)debet, 0 kredit
				from trdtransout a inner join trhtransout b ON a.no_bukti = b.no_bukti AND b.kd_skpd = a.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
				where a.kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000')  AND MONTH(b.tgl_bukti) <= 12 and year(b.tgl_bukti)=2023 and left(a.kd_rek6,1)='5' and right(a.kd_rek6,4)!='9999'
				group by tgl_bukti,a.kd_skpd,c.map_lo
				union all
				SELECT  tgl_sts,a.kd_skpd, map_lo kd_rek6,0 debet,isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) realisasi
				FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd inner join ms_rek6 c on b.kd_rek6=c.kd_rek6
				WHERE a.kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000') and month(a.tgl_sts) <=12  and year(a.tgl_sts)=2023 AND  LEFT(b.kd_rek6, 1) = '4' and right(b.kd_rek6,4)!='9999'
				group by tgl_sts,a.kd_skpd,map_lo,a.jns_trans
			)a
			left join 
			(
				select kd_skpd,kd_rek6,sum(anggaran)anggaran
				from
				(
					select kd_skpd,kd_rek6,sum(nilai)anggaran
					from trdrka
					where kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000') and jns_ang='U2' and right(kd_rek6,4)!='9999'
					group by kd_skpd,kd_rek6
					union all
					select kd_skpd, map_lo kd_rek6,sum(nilai)anggaran
					from trdrka a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6
					where kd_skpd in ('1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000') and jns_ang='U2' and right(a.kd_rek6,4)!='9999'
					group by kd_skpd,map_lo
				)a
				group by kd_skpd,kd_rek6
			)b on a.kd_skpd=b.kd_skpd and a.kd_rek6=b.kd_rek6
			group by tgl_bukti,a.kd_skpd,a.kd_rek6";
		$exe=$this->db->query($sql);

        $data1 = array();
        $ii = 0;
        foreach($exe->result_array() as $a)
        { 	           
            $data1[] = array(
                        'id' => $ii, 
                        'tgl_bukti' => $a['tgl_bukti'],
                        'kd_skpd' => $a['kd_skpd'],
                        'nm_skpd' => $a['nm_skpd'],
                        'kd_rek5'=> $a['kd_rek6'],
                        'nm_rek5'=> $a['nm_rek6'],
                        'anggaran'  => $a['anggaran'],
                        'debet'=> $a['debet'],
                        'kredit' => $a['kredit']
                        );
                        $ii++;
        }


	    echo json_encode($data1);
	

	}

} /*end of end*/