<?php

class Provkot
{
    public function __construct() {
        try {
            global $db;
            $tbprov = 'provinsi';
            $tbkota = 'kota';
            $this->db   = $db;
            $this->table1 = $this->db->$tbprov;
            $this->table2 = $this->db->$tbkota;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    // public function getInfoMapel($idMapel){
    // $ID     = new MongoId($idMapel);
    // $query  = $this->table1->findOne(array("_id" => $ID));
    // if($query['_id']){
    //   $query1 = $this->db->modul->find(array("id_mapel" => $idMapel))->count();
    //   $query['modul'] = $query1;
    // }
    //     return $query;
    // }

    public function getListProv(){
        $query =  $this->table1->find()->sort(array("nama_provinsi" => 1));
        return $query;
    }
    public function getListKotabyProv($id){
      $criteria = array("id_provinsi" => $id);
        $query =  $this->table2->find($criteria);
        return $query;
    }

    public function getProvinsi($id){
      $criteria = array("id_provinsi" => $id);
        $query =  $this->table1->findOne($criteria);
        return $query;
    }

    public function getKota($id){
      $criteria = array("id_kab_kot" => $id);
        $query =  $this->table2->findOne($criteria);
        return $query;
    }

    public function addMapel($nama, $kelas, $user){
        $newID  = "";
        $insert = array("id_kelas"=>$kelas, "nama" => $nama, "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                  $this->table1->insert($insert);
        $newID  = $insert['_id'];
        if ($newID) {
            $status     = "Success";
            $message    = "Mata Pelajaran $nama berhasil ditambahkan!";
        }else {
            $status     = "Failed";
            $message    = "Mata Pelajaran $nama gagal ditambahkan!";
        }

        $result = array("status" => $status, "message"=>$message, "IDMapel" => $newID);
        return $result;
    }

    public function updateMapel($nama, $mapel){
        $update     = array('$set' => array("nama"=>$nama, "date_modified"=>date('Y-m-d H:i:s')));

          try {
              $this->table1->update(array("_id" => new MongoId($mapel)), $update);
              $status   = "success";
              $judul    = "Berhasil!";
              $message  = "Pengaturan Mata Pelajaran berhasil disimpan.";
          } catch(MongoCursorException $e) {
              $status   = "error";
              $judul    = "Maaf!";
              $message  = "Pengaturan Mata Pelajaran gagal disimpan.";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDMapel" => $mapel);
        return $result;
    }

}

?>
