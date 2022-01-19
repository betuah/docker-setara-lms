<?php
    require("../setting/connection.php");

    // $pipeline = array(
    //     array(
    //         '$match' => array(
    //             'id_kelas' => '5dc11412a2f3845c348edf12'
    //         )
    //     ),
    //     array(
    //         '$addFields' => array(
    //             'id' => array(
    //                 '$toString' => '$_id'
    //             ),
    //             '_id_user' => array(
    //                 '$toObjectId' => '$id_user'
    //             )
    //         )
    //     ),
    //     // array(
    //     //     '$lookup' => array(
    //     //         'from' => 'user',
    //     //         'localField' => '_id_user',
    //     //         'foreignField' => '_id',
    //     //         'as' => 'enrolled_info'
    //     //     )
    //     // )
    //     array(
    //         '$lookup' => array(
    //             'from' => 'user',
    //             'let' => array('id_user' => '$_id_user'),
    //             'pipeline' => array(
    //                 array(
    //                     '$match' => array(
    //                         '$expr' => array(
    //                             '$eq' => array('$_id','$$id_user')
    //                         ),
    //                         'nama' => new MongoRegex("/a/i")
    //                     )
    //                 )
    //             ),
    //             'as' => 'enrolled_info'
    //         )
    //     ),
    //     array(
    //         '$limit' => 5
    //     ),
    //     array(
    //         '$skip' => 0
    //     ),
    //     array(
    //         '$sort' => array('nama' => -1)
    //     )
    // );
    //
    // $options = array("explain" => false);
    //
    // $results = $db->anggota_kelas->aggregate($pipeline, $options)['cursor']['firstBatch'];
    //
    // foreach ( $results as $doc ) {
    //     $doc += ["nama_user" => $doc['enrolled_info'][0]['nama']];
    //     $doc['enrolled_info'] = "-";
    //     echo $doc['_id_user']." - ".$doc['nama_user']."<br>";
    //
    //     $output[] = $doc;
    // }
    //
    // echo json_encode($output);


            // $m_collection   = $db->anggota_kelas;
            // /**
            //  * Define the document fields to return to DataTables (as in http://us.php.net/manual/en/mongocollection.find.php).
            //  * If empty, the whole document will be returned.
            //  */
            // $fields    = array();
            //
            //
            // $cursor = $m_collection->find(array('id_kelas' => '5dc11412a2f3845c348edf12'), $fields)->limit(5);
            //
            // $output = array(
            //     "sEcho" => 5,
            //     "iTotalRecords" => 5,
            //     "iTotalDisplayRecords" => 5,
            //     "aaData" => array()
            // );
            //
            // foreach ( $cursor as $doc ) {
            //
            //     $output['aaData'][] = $doc;
            //
            // }
            //
            // echo json_encode( $output );

            $m_collection   = $db->user;
            /**
             * Define the document fields to return to DataTables (as in http://us.php.net/manual/en/mongocollection.find.php).
             * If empty, the whole document will be returned.
             */
            $fields    = array();

            $skip = 1;
            $limit = 2;
            $total_limit = $skip+$limit;

            $matchs = array(
                '$match'  => array(
                    '$and' => array(
                        array("id_kelas" => '5ede400ca2f384451cdcfd29')
                    ),
                    '$or' => array(
                        array("status" => 4),
                        array("status" => "4")
                    )
                )
            );

            $pipeline = array(
                $matchs,
                array(
                    '$addFields' => array(
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
                            ),
                            array(
                                '$project' => array(
                                    'nama' => 1,
                                    'username' => 1,
                                    'status_user' => 1
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
                        '_id' => 0,
                        '_id_user' => 0,
                        'date_modified' => 0,
                        'enrolled_info' => 0
                    )
                ),
                array(
                    '$match' => array(
                        'username' => new MongoRegex("//i")
                    )
                ),
                array(
                    '$facet' => array(
                        'paginatedResults' => [array('$skip' => 0), array('$limit' => 10)],
                        'totalCount' => [array('$count' => 'count')]
                    )
                )
            );

            $options = array("explain" => false);

            $cursor = $db->anggota_kelas->aggregate($pipeline, $options)['cursor']['firstBatch'];

            $output = array(
                "sEcho" => intval($input['sEcho']),
                "iTotalRecords" => 5,
                "iTotalDisplayRecords" => 5,
                "aaData" => array()
            );

            $iTotalDisplayRecords = 0;

            $db_daftar_nilai = $db->modul_kumpul->find(array('id_modul' => '5e9c68e0a2f384e9467ba755'), array('_id' => 0, 'id_modul' => 1, 'id_user' => 1, 'nilai' => 1));
            $db_daftar_quiz  = $db->quiz->find(array('id_modul' => '5e9c68e0a2f384e9467ba755'), array('_id' => 0));
            // $db_kumpul_quiz  = $db->kumpul_quiz->find(array('id_quiz' => '5e9c68fba2f3846f487ba724'));
            // $options = array(
            //     array(
            //         '$group' => array(
            //             '_id' => '$id_quiz',
            //             'user' => array(
            //                 '$push' => '$id_user'
            //             )
            //         )
            //     ),
            //     array(
            //         '$match' => array(
            //             '_id' => '5e9c68fba2f3846f487ba724'
            //         )
            //     )
            // );
            // $cursor = $db->kumpul_quiz->aggregate($options);

            // echo json_encode( $cursor );
            //
            // die();

            $jumlah_quiz     = $db_daftar_quiz->count();

            $daftar_nilai = array();
            foreach ($db_daftar_nilai as $nilai) {
                $daftar_nilai[$nilai['id_user']][] = ['nilai_modul' => $nilai['nilai']];

                if($jumlah_quiz > 0){
                    $daftar_nilai[$nilai['id_user']][] = ['nilai_akhir' => $jumlah_quiz];
                }else{
                    $daftar_nilai[$nilai['id_user']][] = ['nilai_akhir' => 100];
                }
            }

            foreach ( $cursor[0]['paginatedResults'] as $doc ) {
                $doc += ["nilai" => $daftar_nilai[$doc['id_user']]];
                //$doc += ["nilai_modul" => $db->modul_kumpul->findOne(array('id_modul' => '5e9c68e0a2f384e9467ba755', 'id_user' =>  $doc['id_user']), array('_id' => 0, 'id_modul' => 1, 'id_user' => 1, 'nilai' => 1))];

                $output['aaData'][] = $doc;
            }

            $output['iTotalDisplayRecords'] = $iTotalDisplayRecords;

            if(empty($cursor[0]['paginatedResults'])){
                echo "kosong";
            }

            echo json_encode( $output['aaData'] );
            //echo json_encode( $cursor[0]['totalCount'][0]['count'] );
?>
