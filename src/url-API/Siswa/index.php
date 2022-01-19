<?php
require("../../setting/connection.php");

$method	= $_REQUEST;
$table  = $db->user;

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
        $resp   = array('data'=>$data, 'count'=>$count);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

	if($method['action'] == 'insert'){
        $nama   = mysql_escape_string($method['text']);
        $kode   = mysql_escape_string($method['text']);
        $stat   = mysql_escape_string($method['text']);
        $user   = $_SESSION['lms_id'];

        $data	= array('nama' => 'A', 'kode'=> 'B', 'status'=>'Open', 'pembuat'=>'C');
		$table->insert($data);

	}

	if($method['action'] == 'update'){


        $resp   = array('text1'=>$text1, 'text2'=>$text2, 'text3'=>$text3);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

	if($method['action'] == 'delete'){


	}
}

?>
