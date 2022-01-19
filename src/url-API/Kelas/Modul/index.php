<?php
require("../../../setting/connection.php");
require("../../../setting/connection-log.php");

session_start();

$method = $_REQUEST;
$table  = $db->modul;

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
        $catch  = $table->find(array("id_mapel" => $method['IDKelas']));
        foreach ($catch as $row) {
            $data[]   = $row;
        }
        $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"20", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'updtNMateri'){
        $siswa  = $method['s'];
        $modul  = $method['i'];
        $nilai  = $method['n'];

        $cekNilai   = $db->modul_kumpul->findOne(array('id_modul'=>"$modul", 'id_user'=>"$siswa"));
        if (isset($cekNilai['nilai'])){
            $update = array("nilai" => $nilai, "date_modified"=>date('Y-m-d H:i:s'));
            $sukses = $db->modul_kumpul->update(array("id_user"=>"$siswa", "id_modul"=>"$modul"), array('$set'=>$update));

            if ($sukses) {
                if (isset($method['user'])) {
                    # code...
                    //------ Menulis LOG ---------
                    $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"43", "id_data"=>"$cekNilai[_id]", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
                }else{
                    //------ Menulis LOG ---------
                    $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$siswa", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"48", "id_data"=>"$cekNilai[_id]", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
                }

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }else {
            $insert = array("id_user"=>$siswa, "id_modul"=>"$modul", "nilai"=>"$nilai", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                      $db->modul_kumpul->insert($insert);

            if ($insert) {
                if (isset($method['user'])) {
                    //------ Menulis LOG ---------
                    $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"27", "id_data"=>"$insert[_id]", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
                }else{
                    //------ Menulis LOG ---------
                    $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$siswa", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"48", "id_data"=>"$insert[_id]", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
                }
                # code...

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }
    }

    if($method['action'] == 'updtNTugas'){
        $siswa  = $method['s'];
        $tugas  = $method['i'];
        $nilai  = $method['n'];

        $cekNilai   = $db->tugas_kumpul->findOne(array("id_tugas"=>"$tugas", "id_user"=>"$siswa"));
        if (isset($cekNilai['nilai'])) {
            $update = array("nilai" => $nilai, "date_modified"=>date('Y-m-d H:i:s'));
            $sukses = $db->tugas_kumpul->update(array("id_user"=>"$siswa", "id_tugas"=>"$tugas"), array('$set'=>$update));

            if ($sukses) {
                # code...
                //------ Menulis LOG ---------
                $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"43", "id_data"=>"$cekNilai[_id]", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }else {
            $insert = array("id_user"=>"$siswa", "id_tugas"=>"$tugas", "deskripsi" => "", "file"=>"", "nilai"=>$nilai, "catatan"=>"", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                      $db->tugas_kumpul->insert($insert);

            if ($insert) {
                # code...
                //------ Menulis LOG ---------
                $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"31", "id_data"=>"$insert[_id]", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }
    }

    if($method['action'] == 'updtNUjian'){
        $siswa  = $method['s'];
        $ujian  = $method['i'];
        $nilai  = $method['n'];

        $cekNilai   = $db->kumpul_quiz->findOne(array('id_quiz'=>"$ujian", 'id_user'=>"$siswa"));
        if (isset($cekNilai['nilai'])){
            $update = array("nilai" => $nilai, "date_modified"=>date('Y-m-d H:i:s'));
            $sukses = $db->kumpul_quiz->update(array("id_user"=>"$siswa", "id_quiz"=>"$ujian"), array('$set'=>$update));

            if ($sukses) {
                # code...
                //------ Menulis LOG ---------
                $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"45", "id_data"=>"$cekNilai[_id]", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }else {
            $insert = array("id_user"=>"$siswa", "id_quiz"=>"$ujian", "nilai"=>"$nilai", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                      $db->kumpul_quiz->insert($insert);

            if ($insert) {
                # code...
                //------ Menulis LOG ---------
                $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"47", "id_data"=>"$insert[_id]", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));

                $status = array('response'=>'Berhasil!', 'message'=>'Data berhasil disimpan!', 'icon'=>'success');
            } else {
                $status = array('response'=>'Maaf!', 'message'=>'Data tidak tersimpan!', 'icon'=>'error');
            }

            $Json   = json_encode($status);
            header('Content-Type: application/json');

            echo $Json;
        }
        // echo $cekNilai['nilai'];
    }

}

?>
