<?php
session_start();
require("../setting/connection-log.php");

if(isset($_SESSION['lms_id'])){
	//------ Menulis LOG ---------
	$log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"", "aksi"=>"3", "id_data"=>"", "date_created"=>date('Y-m-d H:i:s')));
	session_destroy();
}

header('Location: ../kesetaraan');
?>
<script>
	document.cookie = 'my_cookie=; path=/url-API; domain=setara.kemdikbud.go.id; expires=' + new Date(0).toUTCString();
</script>
