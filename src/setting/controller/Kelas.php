<?php

class Kelas
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName  = 'kelas';
            $this->db   = $db;
            $this->dbLog   = $dbLog;
            $this->table= $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

    public function CountKelas()
    {
        $query =  $this -> table -> find();
        $count = $query->count();
        return $count;
    }

    public function CountKelasBySekolah($id_sekolah)
    {
        $query =  $this -> table -> find(array('sekolah' => $id_sekolah));
        $count = $query->count();
        return $count;
    }

    public function acakKodeKelas($jumlahKarakter)
    {
        $karakter   = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $hasil      = '';
        for ($i=0; $i < $jumlahKarakter; $i++) {
            $acak   = rand(0, strlen($karakter)-1);
            $hasil  .= $karakter{$acak};
            # code...
        }
        $cek    = $this->table->find(array("kode" => $hasil))->count();
        if ($cek > 0) {
            $this->acakKodeKelas(6);
        }
        # code...
        return $hasil;
    }

    public function getListKelas($user){
        $query  = $this->db->anggota_kelas->find(array("id_user" => "$user"));

        if($query->count() > 0){
            $s=0;
            foreach ($query as $member) {
                $idkelas = new MongoId($member['id_kelas']);
                $query1	= $this->table->findOne(array("_id" => $idkelas));
                $kelas[$s] = $query1;
                $kelas[$s]['hak'] = $member['status'];
                $kelas[$s]['test'] = $_SESSION['lms_id'];
                $s++;
            }

            $data   = $kelas;
        }
        return $data;
    }

    public function getKelasMapel($user, $kelas){
        $query  = $this->db->anggota_kelas->find(array("id_user" => "$user", "id_kelas" => array('$ne' => "$kelas"), '$or' => array(array("status"=>"1"), array("status"=>"2")) ), array('id_kelas' => 1, '_id' => 0));

        $ids     = array();
        foreach($query as $seprateIds){
            $ids[] = new MongoId($seprateIds['id_kelas']);
        }

        $query1 = $this->table->find(array("_id" => array( '$in' => $ids)), array('nama' => 1, '_id' => 1, 'kode'=>1))->sort(array('nama'=>1));

        return $query1;
    }

    public function getInfoKelas($idkelas){
		$ID     = new MongoId($idkelas);
        $query  = $this->db->kelas->findOne(array("_id" => $ID));
		if($query['_id']){
			$query1	= $this->db->anggota_kelas->find(array("id_kelas" => $idkelas, "id_user" => array('$ne' => '')));
            $query['member'] = $query1->count();
            $jumlah_siswa = 0;
            $jumlah_user  = 0;
			if ($query['member'] > 0) {
			    foreach ($query1 as $member) {
			        $query['list_member'][]  = $member['id_user'];
                    if($member['status'] == 4 OR $member['status'] == '4'){
                        $jumlah_siswa++;
                    }
                    $jumlah_user++;
			    }
			}
            $query['jumlah_siswa'] = $jumlah_siswa;
            $query['jumlah_user'] = $jumlah_user;
            $data   = $query;
		}
        return $data;
    }

    public function getInfoKelasTkb($idkelas, $tkb){
		$ID     = new MongoId($idkelas);
        $query  = $this->db->kelas->findOne(array("_id" => $ID));
		if($query['_id']){
            if($tkb!="0"){
                $query1	= $this->db->anggota_kelas->find(array("id_kelas" => $idkelas, "tkb" => $tkb));
            }else{
                $query1	= $this->db->anggota_kelas->find(array("id_kelas" => $idkelas));
            }

            $query['member'] = $query1->count();
            $jumlah_siswa = 0;
			if ($query['member'] > 0) {
			    foreach ($query1 as $member) {
			        $query['list_member'][]  = $member['id_user'];
                    if($member['status'] == 4 OR $member['status'] == '4'){
                        $jumlah_siswa++;
                    }
			    }
			}
            $query['jumlah_siswa'] = $jumlah_siswa;
            $data   = $query;
		}
        return $data;
    }

    public function getAnggotaPengampu($idkelas){

        $query1 = $this->db->anggota_kelas->find(array("id_kelas" => $idkelas, '$or' => array(array("status"=>"1"), array("status"=>"2"))));

        foreach ($query1 as $member) {
            $id_user    = new MongoId($member['id_user']);
            $query['list_member'][]  = array('_id' => $id_user);
        }

        $query  = $this->db->user->find(array('$or' => $query['list_member']));

        $data   = $query;

        return $data;
    }

    public function getKeanggotaan($idkelas, $iduser){
        $data	= $this->db->anggota_kelas->findOne(array('id_kelas'=>$idkelas, 'id_user'=>"$iduser"));
                switch ($data['status']) {
                    case 1:
                        $data['posisi'] = "Administrator Kelas";
                        break;
                    case 2:
                        $data['posisi'] = "Guru Mata Pelajaran";
                        break;
                    case 3:
                        $data['posisi'] = "Tutor";
                        break;
                    default:
                        $data['posisi'] = "Anggota";
                        break;
                }
        return $data;
    }

    public function addKelas($nama, $user, $sekolah){
        $newID  = "";
        $kode   = $this->acakKodeKelas(6);
        $insert = array("nama" => $nama, 'kode'=> $kode, "tentang"=>"", "sekolah"=>"", "tkb"=>"", "status"=>"", "creator" => "$user", "update"=>0, "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                  $this->table->insert($insert);
        $newID  = $insert['_id'];
        if ($newID) {
            $status     = "Success";
            $message    = "Kelas $nama Berhasil ditambahkan!";
            $relation   = $this->db->anggota_kelas->insert(array( "id_user"=>"$user", "id_kelas"=>"$newID", "status"=>"1", "date_modified"=>date('Y-m-d H:i:s') ));

            //------ Menulis LOG ---------
            $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$newID", "aksi"=>"5", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status     = "Failed";
            $message    = "Kelas $nama Gagal ditambahkan!";
        }

        $result = array("status" => $status, "message"=>$message, "IDKelas" => $newID);
        return $result;
    }

    public function updateKelas($user, $nama, $tentang, $sekolah, $tkb, $kelas){
        $update     = array("nama"=>$nama, "tentang"=>$tentang, "sekolah"=>$sekolah, "tkb"=>$tkb, "date_modified"=>date('Y-m-d H:i:s'));

          try {
              $this->table->update(array("_id" => new MongoId($kelas)), array('$set' => $update));

                $status   = "success";
                $judul    = "Berhasil!";
                $message  = "Pengaturan Kelas berhasil disimpan.";

                //------ Menulis LOG ---------
                $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"10", "id_data"=>"$kelas", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
          } catch(MongoCursorException $e) {
                $status   = "error";
                $judul    = "Maaf!";
                $message  = "Pengaturan Kelas gagal disimpan.";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDKelas" => $kelas);
        return $result;
    }

    public function updateSekolahKelas($user, $sekolah, $kelas){
        $update     = array("sekolah"=>$sekolah, "update"=>"2021.1", "date_modified"=>date('Y-m-d H:i:s'));

          try {
              $this->table->update(array("_id" => new MongoId($kelas)), array('$set' => $update));

                $status   = "success";
                $judul    = "Berhasil!";
                $message  = "Pengaturan Kelas berhasil disimpan.";

                //------ Menulis LOG ---------
                $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"10", "id_data"=>"$kelas", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));
          } catch(MongoCursorException $e) {
                $status   = "error";
                $judul    = "Maaf!";
                $message  = "Pengaturan Kelas gagal disimpan.";
          }

        $result = array("status" => $status, "judul" => $judul, "message"=>$message, "IDKelas" => $kelas);
        return $result;
    }

    public function joinKelas($kode, $user, $sekolah, $privilege){
        $newID  = "";
        $query  = $this->table->findOne(array("kode" => $kode));
        if (isset($query['_id'])) {
            if ($query['status'] == 'LOCKED') {
                $status     = "Locked";
                $message    = "Maaf, tidak dapat bergabung saat ini!";
            }else {
                $query2     = $this->db->anggota_kelas->findOne(array("id_user" => "$user", "id_kelas"=>"$query[_id]"));
                if (!empty($query2["_id"])) { 
                    $status     = "Failed";
                    $message    = "Kamu sudah bergabung kedalam Kelas ini!";
                }else{
                    $hak        = $privilege == "guru" ? 3 : 4;
                    $relation   = $this->db->anggota_kelas->insert( array( "id_user"=>"$user", "id_kelas"=>"$query[_id]", "status"=>$hak, "tkb"=>"", "date_modified"=>date('Y-m-d H:i:s') ) );

                    $status     = "Success";
                    $message    = "Kamu berhasil bergabung kedalam Kelas!";
                    $newID      = "$query[_id]";

                    //------ Menulis LOG ---------
                    $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>$newID, "aksi"=>"7", "id_data"=>$newID, "date_created"=>date('Y-m-d H:i:s')));
                }
            }
        }else {
            $status     = "Failed";
            $message    = "Maaf, tidak ada kelas dengan kode tersebut!";
        }

        $result = array("status" => $status, "message"=>$message, "IDKelas" => $newID);
        return $result;
    }

    public function addPost($post, $kelas, $user, $sekolah){
        $newID  = "";
        $insert = array("isi_postingan" => $post, 'id_kelas'=> "$kelas", "creator"=>"$user", "date_created"=>date('Y-m-d H:i:s'), "date_modified"=>date('Y-m-d H:i:s'));
                  $this->db->posting->insert($insert);
        $newID  = $insert['_id'];
        if ($newID) {
            $status     = "Success";

            //------ Menulis LOG ---------
            $log      = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>"$sekolah", "id_kelas"=>"$kelas", "aksi"=>"14", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status     = "Failed";
        }

        $result = array("status" => $status, "IDPosting" => $newID);
        return $result;
    }

    public function postingKelas($kelas, $halaman){
        $limit  = 5;
        $skip   = ($halaman - 1)*$limit;
        $next   = ($halaman+1);
        $prev   = ($halaman-1);
        $btn    = '';

        $query  = $this->db->posting->find(array("id_kelas" => $kelas))->skip($skip)->limit($limit)->sort(array('date_created' => -1));
		$count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $data[$index] = $isi;
                $userID	= new MongoId($isi['creator']);
                $query2 = $this->db->user->findOne(array('_id' => $userID));
                $query3 = $this->db->anggota_kelas->findOne(array("id_kelas" => $kelas, "id_user" => $isi['creator']));
                
                $data[$index]['user']       = $query2['nama'];
                $data[$index]['user_foto']  = $query2['foto'];
                
                if ($query3['status'] == 1) {
                    $data[$index]['user_akses'] = 'Administrator Kelas';
                }elseif ($query3['status'] == 2) {
                    $data[$index]['user_akses'] = 'Tutor Pengampu';
                }elseif ($query3['status'] == 3) {
                    $data[$index]['user_akses'] = 'Tutor Pendamping';
                }
                // $data[$index]['user_akses'] = $query3['status'];
            }
            if($halaman > 1){
                $btn = '<a class="btn btn-warning col-md-6" href="?id='.$kelas.'&halaman=' . $prev . '"><b><i class="fa fa-mail-reply"></i> Sebelumnya</b> </a>';
                if($halaman * $limit < $count) {
                    $btn .= ' <a class="btn btn-sucess col-md-6" href="?id='.$kelas.'&halaman=' . $next . '"><b>Selanjutnya <i class="fa fa-mail-forward"></i></b></a>';
                }
            } else {
                if($halaman * $limit < $count) {
                    $btn = ' <a class="btn btn-sucess col-md-12" href="?id='.$kelas.'&halaman=' . $next . '"><b>Selanjutnya <i class="fa fa-mail-forward"></i></b></a>';
                }
            }
        }

        $result = array("count" => $count, "data"=>$data, "tombol"=>$btn);
        return $result;
    }

    public function postingSeluruh($user, $halaman){
        $query  = $this->db->anggota_kelas->find(array("id_user"=>"$user"));
        $count  = $query->count();
        $count2 = 0;
        $data   = array();

        if($count > 0){
            foreach ($query as $isi) {
                $simpan[]['id_kelas'] = $isi['id_kelas'];
            }
            $limit  = 10;
            $skip   = ($halaman - 1)*$limit;
            $next   = ($halaman+1);
            $prev   = ($halaman-1);
            $btn    = '';
            $query2 = $this->db->posting->find(array(
                                                '$or' => $simpan
                                            ))->skip($skip)->limit($limit)->sort(array('date_created' => -1));
    		$count2  = $query2->count();
            if ($count2 > 0) {
                foreach ($query2 as $index => $isi) {
                    $data[$index] = $isi;
                    $userID	    = new MongoId($isi['creator']);
                    $idkelas    = new MongoId($isi['id_kelas']);
                    $query3     = $this->db->user->findOne(array('_id' => $userID));
                    $query4     = $this->table->findOne(array('_id' => $idkelas));
                    $query5     = $this->db->anggota_kelas->findOne(array("id_kelas" => $isi['id_kelas'], "id_user" => $isi['creator']));
                
                    $data[$index]['user']   = $query3['nama'];
                    $data[$index]['foto']   = $query3['foto'];
                    $data[$index]['kelas']  = $query4['nama'];

                    if ($query5['status'] == 1) {
                        $data[$index]['user_akses'] = 'Administrator Kelas';
                    }elseif ($query5['status'] == 2) {
                        $data[$index]['user_akses'] = 'Tutor Pengampu';
                    }elseif ($query5['status'] == 3) {
                        $data[$index]['user_akses'] = 'Tutor Pendamping';
                    }
                }
                if($halaman > 1){
                    $btn = '<a class="btn btn-warning col-md-6" href="?halaman=' . $prev . '"><b><i class="fa fa-arrow-left"></i> Sebelumnya</b> </a>';
                    if($halaman * $limit < $count2) {
                        $btn .= ' <a class="btn btn-sucess col-md-6" href="?halaman=' . $next . '"><b>Selanjutnya <i class="fa fa-arrow-right"></i></b></a>';
                    }
                } else {
                    if($halaman * $limit < $count2) {
                        $btn = ' <a class="btn btn-sucess col-md-12" href="?halaman=' . $next . '"><b>Selanjutnya <i class="fa fa-arrow-right"></i></b></a>';
                    }else{
                        $btn    = '';
                    }
                }
            }
        }else{
            $btn    = '';
        }

        $result = array("count" => $count2, "data"=>$data, "tombol"=>$btn);
        return $result;
    }
}

?>
