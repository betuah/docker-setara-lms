<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
require("../setting/connection.php");
require("../setting/connection-log.php");

$method	= $_REQUEST;
$table  = $db->user;

if(isset($method['action'])){
    if($method['action'] == 'login'){
        $username = mysql_escape_string(trim(strip_tags(stripslashes($method['username']))));
        $password = mysql_escape_string(trim(strip_tags(stripslashes($method['password']))));

        $data   = $table->findOne(['username' => $username]);
        if (!is_null($data)) {
            $hash = $data['password'];
            if(password_verify($password,$hash)){
                if ($data['status'] == 'guru' || $data['status'] == 'siswa') {
                    if($data['username'] == 'direktorat'){
                        $_SESSION['admin_id']       = $data['_id'];
                        $_SESSION['admin_username'] = $data['username'];
                        $_SESSION['admin_name']     = $data['nama'];
                        $_SESSION['admin_status']   = 'superadmin';
                    }
                    $_SESSION['lms_id']         = $data['_id'];
                    $_SESSION['lms_username']   = $data['username'];
                    $_SESSION['lms_name']       = $data['nama'];
                    $_SESSION['lms_status']     = $data['status'];
                    $_SESSION['lms_sekolah']    = $data['sekolah'];
                    $_SESSION['lms_update']     = $data['update'];
                    $page   = 'dashboard';
                }elseif ($data['status'] == 'superadmin' || $data['status'] == 'admin' || $data['status'] == 'pengelola' || $data['status'] == 'pengawas' || $data['status'] == 'kepsek') {
                    $_SESSION['admin_id']       = $data['_id'];
                    $_SESSION['admin_username'] = $data['username'];
                    $_SESSION['admin_name']     = $data['nama'];
                    $_SESSION['admin_status']   = $data['status'];
                    $_SESSION['admin_mengawasi']   = @$data['mengawasi'];
                    $_SESSION['lms_id']         = $data['_id'];
                    $_SESSION['lms_username']   = $data['username'];
                    $_SESSION['lms_name']       = $data['nama'];
                    $_SESSION['lms_status']     = $data['status'];
                    $_SESSION['lms_sekolah']    = $data['sekolah'];
                    $_SESSION['lms_update']     = $data['update'];
                    //$page   = 'manage';
                    $page   = 'dashboard';
                }


                //------ Menulis LOG ---------
                $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$data[_id]", "id_sekolah"=>$data['sekolah'], "id_kelas"=>"", "aksi"=>"2", "id_data"=>"", "date_created"=>date('Y-m-d H:i:s')));
                $resp = array('response'=>'Success!', 'message'=>'Berhasil Masuk !', 'icon'=>'success', 'page'=>$page);
            }else{
                // $resp = array('response'=>$password, 'message'=>'Kata sandi yang anda masukkan Salah!', 'icon'=>$username);
                $resp = array('response'=>'Gagal!', 'message'=>'Kata sandi yang anda masukkan Salah!', 'icon'=>'error');
            }
        }else{
            $resp = array('response'=>'Maaf!', 'message'=>'Akun yang anda masukkan belum terdaftar!', 'icon'=>'error');
        }
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}
}

?>
