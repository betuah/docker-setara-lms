<?php
session_start();
class Quiz
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'quiz';
            $tableName2 = 'paket Soal';
            $this->db = $db;
            $this->dbLog   = $dbLog;
            $this->db->table = $this->db->$tableName;
            $this->db->table2 = $this->db->$tableName2;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function acakKodeQuiz($jumlahKarakter)
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
            $this->acakKodeQuiz(6);
        }

        # code...
        return $hasil;
    }

    public function getKelasByQuiz($id_user, $kode_quiz)
    {
        $quiz     = $this->db->quiz->findOne(array("kode" => "$kode_quiz", "status" => "1"));
        $tugas    = $this->db->tugas->findOne(array("kode" => "$kode_quiz"));
        $bentuk   = 0;
        $ujian_id = 0;

        if(!empty($quiz) OR !empty($tugas)){
            if(!empty($quiz)){
                $ujian  = $quiz;
                $bentuk = 1;
            }else{
                $ujian  = $tugas;
                $bentuk = 2;
            }
            $ujian_id = $ujian['_id'];

            $id_modul = new MongoId($ujian['id_modul']);
            $modul = $this->db->modul->findOne(array("_id" => $id_modul));

            $id_mapel = new MongoId($modul['id_mapel']);
            $mapel = $this->db->mata_pelajaran->findOne(array("_id" => $id_mapel));

            $id_kelas   = $mapel['id_kelas'];
            $list_kelas = $this->db->anggota_kelas->find(array("id_user" => "$id_user"));
            $akses_quiz = 0;

            foreach($list_kelas as $kelas){
                if($kelas['id_kelas'] == $id_kelas){
                    $akses_ujian = 1;
                }
            }

            if($akses_ujian == 1){
                $status = "Success";
                $result = "Klik tombok OK untuk melanjutkan.";
            }else{
                $status = "Failed";
                $result = "Anda tidak berhak mengikuti Ujian";
            }
        }else{
            $status = "Failed";
            $result = "Kode Ujian tidak ditemukan";
        }

        $result = array("status" => $status, "message"=>$result, "ujian"=>$ujian_id, "bentuk"=>$bentuk);
        return $result;
    }

    public function getInfoQuiz($idQuiz){
		$ID     = new MongoId($idQuiz);
        $query  = $this->db->quiz->findOne(array("_id" => $ID));

        // print_r($query);
        return $query;
    }

    public function getNilaiQuiz($idQuiz, $idUser){
        $query  = $this->db->kumpul_quiz->findOne(array("id_quiz" => "$idQuiz", "id_user" => "$idUser"));

        return $query;
    }

    public function getNilaiQuizNew($idQuiz, $idPaket, $idUser){

        $jumlah_soal        = $this->db->soal->find(array("id_paket" => "$idPaket"))->count();
        $jawaban_benar_user = $this->db->jawaban_user->find(array("id_user" => "$idUser", "id_quiz" => "$idQuiz", "status" => "benar"))->count();
        $skala              = 100;

        return ($jawaban_benar_user*$skala)/$jumlah_soal;
    }

    public function getInfoPaket($idQuiz){
        $ID     = new MongoId($idQuiz);
        $query  = $this->db->paket_soal->findOne(array("_id" => $ID));

        // print_r($query);
        return $query;
    }

    public function getListbyModul($idModul){
        $query =  $this->db->quiz->find(array("id_modul"=>"$idModul"));
        // if($query['_id']){
        //     $query1 = $this->db->quiz->find(array("id_modul" => $idModul))->count();
        //     $query['modul'] = $query1;
        // }
        return $query;
    }

    public function getListbyModul_($idModul){
        $query =  $this->db->quiz->find(array("id_modul"=>"$idModul"));

        $result = array();
        foreach ($query as $value) {
            $jumlah_soal = $this->db->soal->find(array('id_paket' => $value['id_paket']))->count();
            $value += ['jumlah_soal' => $jumlah_soal];
            $result[] = $value;
        }

        return $result;
    }

    public function getListbyModul__($idModul){
        $pipeline = array(
            array(
                '$match' => array(
                    'id_modul' => "$idModul"
                )
            ),
            array(
                '$lookup' => array(
                    'from' => 'soal',
                    'let' => array('paket' => '$id_paket'),
                    'pipeline' => array(
                        array(
                            '$match' => array(
                                '$expr' => array(
                                    '$eq' => array('$id_paket','$$paket')
                                )
                            )
                        ),
                        array(
                            '$group' => array(
                                '_id' => '$id_paket',
                                'jumlah_soal' => array('$sum' => 1)
                            )
                        )
                    ),
                    'as' => 'enrolled_info'
                )
            ),
            array(
                '$replaceRoot' => array(
                    newRoot => array(
                        '$mergeObjects' => array(
                            [
                                '$arrayElemAt' => ['$enrolled_info', 0]
                            ],
                            '$$ROOT'
                        )
                    )
                )
            ),
            array(
                '$project' => array(
                    'enrolled_info' => 0
                )
            ),
        );

        $options = array("explain" => false);

        $results = $this->db->quiz->aggregate($pipeline, $options)['cursor']['firstBatch'];

        return $results;
    }

    public function getListActivebyModul($idModul){
        $query =  $this->db->quiz->find(array("id_modul"=>"$idModul", "status"=>"1"));
        // if($query['_id']){
        //     $query1 = $this->db->quiz->find(array("id_modul" => $idModul))->count();
        //     $query['modul'] = $query1;
        // }
        return $query;
    }

    public function getListbyUser($creator){
        $query =  $this->db->paket_soal->find(array("creator"=>"$creator"));
        // if($query['_id']){
        //     $query1 = $this->db->quiz->find(array("id_modul" => $idModul))->count();
        //     $query['modul'] = $query1;
        // }
        return $query;
    }
    public function addQuiz($nama, $kelas, $modul, $durasi, $mulai, $selesai, $instruksi, $user, $sekolah, $jenis){
        $newID  = "";
        $insert = array("nama" => $nama, "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
        $save = $this->db->paket_soal->insert($insert);
        if ($save) {
            $newID  = $insert['_id'];
            $kode = $this->acakKodeQuiz(6);
            $insert2 = array("id_modul" => $modul,"id_paket" =>"$newID", "nama" => "$nama", "kode"=>"$kode", "durasi"=>"$durasi", "instruksi"=> "$instruksi", "start_date"=>"$mulai","end_date"=>"$selesai", "creator" => "$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'), "status"=>"0", "jenis"=>"$jenis", "random_soal"=>"0", "random_opsi"=>"0");
            $insertquiz = $this->db->quiz->insert($insert2);
            if ($insertquiz) {
                $status ="Sukses";
                $IDQz = $insert2['_id'];

                //------ Menulis LOG ---------
                $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"32", "id_data"=>"$IDQz", "data"=>$insert2, "date_created"=>date('Y-m-d H:i:s')));
            }
        }else {
            $status = "Failed";
            $newID  = "";
            $IDQz   = "";
        }

        $result = array("status" => $status, "idQuiz" => $IDQz);
        return $result;
    }

    public function updateQuiz($id, $nama, $durasi, $mulai, $selesai, $instruksi, $publish, $jenis, $random_soal, $random_opsi, $kelas, $sekolah){
        $newID  = "";
        $edit = array("nama"=>$nama, "durasi"=>"$durasi", "start_date"=>"$mulai", "end_date"=>"$selesai", "instruksi"=> "$instruksi", "date_modified"=>date('Y-m-d H:i:s'), "status" => "$publish", "jenis" => "$jenis", "random_soal" => "$random_soal", "random_opsi" => "$random_opsi");

        // print_r($edit);
        $update = $this->db->quiz->update(array("_id"=> new MongoId($id)),array('$set'=>$edit));
        $this->db->quiz_publish->remove(array("id_quiz" => $id));

        if($publish == "1"){
            $this_quiz      = $this->db->quiz->findOne(array("_id" => new MongoId($id)));
            $info_modul     = $this->db->modul->findOne(array("_id" => new MongoId($this_quiz['id_modul'])));
            $info_mapel     = $this->db->mata_pelajaran->findOne(array("_id" => new MongoId($info_modul['id_mapel'])));
            $info_kelas     = $this->db->kelas->findOne(array("_id" => new MongoId($info_mapel['id_kelas'])));
            
            $soal_master    = iterator_to_array($this->db->soal->find(array("id_paket" => $this_quiz['id_paket'])));

            $keys_soal = array_keys($soal_master);
            if($random_soal == "1"){
                shuffle($keys_soal);
            }

            $list_soal      = array();
            foreach ($keys_soal as $key_soal) {
                $this_soal  = $soal_master[$key_soal];
                $list_opsi  = array();

                $opsi_master = iterator_to_array($this->db->opsi_soal->find(array("id_soal" => "$this_soal[_id]")));
                
                $keys_opsi = array_keys($opsi_master);
                if($random_opsi == "1"){
                    shuffle($keys_opsi);
                }
                
                foreach ($keys_opsi as $key_opsi) {
                    $list_opsi[$key_opsi] = $opsi_master[$key_opsi];
                }

                $this_soal += ['opsi' => $list_opsi];
                $list_soal[$key_soal] = $this_soal;
            }

            $keterangan = $info_kelas['nama'].' # '.$info_mapel['nama'].' # '.$info_modul['nama'].' # '.$this_quiz['nama'];

            $this->db->quiz_publish->insert(array("id_quiz"=>$id, "id_sekolah"=>$sekolah, "jumlah_soal"=>sizeof($soal_master), "durasi"=>$this_quiz['durasi'], "start_date"=>$this_quiz['start_date'], "end_date"=>$this_quiz['end_date'], "keterangan"=>$keterangan, "daftar_soal"=>$list_soal, "date_created"=>date('Y-m-d H:i:s')));

        }

        if ($update) {
            $status ="Sukses";

            //------ Menulis LOG ---------
            $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>$_SESSION['lms_id'], "id_sekolah"=>$sekolah, "id_kelas"=>"$kelas", "aksi"=>"33", "id_data"=>"$id", "data"=>$edit, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $result = array("status" => $status);
        return $result;
    }

    public function hitungNilaiQuiz($idUser, $idQuiz, $jumlah_soal){
        $nilai_quiz         = 0;
        $list_jawaban_user  =  $this->db->jawaban_user->find(array("id_user"=>"$idUser", "id_quiz"=>"$idQuiz"));

        foreach ($list_jawaban_user as $jawaban_user) {
            if($jawaban_user['status'] == 'benar'){
                $nilai_quiz++;
            }
        }

        $nilai_akhir = round(($nilai_quiz/$jumlah_soal)*100, 2);

        return $nilai_akhir;
    }

    public function submitQuiz($idUser, $sekolah, $idQuiz, $nilaiQuiz, $jumlah_soal){
        $update     = array('$set' => array("nilai" => $nilaiQuiz, "jumlah_soal" => $jumlah_soal, "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s')));

        try {

            $this->db->kumpul_quiz->update(array("id_user" => $idUser, "id_quiz" => $idQuiz), $update, array("upsert" => true));
            $status     = "Success";

	    //------ Menulis LOG ---------
            $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>$idUser, "id_sekolah"=>$sekolah, "id_kelas"=>$idQuiz, "aksi"=>"45", "id_data"=>$nilaiQuiz, "data"=>$jumlah_soal, "date_created"=>date('Y-m-d H:i:s')));

        } catch(MongoCursorException $e) {

            $status     = "Failed";
        }

        return $status;
    }

    public function isSumbmitted($idUser, $idQuiz){
        $query  = $this->db->kumpul_quiz->find(array("id_user" => $idUser, "id_quiz" => $idQuiz))->count();

        if($query > 0){
            return "1";
        }else{
            return "0";
        }
    }

    public function duplicateQuiz($id_paket){
        $query =  $this->db->paket_soal->find(array("_id"=> new MongoId($id_paket)));
        // for
    }

    public function deleteJawabanUser($id_user, $id_quiz){
        $query =  $this->db->jawaban_user->remove(array("id_user" => $id_user, "id_quiz" => $id_quiz));
        // for
    }

    public function getStatusQuiz($quiz, $user){
        $query =  $this->db->kumpul_quiz->findOne(array("id_quiz"=>"$quiz", "id_user"=>"$user"));
        return $query;
    }

    public function getQuizPublish($idQuiz){
		$ID     = new MongoId($idQuiz);
        $query  = $this->db->quiz_publish->findOne(array("id_quiz" => $idQuiz));

        return $query;
    }

    public function setInfoQuizPeserta($idQuiz, $user, $sekolah, $jenis, $status){
        if($status == "end"){
            $update = array('jenis'=>$jenis, 'selesai'=>date('Y-m-d H:i:s'));
        }else{
            $update = array('jenis'=>$jenis, 'mulai'=>date('Y-m-d H:i:s'), 'selesai'=>$selesai);
        }
        
		$this->db->quiz_monitor->update(array("id_quiz"=>$idQuiz, 'id_user'=>$user, 'id_sekolah'=>$sekolah), array('$set'=>$update), array("upsert" => true));

        return $query;
    }

    public function getRandom($list){
        $keys       = array_keys($list);

        shuffle($keys);

        foreach ($keys as $key) {
            $random_list[$key] = $list[$key];
        }

        return $random_list;
    }
}
