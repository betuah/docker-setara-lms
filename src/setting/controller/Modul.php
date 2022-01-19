<?php

class Modul
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName      = 'modul';
            $this->db       = $db;
            $this->dbLog    = $dbLog;
            $this->table    = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function getInfoModul($idModul){
		$ID     = new MongoId($idModul);
        $query  = $this->table->findOne(array("_id" => $ID));
        return $query;
    }

    public function getStatusMateri($idModul, $user){
        $query =  $this->db->modul_kumpul->findOne(array("id_modul"=>"$idModul", "id_user"=>"$user"));
        return $query;
    }

    public function getListbyMapel($idMapel){
        $query  = $this->table->find(array("id_mapel" => $idMapel))->sort(array("date_created"=>1));
        return $query;
    }

    public function getLearningPath($idmodul, $user){

        if ($idmodul != 0) {
            $nilaiModul     = 0;
            $nilaiTugas     = 0;
            $nilaiUjian     = 0;

            $modul          = $this->table->findOne(array("_id"=> new MongoId($idmodul)));
            if ($modul) {
                $cekNilaiModul  = $this->db->modul_kumpul->findOne(array("id_modul"=>"$idmodul", "id_user"=>"$user"));
                $nilaiModul     += $cekNilaiModul['nilai'];
            }else {
                $nilaiModul     = 100;
            }

            $tugasModul =  $this->db->tugas->find(array("id_modul"=>"$idmodul"));
            $jumlahTugas = $tugasModul->count();
            if ($jumlahTugas > 0) {
                $kumpulTugas = 0;
                foreach ($tugasModul as $tugas) {
                    $cekNilaiTugas  = $this->db->tugas_kumpul->findOne(array("id_tugas"=>"$tugas[_id]", "id_user"=>"$user"));
                    $nilaiTugas     = $nilaiTugas + $cekNilaiTugas['nilai'];
                    $nilai['tugas']['nama'] = $tugas['nama'];
                    $nilai['tugas']['nilai']= $cekNilaiTugas['nilai'];
                    $kumpulTugas++;
                }
                $totalTugas = round(($nilaiTugas/$jumlahTugas), 2);
            }else {
                $totalTugas = 100;
            }

            /* -- New -- */
            $quizModul =  $this->db->quiz->find(array("id_modul"=>"$idmodul"));
            $jumlahQuiz = $quizModul->count();
            if ($jumlahQuiz > 0) {
                $kumpulQuiz = 0;
                foreach ($quizModul as $quiz) {
                    $jumlah_soal   = $this->db->soal->find(array('id_paket'=> "$quiz[id_paket]"))->count();
                    $cekNilaiQuiz  = $this->db->kumpul_quiz->findOne(array("id_quiz"=>"$quiz[_id]", "id_user"=>"$user"));

                    $nilaiQuiz     = round(($cekNilaiQuiz['nilai']/$jumlah_soal)*100, 2);
                    if($nilaiQuiz > 100){
                        $nilaiQuiz = $cekNilaiQuiz['nilai'];
                    }
                    $nilaiUjian += $nilaiQuiz;
                    $kumpulQuiz++;
                }
                $totalUjian = round(($nilaiUjian/$jumlahQuiz), 2);
            }else {
                $totalUjian = 100;
            }

            $persentaseModul = $modul['nilai']['materi'];
            $persentaseTugas = $modul['nilai']['tugas'];
            $persentaseUjian = $modul['nilai']['ujian'];
            $nilaiMinimal    = $modul['nilai']['minimal'];

            $nilaiAkhirModul    = $persentaseModul == 0 ? 0 : round($nilaiModul * ($persentaseModul/100), 2);
            $nilaiAkhirTugas    = $persentaseTugas == 0 ? 0 : round($totalTugas * ($persentaseTugas/100), 2);
            $nilaiAkhirUjian    = $persentaseUjian == 0 ? 0 : round($totalUjian * ($persentaseUjian/100), 2);

            $hasil  = round($nilaiAkhirModul + $nilaiAkhirTugas + $nilaiAkhirUjian, 2);

            $nilai['materi']    = $nilaiAkhirModul;
            $nilai['ujian']     = $nilaiAkhirUjian;
            $nilai['akhir']     = $hasil;

            $status = $hasil >= $nilaiMinimal ? 'Terbuka' : 'Tidak';
        }else {
            $hasil  = 100;

            $nilai['materi']    = 100;
            $nilai['ujian']     = 100;
            $nilai['akhir']     = $hasil;

            $status = 'Terbuka';
        }

        $result = array("prasyarat"=>$idmodul, "status" => $status, "hasil" => $hasil, "nilai"=>$nilai);
        return $result;
    }

    // public function getNilaiTugas(){
    //
    // }

    public function addModul($kiriman, $kelas, $mapel, $user){
        $nama   = $kiriman['namamodul'];
        $syarat = $kiriman['prasyaratmodul'];
        $nilai1 = $kiriman['nilaimateri'];
        $nilai2 = $kiriman['nilaitugas'];
        $nilai3 = $kiriman['nilaiujian'];
        $nilai4 = $kiriman['nilaiminimal'];

        $newID  = "";
        $insert = array("id_mapel"=>$mapel, "nama"=>$nama, "prasyarat"=>$syarat, "nilai"=>array("materi"=>$nilai1, "tugas"=>$nilai2, "ujian"=>$nilai3, "minimal"=>$nilai4), "creator"=>"$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                  $this->table->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"18", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $result = array("status" => $status, "IDMapel" => $mapel);
        return $result;
    }

    public function setModul($kiriman, $kelas, $mapel){
        $id     = $kiriman['idmodul'];
        $nama   = $kiriman['namamodul'];
        $syarat = $kiriman['prasyaratmodul'];
        $nilai1 = $kiriman['nilaimateri'];
        $nilai2 = $kiriman['nilaitugas'];
        $nilai3 = $kiriman['nilaiujian'];
        $nilai4 = $kiriman['nilaiminimal'];

        $newID  = "";
        $update = array("nama" => $nama, "prasyarat"=>$syarat, "nilai"=>array("materi"=>$nilai1, "tugas"=>$nilai2, "ujian"=>$nilai3, "minimal"=>$nilai4), "date_modified"=>date('Y-m-d H:i:s'));
        $sukses = $this->table->update(array("_id"=> new MongoId($id)), array('$set'=>$update));
        if ($sukses) {
            $status = "Success";

            //------ Menulis LOG ---------
            $log  = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$_SESSION[lms_id]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"19", "id_data"=>"$mapel", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $result = array("status" => $status, "IDMapel" => $mapel);
        // $result = array("status" => $kiriman, "balikan" => $kiriman['idmodul']);
        return $result;
    }

}

?>
