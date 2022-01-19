<?php
require("../../setting/connection.php");
require("../../setting/connection-log.php");
require("../../setting/connection-MSSQL.php");

session_start();

$method	= $_REQUEST;
$table  = $db->kelas;
$table2 = $db->anggota_kelas;

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $resp   = array('data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
    }
    
    if($method['action'] == 'verification'){
        $NPSN   = $method['npsn'];
        $stmt   = $DB_Conn->prepare("
                                    SELECT TOP 1
                                        sekolah_id, npsn, nama, email, nomor_telepon, kabupaten, provinsi
                                    FROM skl.sekolah
                                    WHERE npsn = :npsn");
                $stmt->execute(array(":npsn" => $NPSN));
        $data   = $stmt->fetch(PDO::FETCH_ASSOC);

        $resp   = array('data'=>$data);
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

	if($method['action'] == 'showList'){
        $catch  = $table2->find(array("id_user" => $method['ID']));
		$count  = $catch->count();
		if($count > 0){
			$u	= 0;
			foreach ($catch as $row) {
				// $data[]   = $row;
				$id_kelas	= new MongoId($row['id_kelas']);
				$catch2 	= $table->find(array("_id" => $id_kelas));
				// $catch2 	= $table->find(array('$or' => array(
													// array("_id" => $id_kelas),
													// array("creator"=>$method['ID'])
											// )));

				foreach ($catch2 as $row2) {
					$data[$u]   = $row2;
					$data[$u]['hak'] = $row['status'] == 1 ? " (Administrator Kelas)" : ($row['status'] == 2 ? " (Guru Mata Pelajaran)" : ($row['status'] == 3 ? " (Tutor)" : " (Siswa)"));
				}
				$count  = $catch2->count();
				$u++;
			}
		}else{
			$data	= [];
		}
		// $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

	if($method['action'] == 'lockKelas'){
        $ID     = $method['ID'];
        $userID = $method['user'];
        $catch  = $table->findOne(array("_id" => new MongoId($ID), "creator"=> $userID));
        if (isset($catch['_id'])) {
            if($catch['status'] == 'LOCKED'){
                $update     = array('$set' => array("status"=>"", "date_modified"=>date('Y-m-d H:i:s')));
                $message    = "Kelas berhasil dibuka.";
            }else {
                $update     = array('$set' => array("status"=>"LOCKED", "date_modified"=>date('Y-m-d H:i:s')));
                $message    = "Kelas berhasil dikunci.";
            }

            try {
                $table->update(array("_id" => new MongoId($ID)), $update);
                $status     = "Success";

                //------ Menulis LOG ---------
                // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$userID", "id_kelas"=>"$ID", "aksi"=>"9", "id_data"=>"$ID", "date_created"=>date('Y-m-d H:i:s')));
            } catch(MongoCursorException $e) {
                $status     = "Failed 1.";
            }
        } else {
            $status     = "Failed 2.";
        }

        $resp   = array('status'=>$status, "message"=>$message);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'rmv'){
        $query  = $table->findOne(array('_id'=>new MongoId($method['ID'])));
        if ($query['creator'] == $method['u']) {
            // ----- REMOVE CLASSROOM
            $delete = array("_id" => new MongoId($method['ID']));
            $data   = $table->remove($delete);

            // ----- REMOVE MEMBER CLASSROOM
            $delete2    = array("id_kelas"=>$method['ID']);
            $data2      = $table2->remove($delete2);

            //------ Menulis LOG ---------
            // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[u]", "id_kelas"=>"$method[ID]", "aksi"=>"6", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

            $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
        } else {
            $resp   = array('response'=>'Gagal!', 'message'=>'Anda tidak memiliki hak untuk melakukan hal ini!', 'icon'=>'error');
        }

		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'removeAnggota'){
        $aksi   = $method['a'];
        $delete = array("id_user" => $method['ID'], "id_kelas"=>$method['kelas']);
        $data   = $table2->remove($delete);

        //------ Menulis LOG ---------
        // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[u]", "id_kelas"=>"$method[kelas]", "aksi"=>$aksi, "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Berhasil!', 'message'=>'Anggota Kelas berhasil dihapus!', 'icon'=>'success');
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'cPriv'){
        $ID     = $method['ID'];
        $priv   = $method['hak_akses'];
        $kelas  = $method['kelas'];
		$teks	= $priv == 3 ? 'Tutor Pendamping' : 'Tutor Mata Pelajaran';
        $update     = array('$set' => array("status"=>$priv));

        try {
            $table2->update(array("id_user" => $ID, "id_kelas" => $kelas), $update);
            $status     = "Success";
            $message    = "Hak Akses berubah menjadi ".$teks;
            $icon       = "success";

            //------ Menulis LOG ---------
            // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[u]", "id_kelas"=>"$method[kelas]", "aksi"=>"46", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));
        } catch(MongoCursorException $e) {
            $status     = "Failed 1.";
            $message    = "Gagal Berubah.";
            $icon       = "error";
        }

        $resp   = array("status"=>$status, "message"=>$message, "icon"=>$icon);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'cTKB'){
        $ID     = $method['ID'];
        $tkb    = $method['tkb'];
        $kelas  = $method['kelas'];
        $update     = array('$set' => array("tkb"=>$tkb));

        try {
            $table2->update(array("id_user" => $ID, "id_kelas" => $kelas), $update);
            $status     = "Berhasil";
            $message    = "Tempat Kegiatan Belajar anda sudah diubah.";
            $icon       = "success";

            //------ Menulis LOG ---------
            // $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[u]", "id_kelas"=>"$method[kelas]", "aksi"=>"13", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));
        } catch(MongoCursorException $e) {
            $status     = "Maaf";
            $message    = "Tempat Kegiatan Belajar anda gagal diubah.";
            $icon       = "error";
        }

        $resp   = array("status"=>$status, "message"=>$message, "icon"=>$icon);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'daftarPerkembangan'){
        $idmodul    = $method['Modul'];
        $idkelas    = $method['Kelas'];
        $nilaiModul = 0;
        $nilaiTugas = 0;
        $nilaiUjian = 0;
        $users      = array();

        $modul      = $db->modul->findOne(array("_id"=> new MongoId($idmodul)));

        $anggota    = $table2->find(array("id_kelas" => "$idkelas", "status"=> "4"));
        $no = 0;
        foreach ($anggota as $listA) {
            $user       = $db->user->findOne(array("_id" => new MongoId($listA['id_user'])));
            $user['tkb']   = $listA['tkb'];

            $cekNilaiModul  = $db->modul_kumpul->findOne(array("id_modul"=>"$idmodul", "id_user"=>$listA['id_user']));
            // --- Nilai Membaca Materi
            $nilaiModul     += $cekNilaiModul['nilai'];
            $user['nilai']['modul'] = $nilaiModul;

            $tugasModul =  $db->tugas->find(array("id_modul"=>"$idmodul"));
            $jumlahTugas = $tugasModul->count();
            if ($jumlahTugas > 0) {
                $kumpulTugas= 0;
                foreach ($tugasModul as $tugas) {
                    $cekNilaiTugas  = $db->tugas_kumpul->findOne(array("id_tugas"=>"$tugas[_id]", "id_user"=>$listA['id_user']));

                    // --- Nilai Tugas
                    $nilaiTugas     += $nilaiTugas + $cekNilaiTugas['nilai'];
                    $user['nilai']['tugas'][$kumpulTugas]   = $cekNilaiTugas['nilai'];
                    $kumpulTugas++;
                }
                // --- Nilai Rata-Rata Tugas
                $totalTugas = round(($nilaiTugas/$jumlahTugas), 2);

                $evaluasi  = $db->quiz->findOne(array('id_modul' => "$idmodul"));
                if ($evaluasi) {
                    $cekNilaiEvaluasi   = $db->kumpul_quiz->findOne(array("id_quiz"=>"$evaluasi[_id]", "id_user"=>$listA['id_user']));

                    // --- Nilai Ujian
                    $nilaiUjian         += $cekNilaiEvaluasi['nilai'];
                    $user['nilai']['evaluasi']   = $cekNilaiEvaluasi['nilai'];
                }
            } else {
                $totalTugas = 100;
                $evaluasi  = $db->quiz->findOne(array('id_modul' => "$idmodul"));
                if ($evaluasi) {
                    $cekNilaiEvaluasi   = $db->kumpul_quiz->findOne(array("id_quiz"=>"$evaluasi[_id]", "id_user"=>$listA['id_user']));

                    // --- Nilai Ujian
                    $nilaiUjian         += $cekNilaiEvaluasi['nilai'];
                    $user['nilai']['evaluasi']   = $cekNilaiEvaluasi['nilai'];
                }
            }

            $persentaseModul = $modul['nilai']['materi'];
            $persentaseTugas = $modul['nilai']['tugas'];
            $persentaseUjian = $modul['nilai']['ujian'];
            $nilaiMinimal    = $modul['nilai']['minimal'];

            $nilaiAkhirModul    = $persentaseModul == 0 ? 0 : round($nilaiModul * ($persentaseModul/100), 2);
            $nilaiAkhirTugas    = $persentaseTugas == 0 ? 0 : round($totalTugas * ($persentaseTugas/100), 2);
            $nilaiAkhirUjian    = $persentaseUjian == 0 ? 0 : round($nilaiUjian * ($persentaseUjian/100), 2);
            $hasil              = round($nilaiAkhirModul + $nilaiAkhirTugas + $nilaiAkhirUjian, 2);

            // Nilai Akhir
            $user['nilai']['akhir']   = $hasil;

            $no++;
            $users[]    = $user;
        }
		$totalData	= count($users);
		$totalFiltered = $totalData;

		$totalFiltered	= count($users);

		if($totalFiltered > 0){
			foreach($users as $row){
				$nestedData     = array();
				$nestedData[]   = $row["nama"];
				$nestedData[]   = $row["tkb"];
				$nestedData[]   = $row["nilai"]["akhir"];
				// $nestedData[] = $row["id"];
				// $nestedData[] = $row["nama"]." ".$row['batch'];
				// $nestedData[] = date("d M Y", strtotime($row["mulai"]))." s/d <br /> ".date("d M Y", strtotime($row['sampai']));
				// $nestedData[] = date("d M Y H:i:s", strtotime($row["lastupdate"]));
				// $nestedData[] = '<center>
				// 										<button data-toggle="tooltip" data-placement="bottom" title="Edit"  onclick="edit('.$row["id"].')" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> </button>
				// 										<button data-toggle="tooltip" data-placement="bottom" title="Remove"  onclick="remove('.$row["id"].')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button>
				// 								</center>';
				$data[] = $nestedData;
			}
		}else{
			$data	= [];
		}

		$response = array(
						"draw"            => intval( $method['draw'] ),
						"recordsTotal"    => intval( $totalData ),
						"recordsFiltered" => intval( $totalFiltered ),
						"data"            => $data,
                        "kolom"           => $jumlahTugas + 2
					);

		$jsonResponse     = json_encode($response);
		header('Content-Type: application/json');

		echo $jsonResponse;
    }

    if($method['action'] == 'anggotaKelas'){

        /**
         * Define the document fields to return to DataTables (as in http://us.php.net/manual/en/mongocollection.find.php).
         * If empty, the whole document will be returned.
         */
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

        if($method['hak']=='All'){
            $matchs = array(
                '$match' => array(
                    'id_kelas' => $method['kelas'],
                    'id_user' => array('$ne' => '')
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
                        array("status" => (int) $method['hak']),
                        array("status" => $method['hak'])
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

        foreach ( $cursor[0]['paginatedResults'] as $doc ) {
            $menu		= '';

            if ($doc['status_user'] == 'guru') {
                if ((int) $doc['status'] == 2) {
                    $menu		= '<div class="btn-group" style="float: right;">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="radio">
                                                <a class="dropdown-item" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-2\', 2)">
                                                    <input type="radio" name="statusGuru'.$doc['id_user'].'" id="statusGuru'.$doc['id_user'].'-2" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-2\', 2)" value="2" checked >
                                                    <label for="statusGuru2">Tutor (Pengampu Mata Pelajaran) </label>
                                                </a>
                                                <a class="dropdown-item" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-3\', 3)">
                                                    <input type="radio" name="statusGuru'.$doc['id_user'].'" id="statusGuru'.$doc['id_user'].'-3" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-3\', 3)" value="3" >
                                                    <label for="statusGuru3">Tutor (Pendamping) </label>
                                                </a>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" onclick="remove(\''.$doc['id_user'].'\')">Hapus Anggota</a>
                                        </div>
                                    </div>';
                }elseif ((int) $doc['status'] == 3) {
                    $menu		= '<div class="btn-group" style="float: right;">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="tb-lg">Aksi</span>
                                            <span class="tb-sm"><i class="fa fa-pencil"></i></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="radio">
                                                <a class="dropdown-item" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-2\', 2)">
                                                    <input type="radio" name="statusGuru'.$doc['id_user'].'" id="statusGuru'.$doc['id_user'].'-2" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-2\', 2)" value="2" >
                                                    <label for="statusGuru2">Tutor (Pengampu Mata Pelajaran)</label>
                                                </a>
                                                <a class="dropdown-item" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-3\', 3)">
                                                    <input type="radio" name="statusGuru'.$doc['id_user'].'" id="statusGuru'.$doc['id_user'].'-3" onclick="cPriv(\''.$doc['id_user'].'\', \'status'.$doc['id_user'].'\', \'statusGuru'.$doc['id_user'].'-3\', 3)" value="3" checked >
                                                    <label for="statusGuru3">Tutor (Pendamping)</label>
                                                </a>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" onclick="remove(\''.$doc['id_user'].'\')">Hapus Anggota</a>
                                        </div>
                                    </div>';
                }
            }elseif ($doc['status_user'] == 'siswa') {
                $menu		= '<div class="btn-group" style="float: right;">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="tb-lg">Aksi</span>
                                        <span class="tb-sm"><i class="fa fa-pencil"></i></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">';
                                        if (isset($method['tkb']) AND $method['tkb'] != '') {
                                            $no = 1;
                                            $datatkb	=  explode(',', $method['tkb']);
                    $menu	.= '		<div class="radio">';
                                            foreach ($datatkb as $tkb) {
                    $menu	.= '		<a class="dropdown-item" onclick="cTKB(\''.$doc['id_user'].'\', \''.$tkb.'\', \'namaTKB'.$no.'-'.$doc['id_user'].'\', \'member'.$doc['id_user'].'\')" >
                                                    <input type="radio" name="namaTKB'.$no.'" id="namaTKB'.$no.'-'.$doc['id_user'].'" value="'.$tkb.'"'.(isset($doc['tkb']) && $doc['tkb'] == $tkb ? "checked" : "").' >
                                                    <label for="statusGuru3">'.$tkb.' </label>
                                                </a>';
                                            $no++;
                                            }
                    $menu	.= '	</div>
                                            <div class="dropdown-divider"></div>';
                                        }
                    $menu	.= '				<a class="dropdown-item" title="Reset Password Warga Belajar" onclick="resetPassword(\''.$doc['id_user'].'\')">Reset Password</a><a class="dropdown-item" onclick="remove(\''.$doc['id_user'].'\')">Hapus Anggota</a>
                                        </div>
                                    </div>';
            }

            $menuAnggota = '';
            if ($_SESSION['lms_id'] == $doc['id_user']) {
                if ((int) $doc['status'] == 3 || (int) $doc['status'] == 4) {
                    $menuAnggota	= '<div class="btn-group" style="float: right;">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="tb-lg">Aksi</span>
                                            <span class="tb-sm"><i class="fa fa-pencil"></i></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">';
                                        if (isset($method['tkb']) AND $method['tkb'] != '') {
                                            $no = 1;
                                            $datatkb	=  explode(',', $method['tkb']);
                    $menuAnggota	.= '	<div class="radio">';
                                            foreach ($datatkb as $tkb) {
                    $menuAnggota	.= '		<a class="dropdown-item" onclick="cTKB(\''.$doc['id_user'].'\', \''.$tkb.'\', \'namaTKB'.$no.'-'.$doc['id_user'].'\', \'member'.$doc['id_user'].'\')" >
                                                    <input type="radio" name="namaTKB" id="namaTKB'.$no.'-'.$doc['id_user'].'" value="'.$tkb.'"'.(isset($doc['tkb']) && $doc['tkb'] == $tkb ? "checked" : "").' >
                                                    <label for="statusGuru3">'.$tkb.' </label>
                                                </a>';
                                            $no++;
                                            }
                    $menuAnggota	.= '	</div>
                                            <div class="dropdown-divider"></div>';
                                        }

                    $menuAnggota	.= '	<a class="dropdown-item" onclick="out(\''.$doc['id_user'].'\')">Keluar Kelas</a>
                                        </div>
                                    </div>';
                }else {
                    $menuAnggota	= '<div class="btn-group" style="float: right;">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="tb-lg">Aksi</span>
                                            <span class="tb-sm"><i class="fa fa-pencil"></i></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" onclick="out(\''.$doc['id_user'].'\')">Keluar Kelas</a>
                                        </div>
                                    </div>';
                }
            }

            $doc += ["foto_user" => "<td class='tb-lg' width='80px;'>".empty($doc['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$doc['foto']."' style='max-width: 75px; max-height: 75px;' />"."</td>" ];
            $doc += ["nama_user" => "<span class='user-name'>
                                        $doc[nama] ($doc[username])
                                    </span> <br>
                                    <span style='font-size: 0.9em;'>
                                        $doc[sekolah]
                                    </span><br>
                                    <span style='font-size: 0.9em;' id='status$doc[id_user]'>
                                        -- ".($doc['status'] == '1' ? 'Administrator':($doc['status'] == '3' ? 'Tutor (Pendamping)': ($doc['status'] == '4' ? 'Warga Belajar':'Tutor (Pengampu Mata Pelajaran)')))." --
                                    </span><br>
                                    <span style='font-size: 0.9em;' id='member$doc[id_user]'>
                                        <b>".@$doc['tkb']."</b>
                                    </span>"];

            $doc += ["aksi_user" => ($method['statusLoginUser'] == 1 ? $menu:$menuAnggota)];

            $output['aaData'][] = $doc;
            $no++;
        }

        echo json_encode( $output );
    }
}

?>
