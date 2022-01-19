<?php
    require("setting/connection.php");

    // $pipeline = array(
    //     array(
    //         '$match' => array(
    //             'id_modul' => '5e9c68e0a2f384e9467ba755'
    //         )
    //     ),
    //     array(
    //         '$lookup' => array(
    //             'from' => 'soal',
    //             'let' => array('paket' => '$id_paket'),
    //             'pipeline' => array(
    //                 array(
    //                     '$match' => array(
    //                         '$expr' => array(
    //                             '$eq' => array('$id_paket','$$paket')
    //                         )
    //                     )
    //                 ),
    //                 array(
    //                     '$group' => array(
    //                         '_id' => '$id_paket',
    //                         'jumlah_soal' => array('$sum' => 1)
    //                     )
    //                 )
    //             ),
    //             'as' => 'enrolled_info'
    //         )
    //     ),
    //     array(
    //         '$replaceRoot' => array(
    //             newRoot => array(
    //                 '$mergeObjects' => array(
    //                     [
    //                         '$arrayElemAt' => ['$enrolled_info', 0]
    //                     ],
    //                     '$$ROOT'
    //                 )
    //             )
    //         )
    //     ),
    //     array(
    //         '$project' => array(
    //             'enrolled_info' => 0
    //         )
    //     ),
    // );
    //
    // $options = array("explain" => false);
    //
    // $results = $db->quiz->aggregate($pipeline, $options)['cursor']['firstBatch'];
    //
    // echo sizeof($results)."<br>";
    //
    // foreach ($results as $value) {
    //     echo $value['_id']." - ".$value['jumlah_soal']."<br>";
    // }

    $results = $db->quiz->find(array('id_modul' => '5e9c68e0a2f384e9467ba755'));

    foreach ($results as $value) {
        $jumlah_soal = $db->soal->find(array('id_paket' => $value['id_paket']))->count();
        echo $value['_id']." - ".$jumlah_soal."<br>";
    }
?>
