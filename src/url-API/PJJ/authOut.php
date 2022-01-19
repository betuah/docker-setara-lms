<?php
session_start();
require("../../setting/connection-log.php");

if(isset($_SESSION['lms_id'])){
	//------ Menulis LOG ---------
	$log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_kelas"=>"", "aksi"=>"3", "id_data"=>"", "date_created"=>date('Y-m-d H:i:s')));
	session_destroy();
}

header('Location: ../lms.php');
?>
