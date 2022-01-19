<?php

class Mapel
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName  = 'mata_pelajaran';
            $this->db   = $db;
            $this->dbLog = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function getInfoMapel($idMapel){
        $ID     = new MongoId($idMapel);
        $query  = $this->table->findOne(array("_id" => $ID));

        if($query['_id']){
  			   $query1	= $this->db->modul->find(array("id_mapel" => $idMapel))->count();
  			   $query['modul'] = $query1;
        }
        return $query;
    }

    public function getListbyKelas($idkelas){
        $query =  $this->table->find(array("id_kelas"=>"$idkelas"));
        return $query;
    }

    public function addMapel($nama, $kelas, $user){
        $newID  = "";
        $insert = array("id_kelas"=>$kelas, "nama" => $nama, "silabus"=>'', "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                  $this->table->insert($insert);
        $newID  = $insert['_id'];
        if ($newID) {
            $status     = "Success";
            $message    = "Mata Pelajaran $nama berhasil ditambahkan!";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"15", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status     = "Failed";
            $message    = "Mata Pelajaran $nama gagal ditambahkan!";
        }

        $result = array("status" => $status, "message"=>$message, "IDMapel" => $newID);
        return $result;
    }

    public function updateMapel($kelas, $nama, $tutor, $mapel){
        $update     = array("nama"=>$nama, "creator"=>"$tutor", "date_modified"=>date('Y-m-d H:i:s'));

          try {
              $this->table->update(array("_id" => new MongoId($mapel)), array('$set' =>$update));
              $status   = "success";
              $judul    = "Berhasil!";
              $message  = "Pengaturan Mata Pelajaran berhasil disimpan.";

              //------ Menulis LOG ---------
              $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"45", "id_data"=>"$mapel", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
          } catch(MongoCursorException $e) {
              $status   = "error";
              $judul    = "Maaf!";
              $message  = "Pengaturan Mata Pelajaran gagal disimpan.";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDMapel" => $mapel);
        return $result;
    }

    public function updateSilabus($teks, $mapel, $kelas){
        $update     = array("silabus"=>$teks, "date_modified"=>date('Y-m-d H:i:s'));

          try {
              $this->table->update(array("_id" => new MongoId($mapel)), array('$set' => $update));
              $status   = "success";
              $judul    = "Berhasil!";
              $message  = "Silabus berhasil disimpan.";

              //------ Menulis LOG ---------
              $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"17", "id_data"=>"$mapel", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
          } catch(MongoCursorException $e) {
              $status   = "error";
              $judul    = "Maaf!";
              $message  = "Silabus gagal disimpan.";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDMapel" => $mapel);
        return $result;
    }

    public function salinMapel($kelas, $mapel, $user){

          try {
              $mapel1   = $this->table->findOne(array('_id' => new MongoId($mapel)));
              $newmapelID   = "";
              $insertMapel  = array("id_kelas"=>"$kelas", "nama" => $mapel1['nama'], "silabus"=>$mapel1['silabus'], "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                        $this->table->insert($insertMapel);
              $newmapelID   = $insertMapel['_id'];
              if ($newmapelID) {
                    $modul1   = $this->db->modul->find(array('id_mapel' => "$mapel"));

                    if ($modul1->count() > 0) {
                        foreach ($modul1 as $isimodul) {
                            $insertModul    = array("id_mapel"=>"$newmapelID", "nama"=>$isimodul['nama'], "prasyarat"=>"0", "nilai"=>array("materi"=>"$isimodul[nilai][materi]", "tugas"=>"$isimodul[nilai][tugas]", "ujian"=>"$isimodul[nilai][ujian]", "minimal"=>"$isimodul[nilai][minimal]"), "creator"=>"$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                            $this->db->modul->insert($insertModul);

                            $newmodulID = $insertModul['_id'];

                            if ($newmodulID) {
                                $materi1    =  $this->db->materi->find(array('id_modul' => "$isimodul[_id]"));

                                if ($materi1->count() > 0) {
                                    foreach ($materi1 as $isimateri) {
                                        $insertMateri   = array("id_modul"=>"$newmodulID", "judul" => $isimateri['judul'], "isi" => $isimateri['isi'], "file"=>"", "status"=>$isimateri['status'], "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                                        $this->db->materi->insert($insertMateri);
                                    }
                                }

                                $tugas1    =  $this->db->tugas->find(array('id_modul' => "$isimodul[_id]"));

                                if ($tugas1->count() > 0) {
                                    foreach ($tugas1 as $isitugas) {
                                        $insertTugas   = array("id_modul"=>"$newmodulID", "nama" => $isitugas['nama'], "deskripsi" => $isitugas['deskripsi'], "deadline" => $isitugas['deadline'], "creator" => "$user", "file" => $isitugas['file'], "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                                        $this->db->tugas->insert($insertTugas);
                                    }
                                }

                                $quiz1    =  $this->db->quiz->find(array('id_modul' => "$isimodul[_id]"));

                                if ($quiz1->count() > 0) {
                                    foreach ($quiz1 as $isiquiz) {
                                        $insertPaket    = array("nama" => $isiquiz['nama'], "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                                        $savePaket      = $this->db->paket_soal->insert($insertPaket);
                                        if ($savePaket) {
                                            $newpaketID  = $insertPaket['_id'];
                                            $insertQuiz = array("id_modul" => "$newmodulID", "id_paket" =>"$newpaketID", "nama" => $isiquiz['nama'], "durasi"=>$isiquiz['durasi'], "instruksi"=> $isiquiz['instruksi'], "start_date"=>$isiquiz['start_date'],"end_date"=>$isiquiz['end_date'], "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'), "status"=>"0", "jenis"=>$isiquiz['jenis'], "random_soal"=>$isiquiz['random_soal'], "random_opsi"=>$isiquiz['random_opsi']);
                                            $saveQuiz = $this->db->quiz->insert($insertQuiz);

                                            $soal1  = $this->db->soal->find(array('id_paket' => $isiquiz['id_paket']));
                                            if ($soal1->count() > 0) {
                                                foreach ($soal1 as $isisoal) {
                                                    $insertSoal = array("id_paket"=>"$newpaketID", "jenis" => "pg", "soal" => $isisoal['soal'], "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                                                    $saveSoal   = $this->db->soal->insert($insertSoal);
                                                    $newsoalID  = $insertSoal['_id'];

                                                    $kunci1     = $this->db->opsi_soal->find(array('id_soal' => "$isisoal[_id]"));
                                                    foreach ($kunci1 as $isikunci) {
                                                        $insertKunci    = array("id_soal" => "$newsoalID", "text" => $isikunci['text'], "status"=>$isikunci['status'] );
                                                        $saveKunci      = $this->db->opsi_soal->insert($insertKunci);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
              }

              $status   = "success";
              $judul    = "Berhasil!";
              $message  = "Mata Pelajaran berhasil di salin ke kelas yang dituju!";
          } catch(MongoCursorException $e) {
              $status   = "error";
              $judul    = "Maaf!";
              $message  = "Mata Pelajaran gagal di salin ke kelas yang dituju!";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDMapel" => $mapel);
        return $result;
    }

}

?>
