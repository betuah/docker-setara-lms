<?php
require("../../../setting/connection.php");

$method	= $_REQUEST;
$table  = $db->posting;

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $resp   = array('data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

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

	if($method['action'] == 'showList'){
        $catch  = $table2->find(array("id_user" => $method['ID']));
		$count  = $catch->count();
		if($count > 0){
			foreach ($catch as $row) {
				// $data[]   = $row;
				$id_kelas	= new MongoId($row['id_kelas']);
				$catch2 	= $table->find(array("_id" => $id_kelas));
				// $catch2 	= $table->find(array('$or' => array(
													// array("_id" => $id_kelas),
													// array("creator"=>$method['ID'])
											// )));
				foreach ($catch2 as $row2) {
					$data[]   = $row2;
				}
				$count  = $catch2->count();
			}
		}else{
			$data	= [];
		}
		// $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);
        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}
}
?>
