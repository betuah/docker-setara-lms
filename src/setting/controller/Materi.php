<?php

class Materi
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'materi';
            $this->db = $db;
            $this->dbLog = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function getTotalMateri($idModul){
        $query  = $this->table->find(array("id_modul" => $idModul));
        return $query;
    }

    public function getInfoMateri($idMateri){
		$ID     = new MongoId($idMateri);
        $query  = $this->table->findOne(array("_id" => $ID));
        return $query;
    }

    public function addMateri($idModul, $judul, $materi, $status, $kelas, $user){
        $newID  = "";
        $insert = array("id_modul"=>$idModul, "judul" => $judul, "isi" => $materi, "file"=>"", "status"=>$status, "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));

        $this->table->insert($insert);

        if ($insert) {
            $newID  = $insert['_id'];
            $status     = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"21", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else{
            $status     = "Failed";
        }

        $result = array("status" => $status, "IDModul" => $idModul);
        return $result;
    }

    public function updateMateri($idMateri, $judul, $materi, $status, $kelas){

        $update   = array("judul"=>$judul, "isi"=>$materi, "status"=>$status, "date_modified"=>date('Y-m-d H:i:s'));

        try {
            $this->table->update(array("_id" => new MongoId($idMateri)), array('$set' => $update));
            $status     = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"22", "id_data"=>"$idMateri", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
        } catch(MongoCursorException $e) {
            $status     = "Failed";
        }

        $result = array("status" => $status, "IDMateri" => $idMateri);
        return $result;
    }

    public function getComment($idMateri){
        $query =  $this->db->materi_komentar->find(array("id_materi" => "$idMateri"))->sort(array('date_created' => 1));
        $count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $data[$index] = $isi;
                $userID = new MongoId($isi['creator']);
                $query2 = $this->db->user->findOne(array('_id' => $userID));
                $data[$index]['user']       = $query2['nama'];
                $data[$index]['user_foto']  = $query2['foto'];
            }
        }

        $result = array("count" => $count, "data"=>$data);
        return $result;
    }

    public function getCommentReply($idComment){
        $query =  $this->db->materi_komentar->find(array("id_reply" => "$idComment"));
        $count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $data[$index] = $isi;
                $userID = new MongoId($isi['creator']);
                $query2 = $this->db->user->findOne(array('_id' => $userID));
                $data[$index]['user']       = $query2['nama'];
                $data[$index]['user_foto']  = $query2['foto'];
            }
        }

        $result = array("count" => $count, "data"=>$data);
        return $result;
    }

}

?>
