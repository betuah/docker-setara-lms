<?php

class Tugas
{
    public function __construct() {
        try {
            $tableName = 'tugas';
            global $db;
            global $dbLog;
            $this->db = $db;
            $this->dbLog = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function acakKodeTugas($jumlahKarakter)
    {
        $karakter   = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $hasil      = '';
        for ($i=0; $i < $jumlahKarakter; $i++) {
            $acak   = rand(0, strlen($karakter)-1);
            $hasil  .= $karakter{$acak};
            # code...
        }
        
        $cek_kode_tugas = $this->db->tugas->find(array("kode" => $hasil))->count();
        $cek_kode_quiz  = $this->db->quiz->find(array("kode" => $hasil))->count();

        if ($cek_kode_tugas > 0 OR $cek_kode_quiz > 0) {
            $this->acakKodeTugas(6);
        }
        # code...
        return $hasil;
    }

    public function getDetailTugas($id){
        $ID     = new MongoId($id);
        $query  = $this->table->findOne(array("_id" => $ID));

        return $query;
    }

    public function isSumbmitted($idUser, $idTugas){
        $query  = $this->db->tugas_kumpul->find(array("id_user" => $idUser, "id_tugas" => $idTugas))->count();

        if($query > 0){
            return "1";
        }else{
            return "0";
        }
    }

    public function getInfoTugas($idModul){
        $query  = $this->table->findOne(array("id_modul" => $idModul));
        return $query;
    }

    public function getListTugas($idModul){
        $query =  $this->table->find(array("id_modul"=>"$idModul"));
        return $query;
    }

    public function getStatusTugas($tugas, $user){
        $query =  $this->db->tugas_kumpul->findOne(array("id_tugas"=>"$tugas", "id_user"=>"$user"));
        return $query;
    }

    public function getStatusQuiz($quiz, $user){
        $query =  $this->db->kumpul_quiz->findOne(array("id_quiz"=>"$quiz", "id_user"=>"$user"));
        return $query;
    }

    public function getStatusQuizNew($idQuiz, $idPaket, $idUser){
        $jumlah_soal        = $this->db->soal->find(array("id_paket" => "$idPaket"))->count();
        $jawaban_benar_user = $this->db->jawaban_user->find(array("id_user" => "$idUser", "id_quiz" => "$idQuiz", "status" => "benar"))->count();
        $skala              = 100;

        $nilai_quiz         = ($jawaban_benar_user*$skala)/$jumlah_soal;

        return $nilai_quiz;
    }

    public function addTugas($kelas, $idTugas, $nama, $jenis, $durasi, $deskripsi, $begin, $deadline, $user, $sekolah, $file){

        if($file['name'] != ""){
            $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name	= "$user".'_'.date('dmYHIs').".".$ext;
            $folderDest	= 'assets/dokumen/'.$file_name;

            move_uploaded_file($file['tmp_name'], $folderDest);
        }else{
            $file_name  = "";
        }

        $kode = $this->acakKodeTugas(6);
        if(!isset($durasi) OR $jenis=="0"){
            $durasi = 0;
        }

        $insert = array("id_modul"=>$idTugas, "nama" => $nama, "jenis" => "$jenis", "durasi" => "$durasi", "kode" => "$kode", "deskripsi" => $deskripsi, "begin" => $begin,"deadline" => $deadline, "creator" => "$user", "file" => $file_name, "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));

        $this->table->insert($insert);

        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"27", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else{
            $status     = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

    public function updateTugas($kelas, $idTugas, $nama, $jenis, $durasi, $kode, $deskripsi, $begin, $deadline, $user, $sekolah, $file){

        if(empty($kode)){
            $kode = $this->acakKodeTugas(6);
        }

        if(!isset($durasi) OR $jenis=="0"){
            $durasi = 0;
        }

        if($file['name'] != ""){
            $old_file   =  $this->table->findOne(array('_id' => new MongoId($idTugas)))['file'];
            $folderDest	= 'assets/dokumen/'.$old_file;
            unlink($folderDest);

            $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name	= "$user".'_'.date('dmYHIs').".".$ext;
            $folderDest	= 'assets/dokumen/'.$file_name;

            move_uploaded_file($file['tmp_name'], $folderDest);

            $update   = array("nama" => $nama, "jenis" => "$jenis", "durasi" => "$durasi", "kode" => "$kode", "deskripsi" => $deskripsi, "begin" => $begin, "deadline" => $deadline, "file" => $file_name, "date_modified"=>date('Y-m-d H:i:s'));
        }else{
            $update   = array("nama" => $nama, "jenis" => "$jenis", "durasi" => "$durasi", "kode" => "$kode", "deskripsi" => $deskripsi, "begin" => $begin, "deadline" => $deadline, "date_modified"=>date('Y-m-d H:i:s'));
        }

        try {
            $this->table->update(array("_id" => new MongoId($idTugas)), array('$set' => $update));
            $status     = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"28", "id_data"=>"$idTugas", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
        } catch(MongoCursorException $e) {
            $status     = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

    public function submitTugas($user, $sekolah, $idTugas, $deskripsi, $file, $kelas){

        if($file['name'] != ""){
            $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name	= "$user".'_'.date('dmYHIs').".".$ext;
            $folderDest	= 'assets/dokumen/'.$file_name;

            move_uploaded_file($file['tmp_name'], $folderDest);
        }else{
            $file_name  = "";
        }

        $insert = array("id_user"=>"$user", "id_tugas" => $idTugas, "deskripsi" => $deskripsi, "file" => $file_name, "nilai" => "0", "catatan" => "", "status" => "0", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));

        $this->db->tugas_kumpul->update(array("id_user" => "$user", "id_tugas" => $idTugas), array('$set' => $insert), array("upsert" => true));

        if ($insert) {
            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"30", "id_data"=>"$idTugas", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));

            $status     = "Success";
        }else{
            $status     = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

    public function updateTugasSiswa($user, $sekolah, $idTugas, $deskripsi, $file, $kelas){

        if($file['name'] != ""){
            $old_file   = $this->db->tugas_kumpul->findOne(array("id_user" => $user, "id_tugas" => $idTugas))['file'];
            $folderDest	= 'assets/dokumen/'.$old_file;
            unlink($folderDest);

            $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name	= "$user".'_'.date('dmYHIs').".".$ext;
            $folderDest	= 'assets/dokumen/'.$file_name;

            move_uploaded_file($file['tmp_name'], $folderDest);
        }else{
            $file_name  = "";
        }

        $insert = array("id_user"=>"$user", "id_tugas" => $idTugas, "deskripsi" => $deskripsi, "file" => $file_name, "nilai" => "0", "catatan" => "", "status" => "0", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));

        $this->db->tugas_kumpul->update(array("id_user" => "$user", "id_tugas" => $idTugas), array('$set' => $insert), array("upsert" => true));

        if ($insert) {
            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"30", "id_data"=>"$idTugas", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));

            $status     = "Success";
        }else{
            $status     = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

    public function insertNilai($idUser, $sekolah, $idTugas, $nilai, $catatan, $guru, $kelas){


        $update     = array("nilai" => $nilai, "catatan" => $catatan, "status" => "1", "date_modified"=>date('Y-m-d H:i:s'));

        try {

            $this->db->tugas_kumpul->update(array("id_user" => "$idUser", "id_tugas" => $idTugas), array('$set' => $update), array("upsert" => true));
            $status     = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$guru", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"31", "id_data"=>"$idTugas", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
        } catch(MongoCursorException $e) {

            $status     = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

}

?>
