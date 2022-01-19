<?php

class Diskusi
{
    public function __construct() {
        try {
            global $db;
            $tableName      = 'diskusi_modul';
            $this->db       = $db;
            $this->table    = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function getInfoDiskusi($idModul){
		$ID     = new MongoId($idModul);
        $query  = $this->table->findOne(array("_id" => $ID));
        return $query;
    }

    public function getListbyModul($idModul){
        $query =  $this->table->find(array("id_modul" => "$idModul", "id_reply" => "NULL"))->sort(array('date_created' => -1));;
        $count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $data[$index] = $isi;
                $userID	= new MongoId($isi['creator']);
                $query2 = $this->db->user->findOne(array('_id' => $userID));
                $data[$index]['user']       = $query2['nama'];
                $data[$index]['user_foto']  = $query2['foto'];
            }
        }

        $result = array("count" => $count, "data"=>$data);
        return $result;
    }

    public function getListReply($idComment){
        $query =  $this->table->find(array("id_reply" => "$idComment"));
        $count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $data[$index] = $isi;
                $userID	= new MongoId($isi['creator']);
                $query2 = $this->db->user->findOne(array('_id' => $userID));
                $data[$index]['user']       = $query2['nama'];
                $data[$index]['user_foto']  = $query2['foto'];
            }
        }

        $result = array("count" => $count, "data"=>$data);
        return $result;
    }

    public function addDiskusi($idModul, $idReply, $judul, $deskripsi, $creator){

        $insert = array("id_modul"=>"$idModul", "id_reply"=>"$idReply", "judul"=>"$judul", "deskripsi"=>"$deskripsi", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $this->table->insert($insert);
        if ($insert) {
            $status = "Success";
        }else {
            $status = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }
}

?>
