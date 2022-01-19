<?php
date_default_timezone_set('Asia/Jakarta');
require("../../../../setting/connection.php");
require("../../../../setting/connection-log.php");
spl_autoload_register(function ($class) {
  include '../../../../setting/controller/' .$class . '.php';
});

session_start();

$method	= $_REQUEST;
$table  = $db->tugas;

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $date   = new DateTime($data['begin']);
        $begin  = $date->format('d M Y');
        $date   = new DateTime($data['deadline']);
        $deadline = $date->format('d M Y');      
        $resp   = array('data'=>$data, 'begin'=>$begin, 'deadline'=>$deadline);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'showAll'){
        $catch  = $table->find(array());
        foreach ($catch as $row) {
            $data[]   = $row;
        }
        $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'showDetil'){
        $tugas          = $table->findOne(array("_id" => new MongoId($method['ID'])));
        $kumpul_tugas   = $db->tugas_kumpul->findOne(array("id_user" => $method['user'], "id_tugas" => $method['ID']));
        $profil         = $db->user->findOne(array('_id' => new MongoId($method['user'])));

        $data                   = array();
        $data['nama_siswa']     = $profil['nama'];
        $data['foto_siswa']     = $profil['foto'];

        $profil         = $db->user->findOne(array('_id' => new MongoId($tugas['creator'])));

        $data['nama_guru']      = $profil['nama'];
        $data['foto_guru']      = $profil['foto'];
        $data['id_tugas']       = $tugas['_id'];
        $data['id_modul']       = $tugas['id_modul'];
        $data['judul']          = $tugas['nama'];
        $data['deskripsi']      = $tugas['deskripsi'];
        $data['file_lampiran']  = @$tugas['file'];
        $data['deadline']       = $tugas['deadline'];
        $data['creator']        = $tugas['creator'];
        $data['tugas_created']  = $tugas['date_created'];
        $data['tugas_modified'] = $tugas['date_modified'];

	strtotime((new DateTime())->format('d M Y')) > strtotime((new DateTime($tugas['deadline']))->format('d M Y')) ? $deadline = "1" : $deadline = "0";

        if($kumpul_tugas){
            if($method['hakKelas'] != "4"){
                $data['penilaian']          = '  <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" onclick="penilaian()" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke daftar tugas."><i class="font-icon font-icon-pencil"></i> Berikan Penilaian</a>
                                                </div>
                                            ';
            }else{
                if($deadline == "0"){
                    $data['penilaian']          = '<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Aksi
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" onclick="edit_tugas_siswa(\''.$tugas['_id'].'\')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke daftar tugas."><i class="font-icon font-icon-pencil"></i> Edit Tugas</a>
                                                    </div>
                                                ';
                }else{
                    $data['penilaian']          = '';
                }
            }
            $data['jawaban']                = @$kumpul_tugas['deskripsi'];
            $data['file']                   = @$kumpul_tugas['file'];
            $data['kumpul_tugas_created']   = date("d F Y H:i:s", strtotime($kumpul_tugas['date_created']));
            $data['kumpul_tugas_modified']  = date("d F Y H:i:s", strtotime($kumpul_tugas['date_modified']));
        }else{
            $data['penilaian']              = '';
            $data['jawaban']                = '<span class="label label-warning">Belum Dikerjakan</span>';
            $data['file']                   = '';
            $data['kumpul_tugas_created']   = "";
            $data['kumpul_tugas_modified']  = "";
        }

        $resp   = array('data'=>$data);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'showDetilTugasSiswa'){
        $kumpul_tugas   = $db->tugas_kumpul->findOne(array("id_user" => $method['user'], "id_tugas" => $method['ID']));
        $resp           = array('data'=>$kumpul_tugas);
        $Json           = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'showDaftarTugasSiswa'){
        $kelasClass		= new Kelas();
        $mapelClass 	= new Mapel();
        $modulClass 	= new Modul();
        $tugasClass     = new Tugas();
        $userClass		= new User();

        $infoModul		= $modulClass->getInfoModul($method['modul']);
        $infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
        $infoKelas		= $kelasClass->getInfoKelas($infoMapel['id_kelas']);

        foreach (array_values($infoKelas['list_member']) as $data) {
            $menu		= '';
            $infoUser	= $userClass->GetData($data);
            $infoHak	= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], "$infoUser[_id]");

            if ($infoUser['status'] == 'siswa'){
                $menuAnggota	= '<div class="btn-group" style="float: right;">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="lihat_tugas(\''.$method['ID'].'\', \'tugas-siswa\', \''.$infoUser['_id'].'\')" id="btn-lihat-tugas" class="dropdown-item"><i class="font-icon font-icon-eye"></i> Lihat Detil Tugas</a>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="reset_tugas(\''.$method['ID'].'\', \''.$infoUser['id_user'].'\')" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                                    </div>
                                </div>';

                $statusTugas        = $tugasClass->getStatusTugas($method['ID'], $infoUser['_id']) ? "<span class='label label-success'>Sudah mengumpulkan</span>" : "<span class='label label-warning'>Belum mengumpulkan</span>";

                $waktuTugas         = $tugasClass->getStatusTugas($method['ID'], $infoUser['_id']) ? "<i class='fa fa-clock-o' aria-hidden='true'></i> Mengumpulkan 2 hari yang lalu" : "<i class='fa fa-clock-o' aria-hidden='true'></i> -";

                $statusPenilaian    = '';

                $image		= empty($infoUser['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$infoUser['foto']."' style='max-width: 75px; max-height: 75px;' />" ;
                echo "	<tr>
                            <td width='80px;'>".$image."</td>
                            <td><span class='user-name'>$infoUser[nama]</span> <br> <div class='color-blue-grey-lighter'>".$waktuTugas."</div><div id='".$infoUser['id_user']."'>".$statusTugas."</div></span></td>
                            <td width='70px;' class='shared'>".$statusPenilaian."</td>
                            <td width='70px;' class='shared'>".$menuAnggota."</td>
                        </tr>";
            }
        }
    }

    if($method['action'] == 'showDaftarTugasSiswaAjax'){
        $tugasClass     = new Tugas();
        $profileClass   = new Profile();

        $fields    = array();

        $input     =& $_GET;
        $iColumns  = $input['iColumns'];

        $dataProps = array();

        for ($i = 0; $i < $iColumns; $i++) {
            $var = 'mDataProp_'.$i;
            if (!empty($input[$var]) && $input[$var] != 'null') {
                $dataProps[$i] = $input[$var];
            }
        }

        $searchTermsAny = array();
        $searchTermsAll = array();

        if ( !empty($input['sSearch']) ) {
            $sSearch = $input['sSearch'];
            for ( $i=0 ; $i < $iColumns ; $i++ ) {
                if ($input['bSearchable_'.$i] == 'true') {
                    if ($input['bRegex'] == 'true') {
                        $sRegex = str_replace('/', '\/', $sSearch);
                    }else{
                        $sRegex = preg_quote($sSearch, '/');
                    }

                    $searchTermsAny[] = array(
                                                $dataProps[$i] => new MongoRegex( '/'.$sRegex.'/i' )
                                            );
                }
            }
        }else{
            $sSearch = "";
        }

        for ( $i=0 ; $i < $iColumns ; $i++ ) {
            if ( $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
                if ($input['bRegex_'.$i] == 'true') {
                    $sRegex = str_replace('/', '\/', $input['sSearch_'.$i]);
                    //$sRegex = str_replace('\n', '\r', $input['sSearch_'.$i]);
                }else {
                    $sRegex = preg_quote($input['sSearch_'.$i], '/');
                }

                $searchTermsAll[ $dataProps[$i] ] = new MongoRegex( '/'.$sRegex.'/i' );
            }
        }

        $searchTerms = $searchTermsAll;
        if (!empty($searchTermsAny)) {
            $searchTerms['$or'] = $searchTermsAny;
        }

        if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
            $skip  = (int) $input['iDisplayStart'];
            $limit = (int) $input['iDisplayLength'];
        }

        $pipeline = array(
            array(
                '$match'  => array(
                    '$and' => array(
                        array("id_kelas" => $method['kelas']),
                        array("id_user" => array('$ne' => ''))
                    ),
                    '$or' => array(
                        array("status" => 4),
                        array("status" => "4")
                    )
                )
            ),
            array(
                '$addFields' => array(
                    'id' => array(
                        '$toString' => '$_id'
                    ),
                    '_id_user' => array(
                        '$toObjectId' => '$id_user'
                    )
                )
            ),
            array(
                '$lookup' => array(
                    'from' => 'user',
                    'let' => array('id_user' => '$_id_user'),
                    'pipeline' => array(
                        array(
                            '$match' => array(
                                '$expr' => array(
                                    '$eq' => array('$_id','$$id_user')
                                )
                            )
                        ),
                        array(
                            '$addFields' => array(
                                'status_user' => '$status'
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
                '$match' => array(
                    'nama' => new MongoRegex("/".$sSearch."/i")
                )
            ),
            array(
                '$facet' => array(
                    'paginatedResults' => [array('$skip' => $skip), array('$limit' => $limit)],
                    'totalCount' => [array('$count' => 'count')]
                )
            )
        );

        $options = array("explain" => false);

        $cursor = $db->anggota_kelas->aggregate($pipeline, $options)['cursor']['firstBatch'];

        $output = array(
            "sEcho" => intval($input['sEcho']),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => $cursor[0]['totalCount'][0]['count'],
            "aaData" => array()
        );

        $no = 1;
        foreach ( $cursor[0]['paginatedResults'] as $doc ) {

            $menuAnggota	= '<div class="btn-group" style="float: right;">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Aksi
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a onclick="lihat_tugas(\''.$method['ID'].'\', \'tugas-siswa\', \''.$doc['id_user'].'\')" id="btn-lihat-tugas" class="dropdown-item"><i class="font-icon font-icon-eye"></i> Lihat Detil Tugas</a>
                                    <a onclick="reset_tugas(\''.$method['ID'].'\', \''.$doc['id_user'].'\')" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                                </div>
                            </div>';

            $kumpulTugas        = $tugasClass->getStatusTugas($method['ID'], $doc['id_user']);

            if($kumpulTugas){
                $keteranganTugas = "<span class='label label-success'>Sudah mengumpulkan </span>";
                $nilaiSiswa      = "<i class='fa fa-table' aria-hidden='true'></i> Nilai: ".($kumpulTugas['nilai']?$kumpulTugas['nilai']:'<span class="label label-warning">Belum Dinilai</span>');
                if($kumpulTugas['date_created']){
                    $waktuKumpul     = "<i class='fa fa-clock-o' aria-hidden='true'></i> Mengumpulkan ".$profileClass->selisih_waktu($kumpulTugas['date_created']);
                }else{
                    $waktuKumpul     = "<i class='fa fa-clock-o' aria-hidden='true'></i> Mengumpulkan ".$profileClass->selisih_waktu($kumpulTugas['date_modified']);
                }
            }else{
                $keteranganTugas = "<span class='label label-warning'>Belum mengumpulkan</span>";
                $nilaiSiswa      = "<i class='fa fa-table' aria-hidden='true'></i> Nilai: -";
                $waktuKumpul     = "<i class='fa fa-clock-o' aria-hidden='true'></i> -";
            }

            $doc += ["no" => $no];
            $doc += ["nama" => $doc['nama']];
            $doc += ["nilai" => $kumpulTugas['nilai']?$kumpulTugas['nilai']:' -' ];
            $doc += ["waktu_kumpul" => $kumpulTugas['date_created']?$kumpulTugas['date_created']:" - "];
            $doc += ["foto_user" => empty($doc['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$doc['foto']."' style='max-width: 75px; max-height: 75px;' />"];
            $doc += ["nilai_user" => "<span>$doc[nama] ($doc[username])</span> <br> <div id=tugas-".$doc['id_user']." class='color-blue-grey-lighter'>".$waktuKumpul."<br />".$keteranganTugas."&nbsp;&nbsp;&nbsp;".$nilaiSiswa."</div></span>"];
            $doc += ["aksi" => $menuAnggota];

            $output['aaData'][] = $doc;
            $no++;
        }

        echo json_encode( $output );
    }

    if($method['action'] == 'insertTugas'){
        $tugasClass     = new Tugas();

        $status         = $tugasClass->insertNilai($method['id_user'], $method['sekolah'], $method['id_tugas'], $method['nilai'], $method['catatan'], $method['user'], $method['kelas']);

        $resp   = array('status'=>$status);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'getNilaiTugas'){
        $tugasClass     = new Tugas();
        $infoTugas      = $tugasClass->getStatusTugas($method['id_tugas'], $method['id_user']);

        $data               = array();
        $data['nilai']      = $infoTugas['nilai'];
        $data['catatan']    = $infoTugas['catatan'];

        $resp   = array('data'=>$data);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"29", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'resetTugas'){
        $id_tugas = $method['ID'];
        $id_user = $method['user'];

        $delete = array("id_tugas" => $id_tugas, "id_user" => $id_user);
        $data   = $db->tugas_kumpul->remove($delete);
    }

}

?>
