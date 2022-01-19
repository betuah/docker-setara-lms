<?php
require("../../setting/connection.php");
require("../../setting/connection-log.php");
require("../../setting/connection-MSSQL.php");

session_start();

$method	= $_REQUEST;
$table  = $db->sekolah_induk;

if(isset($method['action'])){

    if($method['action'] == 'verification'){
        $NPSN   = $method['npsn'];
        // $stmt   = $DB_Conn->prepare("
        //                             SELECT TOP 1
        //                                 sekolah_id, npsn, nama, email, nomor_telepon, kabupaten, provinsi
        //                             FROM skl.sekolah
        //                             WHERE npsn = :npsn");
        $stmt   = $DB_Conn->prepare("
                                    SELECT
                                        sk.sekolah_id, sk.npsn, sk.nama as 'satuan_pendidikan', sk.provinsi, sk.kabupaten, sk.email, sk.nomor_telepon, COUNT(rbs.rombongan_belajar_id) as 'jumlah_rombel'
                                    FROM skl.sekolah as sk
                                        LEFT JOIN rb.rombongan_belajar as rbs ON rbs.sekolah_id = sk.sekolah_id AND rbs.jenis_rombel = 'Kelas'
                                    WHERE sk.npsn = :npsn
                                    GROUP BY sk.sekolah_id, sk.npsn, sk.nama, sk.email, sk.nomor_telepon, sk.kabupaten, sk.provinsi");
                $stmt->execute(array(":npsn" => $NPSN));
        $data   = $stmt->fetch(PDO::FETCH_ASSOC);
        $data_setara   = $table->findOne(array("npsn" => $NPSN));

        if ($data) {
            if ($data['jumlah_rombel'] > 0) {
                if ($data_setara) {
                    if ($data_setara['aktivasi'] == 'Belum' || is_null($data_setara['aktivasi'])) {
                        // -----> Update status Sekolah yang Belum Aktivasi
                        // -----> Serta Kirim email ke Satuan Pendidikan terkait Akun Pengelola
                        try {
                            if($table->update(array("_id" => $data_setara['_id']), array('$set' => array('aktivasi'=>'Sudah', "date_modified"=>date('Y-m-d H:i:s'))))){
                                $options    = ['cost' => 12];
                                $pass       = password_hash ( $data['npsn'], PASSWORD_BCRYPT, $options );
                                $query      = array(
                                                    "username"  => "pengelola_".strtolower($data['npsn']),
                                                    "password"  => $pass,
                                                    "nama"      => "Pengelola ".$data['satuan_pendidikan'],
                                                    "email"     => $data['email'],
                                                    "jk"        => '',
                                                    "sekolah"   => $data['npsn'].": ".$data['satuan_pendidikan'],
                                                    "mengawasi" => "$data_setara[_id]",
                                                    "status"    => 'pengelola',
                                                    "sosmed"    => array('facebook' => '', 'website' => '','linkedin' => '','twitter' => '' ),
                                                    "update"    => 0,
                                                    "foto"      => '',
                                                    "program"   => '',
                                                    "date_created"  => date("d-M-Y H:i:s"),
                                                    "date_modified" => ''
                                            );
                                $add_pengelola = $db->user->insert($query);
                                
                                $resp   = array(
                                                'response'  =>'Pemberitahuan!',
                                                'message'   =>'Satuan Pendidikan berhasil di aktivasi, Periksa Email Satuan Pendidikan yang didaftarkan dalam DAPODIK untuk melihat akun Pengelola.',
                                                'icon'      =>'success'
                                            );
                            }else {
                                $resp   = array(
                                    'response'  =>'Pemberitahuan!',
                                    'message'   =>'Satuan Pendidikan belum berhasil di aktivasi. Anda dapat mengulang proses ini beberapa saat lagi.',
                                    'icon'      =>'error'
                                );
                            }

                            
                        } catch(MongoCursorException $e) {
                            $resp   = array(
                                            'response'  =>'Pemberitahuan!',
                                            'message'   =>'Satuan Pendidikan belum berhasil di aktivasi. Anda dapat mengulang proses ini beberapa saat lagi.',
                                            'icon'      =>'error'
                                        );
                        }
                        // $resp   = array('response'=>'Pemberitahuan!', 'message'=>$data_setara, 'icon'=>'success');
                    }else {
                        $resp   = array('response'=>'Pemberitahuan!', 'message'=>'Satuan Pendidikan telah di aktivasi sebelumnya, silahkan Periksa Email Satuan Pendidikan yang terdaftar dalam DAPODIK!', 'icon'=>'warning');
                    }
                }else{
                    // -----> Tambahkan Satuan Pendidikan yang Belum Aktivasi
                    // -----> Serta Kirim email ke Satuan Pendidikan terkait Akun Pengelola
                    $provinsi   =   explode('Prov. ', $data['provinsi']);

                    $insert = array(
                                    "npsn" => $data['npsn'],
                                    "nama_sekolah_induk"    => $data['satuan_pendidikan'],
                                    "program"       => 'Pendidikan Kesetaraan',
                                    "kab_kot"       => $data['kabupaten'],
                                    "kab_kota"      => $data['kabupaten'],
                                    "provinsi"      => $provinsi[1],
                                    "nama_operator" => '',
                                    "nomor_telepon" => $data['nomor_telepon'],
                                    "aktivasi"      => 'Sudah',
                                    "date_created"  => date('Y-m-d H:i:s'),
                                    "date_modified" => date('Y-m-d H:i:s')
                                );

                    $table->insert($insert);
                    $newID  = $insert['_id'];

                    if ($newID) {
                        $options    = ['cost' => 12];
                        $pass       = password_hash ( $data['npsn'], PASSWORD_BCRYPT, $options );
                        $query      = array(
                                            "username"  => "pengelola_".strtolower($data['npsn']),
                                            "password"  => $pass,
                                            "nama"      => "Pengelola ".$data['satuan_pendidikan'],
                                            "email"     => $data['email'],
                                            "jk"        => '',
                                            "sekolah"   => $data['npsn'].": ".$data['satuan_pendidikan'],
                                            "mengawasi" => "$newID",
                                            "status"    => 'pengelola',
                                            "sosmed"    => array('facebook' => '', 'website' => '','linkedin' => '','twitter' => '' ),
                                            "update"    => 0,
                                            "foto"      => '',
                                            "program"   => '',
                                            "date_created"  => date("d-M-Y H:i:s"),
                                            "date_modified" => ''
                                    );
                        $add_pengelola = $db->user->insert($query);

                        $resp   = array(
                            'response'  =>'Pemberitahuan!',
                            'message'   =>'Satuan Pendidikan berhasil di aktivasi, Periksa Email Satuan Pendidikan yang didaftarkan dalam DAPODIK untuk melihat akun Pengelola.',
                            'icon'      =>'success'
                        );
                    }else {
                        $resp   = array(
                            'response'  =>'Pemberitahuan!',
                            'message'   =>'Satuan Pendidikan belum berhasil di aktivasi. Anda dapat mengulang proses ini beberapa saat lagi.',
                            'icon'      =>'error'
                        );
                    }
                    // $resp   = array('response'=>'Pemberitahuan!', 'message'=>$provinsi[1], 'icon'=>'success');
                }
            } else {
                if ($data_setara) {
                    $resp   = array('response'=>'Pemberitahuan!', 'message'=>'Satuan Pendidikan dengan NPSN '.$NPSN.', sudah terdaftar di seTARA Daring!', 'icon'=>'info');
                }else {
                    $provinsi   =   explode('Prov. ', $data['provinsi']);

                    $insert = array(
                                    "npsn" => $data['npsn'],
                                    "nama_sekolah_induk"    => $data['satuan_pendidikan'],
                                    "program"       => 'Pendidikan Kesetaraan',
                                    "kab_kot"       => $data['kabupaten'],
                                    "kab_kota"      => $data['kabupaten'],
                                    "provinsi"      => $provinsi[1],
                                    "nama_operator" => '',
                                    "nomor_telepon" => $data['nomor_telepon'],
                                    "aktivasi"      => 'Belum',
                                    "date_created"  => date('Y-m-d H:i:s'),
                                    "date_modified" => date('Y-m-d H:i:s')
                                );

                    $table->insert($insert);
                    $newID  = $insert['_id'];
                    
                    if ($newID) {
                        $resp   = array(
                            'response'  =>'Pemberitahuan!',
                            'message'   =>'Pengajuan Satuan Pendidikan Anda berhasil ditambahkan!',
                            'icon'      =>'success'
                        );
                    }else {
                        $resp   = array(
                            'response'  =>'Pemberitahuan!',
                            'message'   =>'Pengajuan Satuan Pendidikan Anda belum berhasil. Anda dapat mengulang proses ini beberapa saat lagi.',
                            'icon'      =>'error'
                        );
                    }
                }
            }
        }else{
            $resp   = array('response'=>'Pemberitahuan!', 'message'=>'Tidak ada Satuan Pendidikan dengan NPSN tersebut!', 'icon'=>'error');
        }

        // $resp   = array('data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

}

?>
