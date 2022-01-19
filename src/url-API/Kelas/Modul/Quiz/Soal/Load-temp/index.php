<?php
    session_start();
    require("../../../../../../setting/connection.php");
    require("../../../../../../setting/connection-log.php");
    require("../../../../../../setting/controller/Quiz.php");
    require("../../../../../../setting/controller/Soal.php");

    $method	        = $_REQUEST;
    $table          = $db->soal;
    $id_quiz        = $method['quiz'];
    $random_opsi    = $method['opsi'];

    $quizClass      = new Quiz();
    $soalClass      = new Soal();

    $m_collection   = $table;
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

    $cursor = $m_collection->find($searchTerms, $fields)->limit(5);

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
    foreach ( $cursor as $doc ) {
        $id_soal            = $doc['_id'];
        $page               = ceil($input['iDisplayStart']/$input['iDisplayLength'])+1;
        $header_soal        = "<h5 class='with-border'>Nomor ".$page."<span class='pull-right'>".$page." dari ".$jumlah_soal."</span></h5>";
        $jawaban_user       = $_COOKIE["$id_soal"];//$soalClass->getOpsiJawabanUser($_SESSION['lms_id'], $id_quiz, $doc['_id']);
        $list_opsi_soal     = $soalClass->getListOpsiSoal($doc['_id'], $random_opsi);

        foreach ($list_opsi_soal as $opsi_soal) {
            if($opsi_soal['_id'] == $jawaban_user){
                $checked    = "checked";
            }else{
                $checked    = "";
            }
            $opsi           .= "<div class='radio'>
                                    <input type='radio' name='".$doc['_id']."' id='".$opsi_soal['_id']."' value='".$opsi_soal['_id']."' onclick=save_answer('".$id_quiz."','".$doc['_id']."','".$opsi_soal['_id']."','".$opsi_soal['status']."') ".$checked.">
                                    <label for='".$opsi_soal['_id']."'>".$opsi_soal['text']."</label>
                                </div>";
        }
        $doc += ["view_soal"    => $header_soal.$doc['soal'].$opsi];

        $output['aaData'][] = $doc;
    }

    echo json_encode( $output );

?>
