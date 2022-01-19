<?php
require("../../../setting/connection.php");
require("../../../setting/connection-log.php");

$method	= $_REQUEST;
$table  = $db->mata_pelajaran;

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $resp   = array('data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'showAll'){
        $catch  = $table->find()->sort(array('nama' => 1));
        foreach ($catch as $row) {
            $data[]   = $row;
        }
        $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

	if($method['action'] == 'showList'){
        //$catch  = $table->find(array("id_kelas" => $method['ID']))->sort(array('nama' => 1));
        $catch  = $table->find(array("id_kelas" => $method['ID']));
        $data = array();
		$u = 0;
        foreach ($catch as $row) {
			$data[$u]   = $row;
			$data[$u]['hak'] = $row['creator'];
			// $id_kelas	= new MongoId($row['id_kelas']);
			// $catch2 	= $table->find(array("_id" => $id_kelas));
            // // $catch2 	= $table->find(array('$or' => array(
												// // array("_id" => $id_kelas),
												// // array("creator"=>$method['ID'])
										// // )));
			// foreach ($catch2 as $row2) {
				// $data[]   = $row2;
			// }
			// $count  = $catch2->count();
			$u++;
        }
		$count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'removeMapel'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);
        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"16", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));
        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'perkembangan'){
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

        if($method['status'] == 'guru'){
            if($method['tkb']!="0"){
                $matchs = array(
                    '$match'  => array(
                        '$and' => array(
                            array("id_kelas" => $method['kelas']),
                            array("tkb" => $method['tkb']),
                            array("id_user" => array('$ne' => ''))
                        ),
                        '$or' => array(
                            array("status" => 4),
                            array("status" => "4")
                        )
                    )
                );
            }else{
                $matchs = array(
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
                );
            }
        }else{
            $matchs = array(
                '$match'  => array(
                    '$and' => array(
                        array("id_kelas" => $method['kelas']),
                        array("id_user" => $method['akun'])
                    ),
                    '$or' => array(
                        array("status" => 4),
                        array("status" => "4")
                    )
                )
            );
        }

        $pipeline = array(
            $matchs,
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

        $db_daftar_nilai_modul  = $db->modul_kumpul->find(array('id_modul' => $method['modul']), array('_id' => 0, 'id_modul' => 1, 'id_user' => 1, 'nilai' => 1));

        $daftar_nilai = array();
        foreach ($db_daftar_nilai_modul as $nilai) {
            $daftar_nilai[$nilai['id_user']] = $nilai['nilai'];
        }

        $list_tugas = explode(";",$method['tugas']);

        $options = array();
        foreach ($list_tugas as $tugas) {
            array_push($options, array('id_tugas' => $tugas));
        }
        $db_daftar_nilai_tugas   = $db->tugas_kumpul->find(array('$or' => $options));

        $daftar_nilai_tugas = array();
        foreach ($db_daftar_nilai_tugas as $nilai) {
            $daftar_nilai_tugas[$nilai['id_user']][$nilai['id_tugas']] = $nilai['nilai'];
        }

        $list_quiz  = explode(";",$method['ujian']);

        $options = array();
        foreach ($list_quiz as $quiz) {
            $id_quiz = explode('|', $quiz)[0];
            array_push($options, array('id_quiz' => $id_quiz));
        }
        $db_daftar_nilai_quiz   = $db->kumpul_quiz->find(array('$or' => $options));

        $daftar_nilai_quiz = array();
        foreach ($db_daftar_nilai_quiz as $nilai) {
            $daftar_nilai_quiz[$nilai['id_user']][$nilai['id_quiz']] = $nilai['nilai'];
        }

        foreach ( $cursor[0]['paginatedResults'] as $doc ) {

            $nilai_akhir = 0;

            $nilai_materi = $daftar_nilai[$doc['id_user']];

            if(empty($doc['tkb'])){
                $doc['tkb'] = " - ";
            }

            if(empty($nilai_materi)){
                $nilai_materi = " - ";
            }else{
                $nilai_materi = $method['bobot_materi'];
            }

            $doc += ["nilai_materi" => $nilai_materi];

            $nilai_akhir_tugas = 0;
            if(sizeof($list_tugas) > 1){
                $jumlah_tugas        = 0;
                $jumlah_nilai_tugas  = 0;
                foreach ($list_tugas as $tugas) {
                    $nilai_tugas = $daftar_nilai_tugas[$doc['id_user']][$tugas];

                    if(isset($nilai_tugas)){
			if(empty($nilai_tugas)){
                            $nilai_tugas = 0;
                        }
                        $doc += ["nilai_tugas_$tugas" => $nilai_tugas];
                    }else{
                        $doc += ["nilai_tugas_$tugas" => " - "];
                    }
                    $jumlah_nilai_tugas += $nilai_tugas;
                    $jumlah_tugas++;
                }
                $jumlah_tugas--;

                $avg_nilai_tugas = $jumlah_nilai_tugas/$jumlah_tugas;
                $nilai_akhir_tugas = round((($avg_nilai_tugas/100)*$method['bobot_tugas']), 2);
            }else{
                if($nilai_materi == " - "){
                    $doc += ["nilai_tugas" => " - "];
                }else{
                    $doc += ["nilai_tugas" => $method['bobot_tugas']];
                }

                $nilai_akhir_tugas = $method['bobot_tugas'];
            }

            $nilai_akhir_quiz = 0;
            if(sizeof($list_quiz) > 1){
                $jumlah_quiz        = 0;
                $jumlah_nilai_quiz  = 0;
                foreach ($list_quiz as $quiz) {
                    $quiz = explode('|', $quiz);

                    $nilai_quiz = $daftar_nilai_quiz[$doc['id_user']][$quiz[0]];

                    if(isset($nilai_quiz)){
                        $nilai = round((($nilai_quiz*100)/$quiz[1]), 2);
                        if($nilai > 100){
                            $nilai = $nilai_quiz;
                        }
                        $doc += ["nilai_ujian_$quiz[0]" => $nilai];
                    }else{
                        $nilai = 0;
                        $doc += ["nilai_ujian_$quiz[0]" => " - "];
                    }
                    $jumlah_nilai_quiz += $nilai;
                    $jumlah_quiz++;
                }
                $jumlah_quiz--;

                $avg_nilai_quiz = $jumlah_nilai_quiz/$jumlah_quiz;
                $nilai_akhir_quiz = round((($avg_nilai_quiz/100)*$method['bobot_ujian']), 2);
            }else{
                if($nilai_materi == " - "){
                    $doc += ["nilai_ujian" => " - "];
                }else{
                    $doc += ["nilai_ujian" => $method['bobot_ujian']];
                }

                $nilai_akhir_quiz = $method['bobot_ujian'];
            }

            if($nilai_materi == " - "){
                // $doc += ["nilai_akhir" => " - "];
                $nilai_akhir = $nilai_akhir_tugas+$nilai_akhir_quiz;
                $doc += ["nilai_akhir" => $nilai_akhir];
            }else{
                $nilai_akhir = $nilai_materi+$nilai_akhir_tugas+$nilai_akhir_quiz;
                $doc += ["nilai_akhir" => $nilai_akhir];
            }

            $output['aaData'][] = $doc;
            $no++;
        }

        echo json_encode( $output );
    }

    if($method['action'] == 'perkembanganAll'){
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

        if($method['status'] == 'guru'){
            if($method['tkb']!="0"){
                $matchs = array(
                    '$match'  => array(
                        '$and' => array(
                            array("id_kelas" => $method['kelas']),
                            array("tkb" => $method['tkb'])
                        ),
                        '$or' => array(
                            array("status" => 4),
                            array("status" => "4")
                        )
                    )
                );
            }else{
                $matchs = array(
                    '$match'  => array(
                        '$and' => array(
                            array("id_kelas" => $method['kelas'])
                        ),
                        '$or' => array(
                            array("status" => 4),
                            array("status" => "4")
                        )
                    )
                );
            }
        }else{
            $matchs = array(
                '$match'  => array(
                    '$and' => array(
                        array("id_kelas" => $method['kelas']),
                        array("id_user" => $method['akun'])
                    ),
                    '$or' => array(
                        array("status" => 4),
                        array("status" => "4")
                    )
                )
            );
        }

        $pipeline = array(
            $matchs,
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

        $db_daftar_nilai_modul  = $db->modul_kumpul->find(array('id_modul' => $method['modul']), array('_id' => 0, 'id_modul' => 1, 'id_user' => 1, 'nilai' => 1));

        $daftar_nilai = array();
        foreach ($db_daftar_nilai_modul as $nilai) {
            $daftar_nilai[$nilai['id_user']] = $nilai['nilai'];
        }

        $list_tugas = explode(";",$method['tugas']);

        $options = array();
        foreach ($list_tugas as $tugas) {
            array_push($options, array('id_tugas' => $tugas));
        }
        $db_daftar_nilai_tugas   = $db->tugas_kumpul->find(array('$or' => $options));

        $daftar_nilai_tugas = array();
        foreach ($db_daftar_nilai_tugas as $nilai) {
            $daftar_nilai_tugas[$nilai['id_user']][$nilai['id_tugas']] = $nilai['nilai'];
        }

        $list_quiz  = explode(";",$method['ujian']);

        $options = array();
        foreach ($list_quiz as $quiz) {
            $id_quiz = explode('|', $quiz)[0];
            array_push($options, array('id_quiz' => $id_quiz));
        }
        $db_daftar_nilai_quiz   = $db->kumpul_quiz->find(array('$or' => $options));

        $daftar_nilai_quiz = array();
        foreach ($db_daftar_nilai_quiz as $nilai) {
            $daftar_nilai_quiz[$nilai['id_user']][$nilai['id_quiz']] = $nilai['nilai'];
        }

        foreach ( $cursor[0]['paginatedResults'] as $doc ) {

            $nilai_akhir = 0;

            $nilai_materi = $daftar_nilai[$doc['id_user']];

            if(empty($doc['tkb'])){
                $doc['tkb'] = " - ";
            }

            if(empty($nilai_materi)){
                $nilai_materi = " - ";
            }else{
                $nilai_materi = $method['bobot_materi'];
            }

            $doc += ["nilai_materi" => $nilai_materi];

            $nilai_akhir_tugas = 0;
            if(sizeof($list_tugas) > 1){
                $jumlah_tugas        = 0;
                $jumlah_nilai_tugas  = 0;
                foreach ($list_tugas as $tugas) {
                    $nilai_tugas = $daftar_nilai_tugas[$doc['id_user']][$tugas];

                    if(isset($nilai_tugas)){
                        $doc += ["nilai_tugas_$tugas" => $nilai_tugas];
                    }else{
                        $doc += ["nilai_tugas_$tugas" => " - "];
                    }
                    $jumlah_nilai_tugas += $nilai_tugas;
                    $jumlah_tugas++;
                }
                $jumlah_tugas--;

                $avg_nilai_tugas = $jumlah_nilai_tugas/$jumlah_tugas;
                $nilai_akhir_tugas = round((($avg_nilai_tugas/100)*$method['bobot_tugas']), 2);
            }else{
                if($nilai_materi == " - "){
                    $doc += ["nilai_tugas" => " - "];
                }else{
                    $doc += ["nilai_tugas" => $method['bobot_tugas']];
                }

                $nilai_akhir_tugas = $method['bobot_tugas'];
            }

            $nilai_akhir_quiz = 0;
            if(sizeof($list_quiz) > 1){
                $jumlah_quiz        = 0;
                $jumlah_nilai_quiz  = 0;
                foreach ($list_quiz as $quiz) {
                    $quiz = explode('|', $quiz);

                    $nilai_quiz = $daftar_nilai_quiz[$doc['id_user']][$quiz[0]];

                    if(isset($nilai_quiz)){
                        $nilai = round((($nilai_quiz*100)/$quiz[1]), 2);
                        if($nilai > 100){
                            $nilai = $nilai_quiz;
                        }
                        $doc += ["nilai_ujian_$quiz[0]" => $nilai];
                    }else{
                        $nilai = 0;
                        $doc += ["nilai_ujian_$quiz[0]" => " - "];
                    }
                    $jumlah_nilai_quiz += $nilai;
                    $jumlah_quiz++;
                }
                $jumlah_quiz--;

                $avg_nilai_quiz = $jumlah_nilai_quiz/$jumlah_quiz;
                $nilai_akhir_quiz = round((($avg_nilai_quiz/100)*$method['bobot_ujian']), 2);
            }else{
                if($nilai_materi == " - "){
                    $doc += ["nilai_ujian" => " - "];
                }else{
                    $doc += ["nilai_ujian" => $method['bobot_ujian']];
                }

                $nilai_akhir_quiz = $method['bobot_ujian'];
            }

            if($nilai_materi == " - "){
                $doc += ["nilai_akhir" => " - "];
            }else{
                $nilai_akhir = $nilai_materi+$nilai_akhir_tugas+$nilai_akhir_quiz;
                $doc += ["nilai_akhir" => $nilai_akhir];
            }

            $output['aaData'][] = $doc;
            $no++;
        }

        echo json_encode( $output );
    }
}

?>
