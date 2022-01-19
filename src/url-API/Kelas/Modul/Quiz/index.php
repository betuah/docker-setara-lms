<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
require("../../../../setting/connection.php");
require("../../../../setting/connection-log.php");
spl_autoload_register(function ($class) {
  include '../../../../setting/controller/' .$class . '.php';
});

$method	= $_REQUEST;

if(isset($method['action'])){

    if($method['action'] == 'saveAnswer'){

        $opsi_soal  = $db->opsi_soal->findOne(array("_id" => new MongoId($_POST['id_opsi_soal'])));
        $status     = $opsi_soal['status'];

        $jawaban_user_soal_ini  = $db->jawaban_user->findOne(array("id_quiz" => $_POST['id_quiz'], "id_soal" => $_POST['id_soal'], "id_user" => (string)$_SESSION['lms_id']));
        $status_jawaban_user    = $jawaban_user_soal_ini['status'];

        $update     = array('$set' => array("id_opsi_soal" => $_POST['id_opsi_soal'], "status" => $status, "date_modified"=>date('Y-m-d H:i:s')));

        try {

            $db->jawaban_user->update(array("id_user" => (string)$_SESSION['lms_id'], "id_quiz" => $_POST['id_quiz'], "id_soal" => $_POST['id_soal']), $update, array("upsert" => true));
            //$status     = "Success";
        } catch(MongoCursorException $e) {

            //$status     = "Failed";
        }

        $resp   = array('status'=>$status, 'jawaban_user'=>$status_jawaban_user);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
	}

    if($method['action'] == 'saveAnswerNew'){

        $update     = array('$set' => array("id_opsi_soal" => $_POST['id_opsi_soal'], "status" => $status, "date_modified"=>date('Y-m-d H:i:s')));

        try {

            $db->jawaban_user->update(array("id_user" => (string)$_SESSION['lms_id'], "id_quiz" => $_POST['id_quiz'], "id_soal" => $_POST['id_soal']), $update, array("upsert" => true));
            $status     = "Success";
        } catch(MongoCursorException $e) {

            $status     = "Failed";
        }

        $resp   = array('status'=>$status);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
	}

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $db->quiz->remove($delete);

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>"$method[sekolah]", "id_kelas"=>"$method[kelas]", "aksi"=>"34", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

    if($method['action'] == 'showDaftarPenilaianSiswa'){
        $kelasClass		= new Kelas();
        $mapelClass 	= new Mapel();
        $modulClass 	= new Modul();
        $quizClass      = new Quiz();
        $userClass		= new User();

        $infoModul		= $modulClass->getInfoModul($method['modul']);
        $infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
        $infoKelas		= $kelasClass->getInfoKelas($infoMapel['id_kelas']);

        foreach (array_values($infoKelas['list_member']) as $data) {
            $menu		= '';
            $infoUser	= $userClass->GetData($data);
            $infoHak	= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], "$infoUser[_id]");

            if ($infoUser['status'] == 'siswa'){

                $nilaiQuiz       = $quizClass->getStatusQuiz($method['ID'], $infoUser['_id'])['nilai'];

                // if($nilaiQuiz){
                //     $menuAnggota	= '<div class="btn-group" style="float: right;">
                //                         <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                //                             Aksi
                //                         </button>
                //                         <div class="dropdown-menu dropdown-menu-right">
                //                             <a onclick="reset_quiz(\''.$method['ID'].'\', \''.$infoUser['_id'].'\')" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                //                         </div>
                //                     </div>';
                // }else{
                //     $menuAnggota	= '<div class="btn-group" style="float: right;">
                //                         <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                //                             Aksi
                //                         </button>
                //                         <div class="dropdown-menu dropdown-menu-right">
                //                             <a onclick="reset_quiz_none()" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                //                         </div>
                //                     </div>';
                // }

                $menuAnggota	= '<div class="btn-group" style="float: right;">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="reset_quiz(\''.$method['ID'].'\', \''.$infoUser['_id'].'\')" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                                    </div>
                                </div>';

                $statusQuiz      = $nilaiQuiz?"<span class='label label-success'>Sudah mengerjakan</span>" : "<span class='label label-warning'>Belum mengerjakan</span>";

                $nilaiSiswa      = $nilaiQuiz?"<i class='fa fa-table' aria-hidden='true'></i> Nilai: ".round((($nilaiQuiz/$method['jumlah_soal'])*100), 2) : "<i class='fa fa-table' aria-hidden='true'></i> Nilai: -";

                $statusPenilaian    = '';

                $image		= empty($infoUser['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$infoUser['foto']."' style='max-width: 75px; max-height: 75px;' />" ;
                echo "	<tr>
                            <td width='80px;'>".$image."</td>
                            <td><span class='user-name'>$infoUser[nama] ($infoUser[username])</span> <br> <div class='color-blue-grey-lighter'>".$nilaiSiswa."</div>".$statusQuiz." </span></td>
                            <td width='70px;' class='shared'>".$statusPenilaian."</td>
                            <td width='70px;' class='shared'>".$menuAnggota."</td>
                        </tr>";
            }
        }
    }

    if($method['action'] == 'showDaftarPenilaianSiswaAjax'){
        $fields    = array();
        $profileClass   = new Profile();

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
            $kumpulQuiz       = $db->kumpul_quiz->findOne(array("id_quiz" => $method['ID'], "id_user" => $doc['id_user']));

            $menuAnggota	= '<div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Aksi
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a onclick="reset_quiz(\''.$method['ID'].'\', \''.$doc['id_user'].'\')" class="dropdown-item" disable><i class="font-icon font-icon-refresh"></i> Reset</a>
                                </div>
                            </div>';

            $nilai_akhir_quiz = round((($kumpulQuiz['nilai']/$method['jumlah_soal'])*100), 2);

            if($nilai_akhir_quiz > 100){
                $nilai_akhir_quiz = $kumpulQuiz['nilai'];
            }

            $statusQuiz      = $kumpulQuiz['nilai']?"<span class='label label-success'>Sudah mengerjakan</span>" : "<span class='label label-warning'>Belum mengerjakan</span>";
            $nilaiSiswa      = $kumpulQuiz['nilai']?"<i class='fa fa-table' aria-hidden='true'></i> Nilai: ".$nilai_akhir_quiz : "<i class='fa fa-table' aria-hidden='true'></i> Nilai: -";
            $waktu_kumpul    = $kumpulQuiz['nilai']?"<i class='fa fa-clock-o' aria-hidden='true'></i> Mengumpulkan ".$profileClass->selisih_waktu($kumpulQuiz['date_created']):"<i class='fa fa-clock-o' aria-hidden='true'></i> -";

            $doc += ["no" => $no];
            $doc += ["nama" => $doc['nama']];
            $doc += ["nilai" => $nilai_akhir_quiz];
            $doc += ["waktu_kumpul" => $kumpulQuiz['date_created']?$kumpulQuiz['date_created']:" - "];
            $doc += ["foto_user" => empty($doc['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$doc['foto']."' style='max-width: 75px; max-height: 75px;' />"];
            $doc += ["nilai_user" => "<span class='user-name'>$doc[nama] ($doc[username])</span> <br> <div id='".$doc['id_user']."' class='color-blue-grey-lighter'>".$waktu_kumpul."<br />".$statusQuiz."&nbsp;&nbsp;&nbsp;".$nilaiSiswa."</div></span>"];
            $doc += ["aksi" => $menuAnggota];

            $output['aaData'][] = $doc;
            $no++;
        }

        echo json_encode( $output );
    }

    if($method['action'] == 'showDaftarSoal'){
        $soalClass		= new Soal();

        $m_collection   = $db->soal;
        /**
         * Define the document fields to return to DataTables (as in http://us.php.net/manual/en/mongocollection.find.php).
         * If empty, the whole document will be returned.
         */

        $input     =& $_GET;
        $iColumns  = $input['iColumns'];

        $dataProps = array();

        for ($i = 0; $i < $iColumns; $i++) {
            $var = 'mDataProp_'.$i;
            if (!empty($input[$var]) && $input[$var] != 'null') {
                $dataProps[$i] = $input[$var];
            }
        }

        $cursor = $m_collection->find(array("id_paket" => $method['paket'], "soal" => new MongoRegex("/".$input['sSearch']."/i")))->limit(5);

        if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
            $cursor->limit( $input['iDisplayLength'] )->skip( $input['iDisplayStart'] );
        }

        if ( isset($input['iSortCol_0']) ) {
            $sort_fields = array();
            for ( $i=0 ; $i<intval( $input['iSortingCols'] ) ; $i++ ) {
                if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
                    $field = $dataProps[ intval( $input['iSortCol_'.$i] ) ];
                    $order = ( $input['sSortDir_'.$i]=='desc' ? -1 : 1 );
                    $sort_fields[$field] = $order;
                }
            }
            $cursor->sort($sort_fields);
        }

        $output = array(
            "sEcho" => intval($input['sEcho']),
            "iTotalRecords" => $m_collection->count(),
            "iTotalDisplayRecords" => $cursor->count(),
            "aaData" => array()
        );

        $jumlah_soal    = $cursor->count();

        $no = $input['iDisplayStart']+1;
        foreach ( $cursor as $doc ) {
            $opsi = "";
            $list_opsi_soal     = $soalClass->getListOpsiSoal($doc['_id'], 0);

            foreach ($list_opsi_soal as $opsi_soal) {
                if($opsi_soal['status'] == "benar"){
                    $checked    = "checked";
                }else{
                    $checked    = "";
                }
                $opsi           .= "<div class='radio'>
                                        <input type='radio' name='".$doc['_id']."' id='".$opsi_soal['_id']."' value='".$opsi_soal['_id']."' ".$checked." onclick='return false;'>
                                        <label for='".$opsi_soal['_id']."' style='font-size: inherit;'>".$opsi_soal['text']."</label>
                                    </div>";
            }

            if ($_SESSION['lms_id'] == $doc['creator'] OR $method['hakKelas'] == 1 OR $method['hakKelas'] == 2) {
                if($method['status'] == 1){
                    $menu = '  <a href="javascript:void(0)" onclick="peringatan_edit()" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Soal yang sudah dibuat." style="right: 35px">
                                    <i class="font-icon font-icon-pencil")"></i>
                                <a href="javascript:void(0)" onclick="peringatan_edit()"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Soal yang sudah dibuat.">
                                    <i class="font-icon font-icon-trash")"></i>
                                </a>';
                }else{
                    $menu = '  <a href="edit-quiz?act=update&md='.$method['modul'].'&qz='.$method['quiz'].'&id='.$doc['_id'].'" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Soal yang sudah dibuat." style="right: 35px">
                                    <i class="font-icon font-icon-pencil")"></i>
                                <a href="javascript:void(0)" onclick="remove(\''.$doc['_id'].'\')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Soal yang sudah dibuat.">
                                    <i class="font-icon font-icon-trash")"></i>
                                </a>';
                }
            }else{
                $menu = "[ - ]";
            }
            $doc += ["no_soal"    => "(".$no++.")"];
            $doc += ["view_soal"  => "<div>".$doc['soal']."</div>".$opsi];
            $doc += ["menu_soal"  => $menu];

            $output['aaData'][] = $doc;
        }

        echo json_encode( $output );
    }

    if($method['action'] == 'resetQuiz'){
        $id_quiz = $method['ID'];
        $id_user = $method['user'];

        $delete = array("id_quiz" => $id_quiz, "id_user" => $id_user);
        $data   = $db->kumpul_quiz->remove($delete);
        $data   = $db->jawaban_user->remove($delete);
    }

    if($method['action'] == 'generateKode'){
        $quizClass = new Quiz();
        $id_quiz = $method['ID'];

        $kode    = $quizClass->acakKodeQuiz(6);

        $update     = array('$set' => array("kode" => $kode, "date_modified"=>date('Y-m-d H:i:s')));

        try {
            $db->quiz->update(array("_id" => new MongoId($id_quiz)), $update);
            $status     = "Success";
            $message    = "Kode Ujian berhasil Digenerate.";
        } catch(MongoCursorException $e) {
            $status     = "Failed";
            $message    = "Kode Ujian gagal Digenerate.";
        }

        $resp   = array('status'=>$status, 'message'=>$message);
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }
}

?>
