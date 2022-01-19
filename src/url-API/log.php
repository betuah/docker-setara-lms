<?php
session_start();
require("../setting/connection-log.php");

$method	= $_REQUEST;

if(isset($method['action'])){
    if($method['action'] == 'access'){
        $table   = $dbLog->log_access;
        $user    = $method['uid'];
        $kls     = $method['kls'];
        $page    = $method['pg'];
        $url     = $method['url'];
        $sekolah = $method['sekolah'];

        $query   = $table->insert(array( "id_user"=>"$user", "id_kelas"=>"$kls", "id_sekolah"=>"$sekolah", "halaman"=>$page, "link"=>$url, "date_created"=>date('Y-m-d H:i:s') ));
        // if ($query) {
        //     # code...
        //     $resp = array('response'=>'Sukses!', 'message'=>'Tercatat!', 'icon'=>'success');
        // }else{
        //     $resp = array('response'=>'Gagal!', 'message'=>'Tidak Tercatat!', 'icon'=>'error');
        // }
        
        $resp   = array('response'=>'Maaf!', 'message'=>'Tidak Menulis ke Log Aktivitas!', 'icon'=>'warning');

		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}
}

?>
