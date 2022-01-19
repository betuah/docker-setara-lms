<?php

class Dashboard
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
    public function CountDashboard()
    {
        $queryA = $this->db->user->find(array('status'=>'guru', 'program'=>'Paket A'));
        $guruA  = $queryA->count();

        $queryB = $this->db->user->find(array('status'=>'guru', 'program'=>'Paket B'));
        $guruB  = $queryB->count();

        $queryC = $this->db->user->find(array('status'=>'guru', 'program'=>'Paket C'));
        $guruC  = $queryC->count();

        $guru   = $guruA+$guruB+$guruC;

        $queryA = $this->db->user->find(array('status'=>'siswa', 'program'=>'Paket A'));
        $siswaA = $queryA->count();

        $queryB = $this->db->user->find(array('status'=>'siswa', 'program'=>'Paket B'));
        $siswaB = $queryB->count();

        $queryC = $this->db->user->find(array('status'=>'siswa', 'program'=>'Paket C'));
        $siswaC = $queryC->count();

        $siswa  = $siswaA+$siswaB+$siswaC;

        $query = $this->db->kelas->find();
        $kelas = $query->count();

        $query = $this->db->sekolah_induk->find(array('program'=>'Pendidikan Kesetaraan'));
        $satuan = $query->count();

        $data  = ['jmlGuru'=>$guru,'jmlSiswa'=>$siswa,'jmlKelas'=>$kelas,'jmlSatuan'=>$satuan];

        return $data;
    }
    public function lastRegister()
    {
        $i=0;

        @$query = $this->db->user->find()->sort([_id => -1])->limit(10);
        foreach ($query as $userInfo) {
            $data[$i]['nama'] = $userInfo['nama'];
            $data[$i]['username'] = $userInfo['username'];
            $data[$i]['sekolah'] = $userInfo['sekolah'];
            $data[$i]['status'] = $userInfo['status'];
            $data[$i]['date'] = $userInfo['date_created'];

            $i++;
        }

        return $data;
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

	public function getInfoKelas($idkelas){
		$ID     = new MongoId($idkelas);
        $query  = $this->db->kelas->findOne(array("_id" => $ID));
		if($query['_id']){
			$query1	= $this->db->anggota_kelas->find(array("id_kelas" => $idkelas));
            $query['member'] = $query1->count();
			if ($query['member'] > 0) {
			    foreach ($query1 as $member) {
			        $query['list_member'][]  = $member['id_user'];
			    }
			}
            $data   = $query;
		}
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

    public function postingan(){

        $query  = $this->db->posting->find()->limit(7)->sort(array('date_created' => -1));
		$count  = $query->count();
        $data   = array();

        if ($count > 0) {
            foreach ($query as $index => $isi) {
                $kelasID = new MongoId($isi['id_kelas']);
                $userID = new MongoId($isi['creator']);
                $query2 = $this->db->kelas->findOne(array('_id' => $kelasID));
                $query3 = $this->db->user->findOne(array('_id' => $userID));

                $data[$index]['id_postingan']   = $isi['_id'];
                $data[$index]['id_kelas']       = $isi['id_kelas'];
                $data[$index]['isi_postingan']  = $isi['isi_postingan'];
                $data[$index]['date_created']   = $isi['date_created'];
                $data[$index]['kelas']      = $query2['nama'];
                $data[$index]['user']       = $query3['nama'];
                $data[$index]['user_foto']  = $query3['foto'];
            }
        }

        $result = array("count" => $count, "data"=>$data);
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
                    $data[$index]['user']   = $query3['nama'];
                    $data[$index]['foto']   = $query3['foto'];
                    $data[$index]['kelas']  = $query4['nama'];
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
