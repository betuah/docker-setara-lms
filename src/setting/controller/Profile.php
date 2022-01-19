<?php
/** Rifai
//Rifai Lagi
*/
class Profile
{
	public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'user';
            $this->dbLog   = $dbLog;
            $this->table = $db -> $tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

	public function GetData($id_profile) {
        $criteria = array('_id' => new MongoId($id_profile));
		$getprofile = $this -> table -> findOne($criteria);

		return $getprofile;
	}

    public function UpdateSosmed($id_profile, $sekolah, $website,$facebook,$linkedin,$twitter){
        global $db;

        $update = array("sosmed.website"=>$website,"sosmed.facebook"=>$facebook, "sosmed.linkedin"=>$linkedin, "sosmed.twitter"=>$twitter, "date_modified"=>date('Y-m-d H:i:s'));
        $sukses = $this -> table -> update(array("_id"=> new MongoId($id_profile)),array('$set'=>$update));
        if ($sukses) {
            # code...
            //------ Menulis LOG ---------
            $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$id_profile", "id_sekolah"=>$sekolah, "id_kelas"=>"", "aksi"=>"4", "id_data"=>"$id_profile", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

            echo "<script type='text/javascript'> swal({
                                  title: 'Berhasil diperbarui!',
                                  text: 'Profil anda berhasil diperbarui',
                                  type: 'success',
                                  timer: 2000
							  	}, function () {
                                    location.href='setting-profile';
								});
                </script>";
        }else{
            echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Profil anda gagal diperbarui',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
        }

    }

    public function UpdateProfile($id_profile, $password,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto,$prov,$kota){
        global $db;

        $update = array("username"=>$username,"password"=>$password, "nama"=>$nama,  "email"=>$email, "jk"=>$jenis_kelamin, "sekolah"=>$sekolah, "status"=>$status, "foto"=>$foto,"date_modified"=>date('Y-m-d H:i:s'),"provinsi"=>$prov,"kota"=>$kota);
        $sukses = $this -> table -> update(array("_id"=> new MongoId($id_profile)),array('$set'=>$update));
        if ($sukses) {
            # code...
            //------ Menulis LOG ---------
              $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$id_profile", "id_sekolah"=>$sekolah, "id_kelas"=>"", "aksi"=>"4", "id_data"=>"$id_profile", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

          $_SESSION['lms_name'] = $nama;
            echo "<script type='text/javascript'> swal({
                                  title: 'Berhasil diperbarui!',
                                  text: 'Profil anda berhasil diperbarui',
                                  type: 'success',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
        }else{
            echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Profil anda gagal diperbarui',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
        }

    }

    public function CheckPassword($id_profile, $password, $password_baru, $pasword_confirm,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto){
        global $db;
        $getusers = $this -> table -> findOne(['_id' => $id_profile]);
        if (!is_null($getusers)) {
            $hash = $getusers['password'];
            // echo $hash;
            if(password_verify($password,$hash)){
                 if ($password_baru == $pasword_confirm) {
                     # code...

                    $options = [
                        'cost' => 12,
                    ];
                    $pass = password_hash ( $password_baru , PASSWORD_BCRYPT, $options );

                    $update = array("username"=>$username,"password"=>$pass, "nama"=>$nama,  "email"=>$email, "jk"=>$jenis_kelamin, "sekolah"=>$sekolah, "status"=>$status, "foto"=>$foto);
                    // echo "update = array(username=>$username,password=>$pass, 'nama'=>$nama,  'email'=>$email, 'jk'=>$jenis_kelamin, 'sekolah'=>$sekolah, 'status'=>$status, 'foto'=>$foto)";
                    $sukses=$this -> table -> update(array("_id"=> $id_profile),array('$set'=>$update));
                    if ($sukses) {
                        # code...
                      $_SESSION['lms_name'] = $nama;
                        echo "<script type='text/javascript'> swal({
                                  title: 'Berhasil diperbarui!',
                                  text: 'Kata Sandi anda berhasil diperbarui',
                                  type: 'success',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                            </script>";
                    }else{
                        echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Kata Sandi anda gagal diperbarui',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                            </script>";
                    }

                 }else{
                    echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Kata sandi baru yang anda masukan tidak sama.',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
                 }
            }else{
                echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Kata sandi anda tidak sesuai.',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
            }
        }else{
            echo '<script type="text/javascript"> alert("Username Anda Tidak ditemukan")</script>';
        }
    }



    public function UpdateProfileFoto($id_profile, $password,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto,$foto_size,$foto_tmp,$foto_ext,$foto_lama,$prov,$kota){


            $format = array("jpg", "jpeg", "png", "gif", "bmp");


            if(in_array(strtolower($foto_ext), $format))
            {
                $foto_name    = substr(md5(time()), 0, 9).'_'.date('dmYHIs').".".$foto_ext;
                // $folderRoot   ='http://sumberbelajar.seamolec.org/Assets/foto/';
                // $folderDest   ='http://sumberbelajar.seamolec.org/Assets/foto/'.$foto_name;
                $folderRoot   ='media/Assets/foto/';
                $folderDest   ='media/Assets/foto/'.$foto_name;

                // echo "move_uploaded_file($foto_tmp, $folderDest)";
                if ($foto_size < 240000) {
                  # code...
				            chmod($folderRoot, 0777);

                    if(move_uploaded_file($foto_tmp, $folderDest))
                    {
                        // mengganti File Permission
                        $lama = $folderRoot.$foto_lama;
                        if (file_exists($lama)) {
                          if ($foto_lama != 'no_picture.png') {
                            # code...
                            chmod($folderRoot, 0777);
                            unlink($lama);
                          }
                        }
                        chmod($folderRoot, 0744);

                        // Update Data

                        $update = array("username"=>$username,"password"=>$password, "nama"=>$nama,  "email"=>$email, "jk"=>$jenis_kelamin, "sekolah"=>$sekolah, "status"=>$status, "foto"=>$foto_name,"provinsi"=>$prov,"kota"=>$kota);
                        $sukses = $this -> table -> update(array("_id"=> new MongoId($id_profile)),array('$set'=>$update));

                        if ($sukses) {
                            # code...
                            echo "<script type='text/javascript'>
								swal({
                                      title: 'Berhasil diperbarui!',
                                      text: 'Profil anda berhasil diperbarui',
                                      type: 'success',
                                      timer: 2000
                                    }, function () {
	                                    location.href='setting-profile';
									});
                                </script>";
                        }
                        else
                        {
                            echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diperbarui!',
                                  text: 'Profil anda gagal diperbarui',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
                        }

                    }
                    else
                    {
                        echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diunggah!',
                                  text: 'Foto profil anda gagal diunggah cek kembali ukuran file dan koneksi internet anda!',
                                  type: 'error'

                                }, function () {

								});
                </script>";
                    }
                  }else{
                    echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diunggah!',
                                  text: 'Foto profil anda gagal diunggah maksimal ukuran 200KB !$foto_tmp',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
                  }
            }
            else
            {
                echo "<script type='text/javascript'> swal({
                                  title: 'Gagal diunggah!',
                                  text: 'Jenis File tidak didukung!',
                                  type: 'error',
                                  timer: 2000
                                }, function () {
                                    location.href='setting-profile';
								});
                </script>";
            }

    }

    public function updateInfo($sekolah, $email, $user){
        global $db;

        $update = array("sekolah"=>$sekolah, "email"=>$email, "update"=> 1, "date_modified"=>date('Y-m-d H:i:s'));
        $sukses = $this -> table -> update(array("_id"=> new MongoId($user)),array('$set'=>$update));
        if ($sukses) {
            # code...
            //------ Menulis LOG ---------
              $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$sekolah, "id_kelas"=>"", "aksi"=>"4", "id_data"=>"$user", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

            $_SESSION['lms_update'] = 1;
            $status     = "Success";
            $message    = "Data anda berhasil diperbarui!";
        }else{
            $status     = "Failed";
            $message    = "Data anda gagal diperbarui!";
        }

        $result = array("status" => $status, "message"=>$message);
        return $result;

    }

    public function updateInfoSekolah($sekolah, $user){
        global $db;

        $update = array("sekolah"=>$sekolah, "update"=> "2021.1", "date_modified"=>date('Y-m-d H:i:s'));
        $sukses = $this -> table -> update(array("_id"=> new MongoId($user)),array('$set'=>$update));
        if ($sukses) {
            # code...
            //------ Menulis LOG ---------
              $log    = $this->dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$user", "id_sekolah"=>$sekolah, "id_kelas"=>"", "aksi"=>"4", "id_data"=>"$user", "data"=>$update, "date_created"=>date('Y-m-d H:i:s')));

            $_SESSION['lms_update']  = "2021.1";
            $_SESSION['lms_sekolah'] = $sekolah;
            
            $status     = "Success";
            $message    = "Data anda berhasil diperbarui!";
        }else{
            $status     = "Failed";
            $message    = "Data anda gagal diperbarui!";
        }

        $result = array("status" => $status, "message"=>$message);
        return $result;

    }

    function selisih_waktu($timestamp){
        $selisih = time() - strtotime($timestamp) ;
        $detik  = $selisih ;
        $menit  = round($selisih / 60 );
        $jam    = round($selisih / 3600 );
        $hari   = round($selisih / 86400 );
        $minggu = round($selisih / 604800 );
        $bulan  = round($selisih / 2419200 );
        $tahun  = round($selisih / 29030400 );
        if ($detik <= 60) {
            $waktu = $detik.' detik yang lalu';
        } else if  ($menit <= 60) {
            $waktu = $menit.' menit yang lalu';
        } else if ($jam <= 24) {
            $waktu = $jam.' jam yang lalu';
        } else if ($hari <= 7) {
            $waktu = $hari.' hari yang lalu';
        } else if ($minggu <= 4) {
            $waktu = $minggu.' minggu yang lalu';
        } else if ($bulan <= 12) {
            $waktu = $bulan.' bulan yang lalu';
        } else {
            $waktu = $tahun.' tahun yang lalu';
        }
        return $waktu;
    }


}


?>
