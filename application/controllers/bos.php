<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Bos extends REST_Controller
{	
	
	protected $builtInMethods;
	
	public function __construct()
	{
		parent::__construct();
		$this->__getMyMethods();
		$this->load->model('bosModel');
        $this->load->helper('helper');
	}
    
    function view_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
		$tgl = $this->get('tgl');
        if($tgl==''){
			$this->response(array('error' => 'Data parameter kosong'), 400); 
		}
		else {
			
		$tg1 = substr($tgl,0,2);
		$tg2 = substr($tgl,2,2);
		$tg3 = substr($tgl,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
			
			$query = $this->mapi->get_tgl($tanggal,'tsamsat','tgl_samsat');
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}
	
	function save_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
        $tgl1 = $this->get('tgl');
		date_default_timezone_set('Asia/Jakarta');
		$now 	 = date('Y-m-d H:i:s');
		
		$tg1 = substr($tgl1,0,2);
		$tg2 = substr($tgl1,2,2);
		$tg3 = substr($tgl1,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		
		$url = "https://simakda.kalbarprov.go.id/simakdaservice_2023/index.php/bos/test/format/json";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($ch);
        $content = json_decode($response);
        curl_close($ch);
		
		// $data = file_get_contents($url);

		// print_r($data);
		// return;
	
		$row_num = count($content);
        $dsql='';
        $ii = 0;

        if($row_num>0){
            foreach($content as $resulte){

                $no_sp2b             = $resulte->no_sp2b;
                $tgl_sp2b            = $resulte->tgl_sp2b;
                $bulan              = $resulte->bulan;
                $no_bukti            = $resulte->no_bukti;
                $tgl_bukti           = $resulte->tgl_bukti;
                $kd_skpd             = $resulte->kd_skpd;
                $nm_skpd             = $resulte->nm_skpd;
                $kd_sub_kegiatan     = $resulte->kd_sub_kegiatan;
                $nm_sub_kegiatan     = $resulte->nm_sub_kegiatan;
                $kd_rek6             = $resulte->kd_rek6;
                $nm_rek6             = $resulte->nm_rek6;
                $kd_satdik           = $resulte->kd_satdik;
                $nm_satdik           = $resulte->nm_satdik;
                $keterangan          = $resulte->keterangan;
                $tgl_awal            = $resulte->tgl_awal;
                $tgl_akhir           = $resulte->tgl_akhir;
                $tahap               = $resulte->tahap;
                $jenis_bos           = $resulte->jenis_bos;
                $nilai               = $resulte->nilai;
                
                
                $insertdata         =  $this->db->query("INSERT INTO trdtransout_blud_test 
                                        (no_bukti,
                                        no_sp2d,
                                        kd_sub_kegiatan,
                                        nm_sub_kegiatan,
                                        kd_rek6,
                                        nm_rek6,
                                        nilai,
                                        kd_skpd,
                                        sumber,
                                        bulan,
                                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",array(
                                            $no_bukti,
                                            $no_sp2b,
                                            $kd_sub_kegiatan,
                                            $nm_sub_kegiatan,
                                            $kd_rek6,
                                            $nm_rek6,
                                            $nilai,
                                            $kd_skpd,
                                            'BOS',
                                            $bulan
                                        ));
            }
            
            
			// $insert_ =  $this->mapi->save_tgl('tsamsat',$dsql,$tanggal);

			// if($insert_){
			// 	$insert_ = $this->db->query("insert into tr_tetap select * from tr_tetap_api where no_tetap+kanal not in (select no_tetap+kanal from tr_tetap where tgl_tetap='$tanggal') and tgl_tetap='$tanggal'");
            // 	$insert_ = $this->db->query("insert into tr_terima select * from tr_terima_api where no_terima+kanal not in (select no_terima+kanal from tr_terima where kunci=1 and tgl_terima='$tanggal') and tgl_terima='$tanggal'");

            	if ($insertdata) {
					$this->response(array('status' => 'berhasil', 200));
				} else {
					$this->response(array('status' => 'gagal', 502));
				}

			// }else{
			// 	$this->response(array('status' => 'gagal', 502));
			// } 


            
			
            
			

		}else{
            $this->response(array(
				'status' => false,
				'message' => 'Data tidak ada',
				'data' => '',
				), 404); 
        }					
						
		
    }
    
    
	/**
	 * 
	 * Analizes self methods using reflection
	 * @return Boolean
	 */
	private function __getMyMethods()
	{
		$reflection = new ReflectionClass($this);
		
		//get all methods
		$methods = $reflection->getMethods();
		$this->builtInMethods = array();
		
		//get properties for each method
		if(!empty($methods))
		{
			foreach ($methods as $method) {
				if(!empty($method->name))
				{
					$methodProp = new ReflectionMethod($this, $method->name);
					
					//saves all methods names found
					$this->builtInMethods['all'][] = $method->name;
					
					//saves all private methods names found
					if($methodProp->isPrivate()) 
					{
						$this->builtInMethods['private'][] = $method->name;
					}
					
					//saves all private methods names found					
					if($methodProp->isPublic()) 
					{
						$this->builtInMethods['public'][] = $method->name;
						
						// gets info about the method and saves them. These info will be used for the xmlrpc server configuration.
						// (only for public methods => avoids also all the public methods starting with '_')
						if(!preg_match('/^_/', $method->name, $matches))
						{
							//consider only the methods having "_" inside their name
							if(preg_match('/_/', $method->name, $matches))
							{	
								//don't consider the methods get_instance and validation_errors
								if($method->name != 'get_instance' AND $method->name != 'validation_errors')
								{
									// -method name: user_get becomes [GET] user
									$name_split = explode("_", $method->name);
									$this->builtInMethods['functions'][$method->name]['function'] = $name_split['0'].' [method: '.$name_split['1'].']';
									
									// -method DocString
									$this->builtInMethods['functions'][$method->name]['docstring'] =  $this->__extractDocString($methodProp->getDocComment());
								}
							}
						}
					}
				}
			}
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * Manipulates a DocString and returns a readable string
	 * @param String $DocComment
	 * @return Array $_tmp
	 */
	private function __extractDocString($DocComment)
	{
		$split = preg_split("/\r\n|\n|\r/", $DocComment);
		$_tmp = array();
		foreach ($split as $id => $row)
		{
			//clean up: removes useless chars like new-lines, tabs and *
			$_tmp[] = trim($row, "* /\n\t\r");
		}			
		return trim(implode("\n",$_tmp));
	}



	public function API_get()
	{
		$this->response($this->builtInMethods, 200); // 200 being the HTTP response code
	}


    function test_get(){
        $query          = $this->bosModel->test_query();
        $this->response($query, 200); 
    }

    function apbdBos_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit',-1);
		$headers 	        = getallheaders();

        if (isset($headers['Authorization']) && $headers['Authorization'] == 'Bearer $2y$10$bruKwHKOLivtKlagG6h0Hu3SogLzDF8bGKlbO/xZsX22mv5xZntS@m1n') {
			$query          = $this->bosModel->getBosApbd();
			if($query) {
				$this->response(array(
					'status' => true,
					'message' => 'SUKSES',
					'data' => $query,
					), 200);                
			} else {
				
				$this->response(array(
					'status' => false,
					'message' => 'Data tidak ditemukan',
					'data' => '',
					), 404);  
			}
		}else{
			$this->response(array(
				'status' => false,
				'message' => 'Unauthorized access token',
				'data' => '',
				), 404);  
		}
	}
	
    
}