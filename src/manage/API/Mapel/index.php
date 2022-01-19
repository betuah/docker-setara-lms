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
        $kelasClass     = new Mapel();

        $skip           = $method['start'];
        $limit          = $method['length'];
        $kelas          = $method['kelas'];
        $search         = $method['search']['value'];
        $order          = $method['order'];
        $draw           = $method['draw'];

        if ($kelas != 0) {
            if (!empty($search)) {
                $criteria   = array(
                                    '$or' => array(
                                                array('id_kelas'=>$kelas),
                                                array('nama'=>new MongoRegex( '/'.$search.'/i'))
                                    )
                                );
            }else{
                $criteria   = array(
                                    '$or' => array(
                                                array('id_kelas'=>$kelas)
                                    )
                                );
            }
        }else{
            if (!empty($search)) {
                $criteria   = array(
                                    '$or' => array(
                                                array('nama'=>new MongoRegex( '/'.$search.'/i'))
                                    )
                                );
            }else{
                $criteria   = '';
            }
        }

        $data       = $kelasClass->GetAll($skip, $limit, $criteria, $order);

        $response   = array(
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
        $userClass  = new Mapel();
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

?>