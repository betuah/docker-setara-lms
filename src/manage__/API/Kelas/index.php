<?php
require("../../../setting/connection.php");
require("../../../setting/connection-log.php");
spl_autoload_register(function ($class) {
  include '../../controller/' .$class . '.php';
});

$method = $_REQUEST;
$table  = $db->tugas;

if(isset($method['action'])){
    if ($_SERVER['SERVER_ADDR'] == '103.52.145.195') {
        if($method['action'] == 'show'){
            $ID     = new MongoId($method['ID']);
            $data   = $table->findOne(array("_id" => $ID));
            
            $resp   = array('data'=>$data);
            
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }

        if($method['action'] == 'readMore'){
            $kelasClass = new Kelas();
            $id         = $method['ID'];

            $data   = $kelasClass->readMore($id);
            
            $resp   = array('data'=>$data);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
            // print $_SERVER['SERVER_ADDR'];
        }

        if($method['action'] == 'getAll'){
            $kelasClass     = new Kelas();

            $skip           = $method['start'];
            $limit          = $method['length'];
            $sekolah        = $method['sekolah'];
            $search         = $method['search']['value'];
            $order          = $method['order'];
            $draw           = $method['draw'];

            if ($sekolah == 0) {
                if (!empty($search)) {
                    $criteria   = array(
                                        '$or' => array(
                                                    array('nama'=>new MongoRegex( '/'.$search.'/i')),
                                                    array('kode'=>new MongoRegex( '/'.$search.'/i'))
                                        )
                                    );
                }else{
                    $criteria   = array();
                }
            }else{
                if (!empty($search)) {
                    $criteria   = array(
                                        '$or' => array(
                                                    array('sekolah'=>$sekolah),
                                                    array('nama'=>new MongoRegex( '/'.$search.'/i')),
                                                    array('kode'=>new MongoRegex( '/'.$search.'/i'))
                                        )
                                    );
                }else{
                    $criteria   = array(
                                        '$or' => array(
                                                    array('sekolah'=>$sekolah)
                                        )
                                    );
                }
            }

            $hasil      = $kelasClass->GetAll($skip, $limit, $criteria, $order);

            $response   = array(
                            "draw"            => intval( $draw ),
                            "recordsTotal"    => intval( $hasil['total'] ),
                            "recordsFiltered" => intval( $hasil['filter'] ),
                            "data"            => $hasil['data']
                        );

            $Json   = json_encode($response);
            header('Content-Type: application/json');

            echo $Json;
            // print_r($hasil);
        }
		
		if($method['action'] == 'getClassbySchool'){
            $kelasClass = new Kelas();
            $id         = $method['ID'];

            $data   = $kelasClass->getClasslistBySchool($id);

            // foreach ($data as $key => $value) {
            //     $listAnggota = $kelasClass->getlistAnggota($id);
            // }
            
            $resp   = array('data'=>$data);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }
		
		if($method['action'] == 'getMemberbyClass'){
            $kelasClass = new Kelas();
            $kelas	= $method['kelas'];
			$peran	= $method['peran'];

            $data   = $kelasClass->getMemberlistByClass($kelas, $peran);

            // foreach ($data as $key => $value) {
            //     $listAnggota = $kelasClass->getlistAnggota($id);
            // }
            
            $resp   = array('data'=>$data);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }

        if($method['action'] == 'getbySchool'){
            $kelasClass = new Kelas();
            $id         = $method['ID'];
            $peran      = $method['peran'];

            $data   = $kelasClass->getlistBySchool($id,$peran);

            // foreach ($data as $key => $value) {
            //     $listAnggota = $kelasClass->getlistAnggota($id);
            // }
            
            $resp   = array('data'=>$data);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }

        if($method['action'] == 'getActivity'){
            $kelasClass     = new Kelas();

            $kelas      = $method['user'];
            $waktu      = $method['waktu'];

            $data       = $kelasClass->GetLog1($kelas, $waktu);
            $resp       = array('data'=>$data);

            // $response   = array(
            //                 "draw"            => intval( $draw ),
            //                 "recordsTotal"    => intval( $data['total'] ),
            //                 "recordsFiltered" => intval( $data['filter'] ),
            //                 "data"            => $data['data']
            //             );

            // $Json       = json_encode($response);
            $Json       = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
            // print_r($limit);
        }
		
		if($method['action'] == 'getActivity2'){
            $kelasClass     = new Kelas();

            $kelas      = $method['kelas'];
            $peran      = $method['peran'];
            $waktu      = $method['tahun'].'-'.$method['bulan'];

            $data       = $kelasClass->GetLogKelas($kelas, $peran, $waktu);
            $resp       = array('data'=>$data);

            // $response   = array(
            //                 "draw"            => intval( $draw ),
            //                 "recordsTotal"    => intval( $data['total'] ),
            //                 "recordsFiltered" => intval( $data['filter'] ),
            //                 "data"            => $data['data']
            //             );

            // $Json       = json_encode($response);
            $Json       = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
            // print_r($limit);
        }

        if($method['action'] == 'getdetailActivity'){
            $kelasClass     = new Kelas();

            $user       = $method['id'];
            $kelas      = $method['kelas'];
            $waktu      = $method['waktu'];
            

            $data       = $kelasClass->GetLog($user,$kelas,$waktu);
            $resp       = array('data'=>$data);

            // $response   = array(
            //                 "draw"            => intval( $draw ),
            //                 "recordsTotal"    => intval( $data['total'] ),
            //                 "recordsFiltered" => intval( $data['filter'] ),
            //                 "data"            => $data['data']
            //             );

            // $Json       = json_encode($response);
            $Json       = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
            // print_r($limit);
        }
           

        // if($method['action'] == 'getActivity'){
        //     $kelasClass     = new Kelas();

        //     $kelas          = $method['kelas'];
        //     $skip           = $method['start'];
        //     $limit          = $method['length'];
        //     $draw           = $method['draw'];

        //     $data       = $kelasClass->GetLog($kelas, $skip, $limit);

        //     $response   = array(
        //                     "draw"            => intval( $draw ),
        //                     "recordsTotal"    => intval( $data['total'] ),
        //                     "recordsFiltered" => intval( $data['filter'] ),
        //                     "data"            => $data['data']
        //                 );

        //     $Json       = json_encode($response);
        //     header('Content-Type: application/json');

        //     echo $Json;
        //     // print_r($limit);
        // }

        if($method['action'] == 'showAll'){
            $catch  = $table->find(array());
            foreach ($catch as $row) {
                $data[]   = $row;
            }
            $count  = $catch->count();
            $resp   = array('count'=>$count, 'data'=>$data);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }

        if($method['action'] == 'insertTugas'){
            $tugasClass     = new Tugas();

            $status         = $tugasClass->insertNilai($method['id_user'], $method['id_tugas'], $method['nilai'], $method['catatan'], $method['user'], $method['kelas']);

            $resp   = array('status'=>$status);
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }

        if($method['action'] == 'remv'){
            $userClass  = new Kelas();
            $data       = $userClass->RemoveData($method['ID']);

            if ($data) {
                // //------ Menulis LOG ---------
                // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_kelas"=>"$method[kelas]", "aksi"=>"30", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));
                $resp   = array('response'=>'Berhasil!', 'message'=>'Data sudah terhapus!', 'icon'=>'success');
            }else{
                $resp   = array('response'=>'Gagal!', 'message'=>'Data belum terhapus!', 'icon'=>'error');
            }
            
            $Json   = json_encode($resp);
            header('Content-Type: application/json');

            echo $Json;
        }
    }
}

?>