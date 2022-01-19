<?php
    require("setting/connection.php");

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

            $pipeline = array(
                array(
                    '$match' => array(
                        'id_kelas' => '5b059427a2f3847a70db9274'
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
                                    ),
                                    'nama' => new MongoRegex("//i")
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
                    '$project' => array(
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

            // $output = array(
            //     "sEcho" => intval($input['sEcho']),
            //     "iTotalRecords" => 5,
            //     "iTotalDisplayRecords" => 5,
            //     "aaData" => array()
            // );
            //
            // $iTotalDisplayRecords = 0;
            //
            // foreach ( $cursor as $doc ) {
            //     $doc += ["nama_user" => $doc['enrolled_info'][0]['nama']];
            //     $doc['enrolled_info'] = "-";
            //
            //     if(!empty($doc['nama_user'])){
            //         $output['aaData'][] = $doc;
            //         $iTotalDisplayRecords++;
            //     }
            // }
            //
            // $output['iTotalDisplayRecords'] = $iTotalDisplayRecords;

            echo json_encode( $cursor[0]['paginatedResults'][1] );
            //echo json_encode( $cursor[0]['totalCount'][0]['count'] );
?>
