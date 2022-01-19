<?php

class Soal
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'soal';
            $this->db = $db;
            $this->dbLog   = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function getInfoSoal($idSoal){
		$ID     = new MongoId($idSoal);
        $query  = $this->table->findOne(array("_id" => $ID));

        return $query;
    }

    public function getListbyQuiz($idQuiz){
        $query =  $this->table->find(array("id_paket"=>"$idQuiz"));
        // if($query["soal"]){
        //     $query1 = $this->table->find(array("id_paket" => $idQuiz))->count();
        //     $query['jmlSoal'] = $query1;
        // }
        return $query;
    }

    public function getListbySoal($idSoal){
        $query =  $this->table->find(array("id_soal"=>"$idSoal"));
        return $query;
    }

    public function getListJawaban($idSoal){
        // print_r("$idSoal");
        $query =  $this->db->opsi_soal->find(array("id_soal"=>"$idSoal"));
        return $query;
    }

    public function getListSoalbyQuiz($idPaket, $random){
        $query      = $this->table->find(array("id_paket"=>"$idPaket"));
        $list_soal  = iterator_to_array($query);
        $keys       = array_keys($list_soal);

        if($random == "1"){
            shuffle($keys);
        }

        foreach ($keys as $key) {
            $random_list_soal[$key] = $list_soal[$key];
        }

        return $random_list_soal;
    }

    public function getListOpsiSoal($idSoal, $random){
        $query =  $this->db->opsi_soal->find(array("id_soal"=>"$idSoal"));
        $list_opsi_soal  = iterator_to_array($query);
        $keys       = array_keys($list_opsi_soal);

        if($random == "1"){
            shuffle($keys);
        }

        foreach ($keys as $key) {
            $random_list_opsi_soal[$key] = $list_opsi_soal[$key];
        }

        return $random_list_opsi_soal;
    }

    public function getSoalbyId($idSoal){
        $query =  $this->table->findOne(array("_id" => new MongoId($idSoal)));
        return $query;
    }

    public function getOpsiJawabanUser($idUser, $idQuiz, $idSoal){
        $query =  $this->db->jawaban_user->findOne(array("id_user" => "$idUser", "id_quiz" => "$idQuiz", "id_soal" => "$idSoal"));
        return $query;
    }

    public function getNumberbyQuiz($idQuiz){
        $query =  $this->table->find(array("id_paket"=>"$idQuiz"))->count();
        return $query;
    }

    public function addSoal($kelas, $soal, $jawaban, $benar, $id_paket, $user){
        $newID  = "";
        $insert = array("id_paket"=>"$id_paket","jenis" => "pg","soal" => $soal, "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
        $paketsoal = $this->table->insert($insert);
        $idSoal = $insert['_id'];
        if ($paketsoal) {
            $b = $benar;
			// $i = 0;
			// echo "benar = ".$benar."<br />";

			foreach ($jawaban as $index=>$jawab) {
				// echo $i .' = '.$benar.'<br />';
				// echo "if ".$benar ."=". $i.")";

				if ($benar == $index) {
					$status = "benar";
				}else{
					$status = "salah";
				}
				$inserts = array("id_soal" => "$idSoal", "text" => $jawab, "status"=>"$status" );
				$inserttag = $this->db->opsi_soal->insert($inserts);

			}

            $hasil  = "Sukses";
            $pesan  = "Berhasil menambahkan soal dan jawaban!";
            $icon   = "success";

            //------ Menulis LOG ---------
            $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"35", "id_data"=>"$idSoal", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));

			// if (isset($_GET['md'])) {
			// 	# code...
			// 	echo "<script>alert('Sukses'); document.location='quiz-action.php?md=".$_GET['md']."&qz=".$_GET['qz']."</script>";
			// }else if (isset($_GET['id'])) {
			// 	# code...
			// 	echo "<script>alert('Sukses'); document.location='paket-detail.php?id=".$_GET['id']."</script>";
			// }
        }else {
            $hasil  = "Gagal";
            $pesan  = "Soal dan jawaban tidak berhasil ditambahkan!";
            $icon   = "error";
        }

        $result = array("hasil"=>$hasil, "pesan"=>$pesan, "icon"=>$icon);
        return $result;
    }

    public function updateSoal($id, $kelas, $soal, $jawaban, $benar, $id_paket, $user){
        $newID  = "";
        $edit = array("id_paket"=>"$id_paket","jenis" => "pg","soal" => $soal, "creator" => "$user", "date_modified"=>date('Y-m-d H:i:s'));
        $paketsoal = $this->table->update(array("_id"=> new MongoId($id)),array('$set'=>$edit));

        if ($paketsoal) {
            $deleteopsi = array("id_soal" => "$id");
            $hapusopsi = $this->db->opsi_soal->remove($deleteopsi);
                $i = 0;
                $b = $benar;
            foreach ($jawaban as $index=>$jawab) {
                // echo $i .' = '.$benar.'<br />';
                // echo "if ".$benar ."=". $i.")";

                if ($benar == $index) {
                    $status = "benar";
                }else{
                    $status = "salah";
                }
                $i = $i+1;
                $inserts = array("id_soal" => "$id", "text" => $jawab, "status"=>"$status" );
                $inserttag = $this->db->opsi_soal->insert($inserts);
            }

            $status1    = "Sukses";
            $pesan      = "Berhasil memperbarui soal dan jawaban!";
            $icon       = "success";

            //------ Menulis LOG ---------
            $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"36", "id_data"=>"$id", "data"=>$edit, "date_created"=>date('Y-m-d H:i:s')));

        }else {
            $status1    = "Gagal";
            $pesan      = "Soal dan jawaban tidak berhasil diperbarui!";
            $icon       = "error";
        }

        $result = array("status"=>$status1, "pesan"=>$pesan, "icon"=>$icon);
        return $result;
    }

    public function addOpsi($nama, $modul, $user){
        $newID  = "";
        $insert = array("nama" => $nama, "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
        $paketsoal = $this->db->paket_soal->insert($insert);
        if ($paketsoal) {
            $status ="Sukses";
        }else {
            $status     = "Failed";
        }

        $result = array("status" => $status, "IDMapel" => $mapel);
        return $result;
    }

}
