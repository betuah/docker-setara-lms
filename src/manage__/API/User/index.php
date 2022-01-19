<?php
require("../../../setting/connection.php");
require("../../../setting/connection-log.php");
spl_autoload_register(function ($class) {
  include '../../controller/' .$class . '.php';
});

$method = $_REQUEST;
$table  = $db->tugas;

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $resp   = array('data'=>$data);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'getAll'){
        $userClass      = new User();

        $skip           = $method['start'];
        $limit          = $method['length'];
        $search         = $method['search']['value'];
        $order          = $method['order'];
        $draw           = $method['draw'];

        if (!empty($search)) {
            $criteria   = array(
                                '$or' => array(
                                            array('nama'=>new MongoRegex( '/'.$search.'/i')),
                                            array('sekolah'=>new MongoRegex( '/'.$search.'/i'))
                                )
                            );
        }else{
            $criteria   = '';
        }

        $data           = $userClass->GetAllUser($skip, $limit, $criteria, $order);

        $response = array(
                        "draw"            => intval( $draw ),
                        "recordsTotal"    => intval( $data['total'] ),
                        "recordsFiltered" => intval( $data['filter'] ),
                        "data"            => $data['data']
                    );

        $Json   = json_encode($response);
        header('Content-Type: application/json');

        echo $Json;
        // print_r($limit);
    }

    if($method['action'] == 'remv'){
        $userClass  = new User();
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

    // if($method['action'] == 'showAll'){
    //     $catch  = $table->find(array());
    //     foreach ($catch as $row) {
    //         $data[]   = $row;
    //     }
    //     $count  = $catch->count();
    //     $resp   = array('count'=>$count, 'data'=>$data);
    //     $Json   = json_encode($resp);
    //     header('Content-Type: application/json');

    //     echo $Json;
    // }

    // if($method['action'] == 'insertTugas'){
    //     $tugasClass     = new Tugas();

    //     $status         = $tugasClass->insertNilai($method['id_user'], $method['id_tugas'], $method['nilai'], $method['catatan'], $method['user'], $method['kelas']);

    //     $resp   = array('status'=>$status);
    //     $Json   = json_encode($resp);
    //     header('Content-Type: application/json');

    //     echo $Json;
    // }

}

?>