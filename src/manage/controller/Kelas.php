<?php

class Kelas
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'kelas';
            $this->db = $db;
            $this->dbLog   = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

	public function GetData($user)
    {
        $criteria   = array('_id' => new MongoId(''.$user.''));
  		$getKelas = $this -> table -> findOne($criteria);

  		return $getKelas;
	}

    public function userSekolah($list)
    {
        $new    = explode(',', $list);
        foreach ($new as $data) {
            $findSekolah    = $this->db->sekolah_induk->findOne(array("_id" => new MongoId($data)));


            $sekolah['id']      = $data;
            $sekolah['nama']    = $findSekolah['nama_sekolah_induk'];
            $sekolah['npsn']    = $findSekolah['npsn'];

            $tampung[]          = $sekolah;
        }

        return $tampung;
    }

    public function getlistBySchool($id, $peran)
    {
        $criteria   = array('sekolah' => $id);
        $query      = $this->table->find($criteria);
        $jmlKelas   = $query->count();

        $uwala = array();

        if($jmlKelas > 0){
            $z = array();
            foreach ($query as $index => $value) {
                $data   = $value;
                if ($peran != 0) {
                    $filter = array( "id_kelas" => "$value[_id]", "status"=>"$peran");
                }else{
                    $filter = array( "id_kelas" => "$value[_id]" );

                }

                $query1 = $this->db->anggota_kelas->find(
                                                        $filter
                                                    );
                $data['member'] = $query1->count();
                $list_member = array();
                if ($data['member'] > 0) {
                    foreach ($query1 as $index=>$member) {
                        // $data['list_member'][]   = $member['id_user'];
                        $list_member[]   = $member['id_user'];
                        // $list_member[$index]['id_user']   = $member['id_user'];
                        // $list_member[$index]['role']      = $peran != 0 ? $member['status'] : $peran;
                    }
                }
                // $uwala['data'][]   = $data;
                $z = array_unique(array_merge($list_member,$z), SORT_REGULAR);
                // $z = array_merge($list_member,$z);

                // $z = array_unique(array_merge($list_member['id_user'],$z), SORT_REGULAR);
                // $z = array_map("unserialize", array_unique(array_map("serialize", $list_member), SORT_REGULAR));
            }
            // $uwala['listSeluruh'] = $z;

            $uwala['jumlah'] = count($z);
            $a = 0;
            foreach ($z as $us) {
                $query  = $this->db->user->findOne(array("_id" => new MongoId($us)));
                // $query  = $this->db->user->findOne(array("_id" => new MongoId($us['id_user'])));

                $tampung[$a]['id']      = "$query[_id]";
                $tampung[$a]['name']    = "<b>".$query['nama']."</b> (".$query['username'].")";
                $tampung[$a]['nama']    = $query['nama'];
                $tampung[$a]['user']    = $query['username'];
                $tampung[$a]['link']    = "<a href='new.php?id=$query[_id]' class='btn btn-primary btn-sm' title='Lihat data' data-toggle='tooltip' data-placement='left'><i class='fa fa-search'></i></a>";
                // $tampung[$a]['role']    = $us['role'];
                $a++;
            }

            if ($uwala['jumlah'] > 0) {
                usort($tampung, function($a, $b) {
                    return $a['nama'] - $b['nama'];
                });

                $uwala['list'] = $tampung;
            }else{
                $uwala['list'] = $list_member;
            }
        }else{
            $uwala['jumlah'] = 0;
        }


        return $uwala;
    }

    public function getInfoKelas($idkelas){
        $ID     = new MongoId($idkelas);
        $query  = $this->table->findOne(array("_id" => $ID));
        if($query['_id']){
            $query1 = $this->db->anggota_kelas->find(
                                                    array(
                                                        "id_kelas" => $idkelas
                                                    )
                                                );
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

    public function getlistAnggota($id)
    {
        $criteria   = array('id_kelas' => $id);
        $query      = $this->db->anggota_kelas->find($criteria);

        return $query;
    }

    // public function GetAllUser($user)
    public function GetAll($skip, $limit, $criteria, $order)
    {
        $columns = array(
            0   => 'sekolah' ,
            1   => 'nama',
            2   => 'status',
            3   => 'date_created'
        );

        foreach ($order as $value) {
            $by         = $value['dir'] == 'asc' ? 1 : -1;
            $sorting[]    = array($columns[$value['column']] => $by);
        }

        $getKelas = empty($criteria) ?
                                $this->table->find()->skip($skip)->limit($limit)->sort($sorting) :
                                $this->table->find($criteria)->skip($skip)->limit($limit)->sort($sorting);
        $filter     = $getKelas->count();
        $total      = $this->table->find()->count();

         if (empty($filter)) {
            $seluruh  = array();
        }else{
            foreach ($getKelas as $val) {
                if (!empty($val['sekolah'])) {
                    $idsklh = new MongoId($val['sekolah']);
                    $sklh   = $this->db->sekolah_induk->findOne(array('_id'=>$idsklh));
                    $dsklh  = $sklh['nama_sekolah_induk'];
                }else{
                    $dsklh  = 'Belum dihubungkan dengan Sekolah';
                }
                $data   = array();
                $data[] =  $dsklh;
                $data[] =  $val['nama'].'<br> <b>('.$val['kode'].')</b>';
                $data[] =  empty($val['status']) ? 'Terbuka' : 'Terkunci';
                $data[] =  $val['date_created'];
                // $data[] =  date_format(date_create($val['date_created']), "d-M-Y H:i:s");
                $data[] = "$val[_id]";

                $seluruh[] = $data;
                // $law[]  = $val['sekolah'];
            }
        }

        $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>'', 'total'=>'', 'data'=>$getKelas];

        return $feedback;
        // print_r($feedback);
    }

    public function GetLog($iduser,$idkelas)
    {

        $getdataUsr = $this->db->user->findOne(array('_id' => new MongoId($iduser)));
        $getdataLog = $this->dbLog->log_access->find(array('id_user'=>$iduser));
        // $listKelas  = $this->getAnggotadikelas($iduser);

        $collection = $this->dbLog->log_access;

        $getdataLog = $collection->find(array('id_user'=>$iduser, 'id_kelas'=>$idkelas))->sort(array('date_created'=> -1));

        if (!empty($sorting)) {
            $getdataLog->sort($sorting);
        }

        $total      = $collection->count();
        $filter     = $getdataLog->count();

        // $a    = 0;
        $selisih    = 0;
        $waktu1     = '';
        $kelas1     = 0;

        // foreach ($listKelas as $index=>$value) {
        //     $tampung[$index] = $a;
        //     $a++;
        // }


        foreach ($getdataLog as $value) {
            if (($value['id_kelas'] != '0') AND ($value['id_kelas'] != '')) {

                $duser  = $getdataUsr['nama'];

                if (!empty($value['id_kelas'])) {
                    $idkelas    = new MongoId($value['id_kelas']);
                    $query1     = $this->db->kelas->findOne(array('_id'=>$idkelas));
                    $dkelas     = $query1['nama'];
                }else{
                    $dkelas  = '';
                }

                $hak    = $this->getKeanggotaan($value['id_kelas'], "$getdataUsr[_id]");

                $hitung = $this->coba($waktu1, $value['date_created'], $kelas1, $value['id_kelas']);
                if ($hitung['selisih'] == 0) {
                    $selisih = 0;
                }
                $waktu1 = $hitung['waktu'];
                $kelas1 = $hitung['kelas'];
                $selisih += $hitung['selisih'];
                // $selisih += $waktu - strtotime($value['date_created']);

                $data   = array();
                $data['name']   =  '<b>'.$duser.'</b> ('.$hak['posisi'].') ';
                $data['kelas']  =  $dkelas;
                // $data[] =  $dsklh;
                // $data[] =  $value['nama'].'<br> <b>('.$value['kode'].')</b>';
                $data['link'] =  "<a href='".$value['link']."' style='text-decoration: none'>Membuka halaman ".$value['halaman']."</a>";
                $data['link'] = str_replace("SIAJAR LMS","seTARA Daring",$data['link']);
                $data['selisih'] = $selisih;
                $data['tanggal'] =  date_format(date_create($value['date_created']), "d-M-Y H:i:s");

                $seluruh[] = $data;
            }
        }

        $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>'', 'total'=>'', 'data'=>$columns];

        return $feedback;
    }

    public function GetLog1($iduser, $waktu)
    {
        $jangka = explode(" - ", $waktu);
        $awal   = date('Y-m-d 00:00:00', strtotime($jangka[0]));
        $akhir  = date('Y-m-d 23:59:59', strtotime($jangka[1]));
        // Informasi tentang User
        $dataUser   = $this->db->user->findOne(array('_id' => new MongoId($iduser)));
        $listKelas  = $this->getAnggotadikelas($iduser);

        $collection = $this->dbLog->log_access;
        $seluruh = array();
        foreach ($listKelas as $index=>$val) {

            // Informasi tentang Kelas
            $idkelas    = new MongoId($val['id_kelas']);
            $datakelas  = $this->db->kelas->findOne(array('_id'=>$idkelas));
            if ($val['status'] == 1) {
                $role = 'Administrator Kelas';
            }elseif ($val['status'] == 2) {
                $role = 'Guru Mata Pelajaran';
            }elseif ($val['status'] == 3) {
                $role = 'Tutor';
            }else{
                $role = 'Siswa';
            }
            // $role       = $val['status'] == 1 ? "Administrator Kelas"  : $dataUser['status'] == 2 ? "Guru Mata Pelajaran" : $dataUser['status'] == 3 ? "Tutor" : "Anggota" ;

            // Mengambil data dari Tabel Log Access
            $getdataLog = $collection->find(
                                        array(
                                            'id_user'   =>$iduser,
                                            'id_kelas'  =>$val['id_kelas'],
                                            'date_created'=>array(
                                                    '$gte'  => $awal,
                                                    '$lt'   => $akhir
                                                )
                                        )
                                    )->sort(array('_id'=> -1));
            if ($getdataLog->count() > 0) {
                $selisih    = 0;
                $waktu1     = '';
                $kelas1     = 0;
                $data   = array();

                foreach ($getdataLog as $value) {

                    $hitung = $this->coba($waktu1, $value['date_created'], $kelas1, $value['id_kelas']);
                    $waktu1 = $hitung['waktu'];
                    $kelas1 = $hitung['kelas'];
                    $selisih += $hitung['selisih'];

                    $data['nama']   = '<b>'.$dataUser['nama'].'</b>';
                    $data['kelas']  = $datakelas['nama']." <b>($role)</b>";
                    $data['durasi'] = sprintf('%02d Jam %02d menit %02d detik', ($selisih/3600),($selisih/60%60), $selisih%60);
                    $data['link'] =  "<a href='new_.php?id=$iduser&kelas=$val[id_kelas]' class='btn btn-sm'><i class='fa fa-search'></i></a>";
                }

                $seluruh[] = $data;

            }
        }


        $feedback   = ['data'=>$seluruh];
        // $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>'', 'total'=>'', 'data'=>$columns];

        return $feedback;
    }

    public function coba($old, $new, $kelasOld, $kelasNew)
    {
        $waktu1     = strtotime($old);
        $waktu2     = strtotime($new);

        if (empty($old)) {
            $selisih = 0;
        }else{
            $tglAwal    = date('Y-m-d', $waktu1);
            $tglAkhir   = date('Y-m-d', $waktu2);
            if ($tglAwal != $tglAkhir) {
                $selisih = 0;
            }else{
                if ($kelasOld == 0) {
                    $selisih = 0;
                }else{
                    if ($kelasOld == $kelasNew) {
                        $selisih = $waktu1 - $waktu2;
                    }else{
                        $selisih = 0;
                    }
                }
            }

        }

        return array('waktu'=>$new, 'selisih'=>$selisih, 'kelas'=>$kelasNew);
    }

    public function RemoveData($iddata)
    {
        $filter     = array('_id' => new MongoId(''.$iddata.''));
        $remove     = $this->table->remove($filter);

        return $remove;
    }

    public function getKeanggotaan($idkelas, $iduser){
        $data   = $this->db->anggota_kelas->findOne(array('id_kelas'=>$idkelas, 'id_user'=>"$iduser"));
                switch ($data['status']) {
                    case '1':
                        $data['posisi'] = "Administrator Kelas";
                        break;
                    case '2':
                        $data['posisi'] = "Guru Mata Pelajaran";
                        break;
                    case '3':
                        $data['posisi'] = "Tutor";
                        break;
                    default:
                        $data['posisi'] = "Siswa";
                        break;
                }
        return $data;
    }

    public function getAnggotadikelas($iduser){
        $data   = $this->db->anggota_kelas->find(array('id_user'=>"$iduser"));

        return $data;
    }

    public function readMore($id_postingan)
    {
        $criteria   = array('_id' => new MongoId($id_postingan));
        $query      = $this->db->posting->findOne($criteria);

        $data['id_postingan']   = "$query[_id]";
        $data['isi_postingan']  = $query['isi_postingan'];

        return $data;
    }
}


?>
