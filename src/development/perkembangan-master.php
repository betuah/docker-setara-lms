<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$kelasClass 	= new Kelas();
$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$materiClass 	= new Materi();
$tugasClass 	= new Tugas();
$quizClass 	    = new Quiz();
$userClass 	    = new User();

$menuMapel      = 3;
$infoMapel		= $mapelClass->getInfoMapel($_GET['id']);
$listModul		= $modulClass->getListbyMapel($_GET['id']);
$infoKelas		= $kelasClass->getInfoKelas($infoMapel['id_kelas']);

$hakKelas		= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
if(!$hakKelas['status']){
	echo "<script>
			swal({
				title: 'Maaf!',
				text: 'Anda tidak terdaftar pada Kelas / Kelas tidak tsb tidak ada.',
				type: 'error'
			}, function() {
				 window.location = 'index.php';
			});
		</script>";
		die();
}

if(isset($_POST['updateMapel'])){
	if ($hakKelas['status'] == 1 || $hakKelas['status'] == 2) {
		$nama	= mysql_escape_string($_POST['namaMapelupdate']);
		$rest	= $mapelClass->updateMapel("$infoKelas[_id]", $nama, $_GET['id']);

		echo	"<script>
					swal({
						title: '$rest[judul]',
						text: '$rest[message]',
						type: '$rest[status]'
					}, function() {
						 window.location = 'perkembangan.php?id=$rest[IDMapel]';
					});
				</script>";
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam merubah Pengaturan kelas.',
						type: 'error'
					}, function() {
						 window.location = 'index.php';
					});
				</script>";
	}
}

$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Perkembangan Siswa Mata Pelajaran <?=$infoMapel['nama']?> - seTARA Daring";</script>
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">

    <div class="modal fade"
         id="updateMapel"
         tabindex="-1"
         role="dialog"
         aria-labelledby="updateMapelLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST">
                <div class="modal-header">
                    <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="font-icon-close-2"></i>
                    </button>
                    <h4 class="modal-title" id="updateMapelLabel">Pengaturan Mata Pelajaran</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="namaMapelupdate" class="col-md-3 form-control-label">Mata Pelajaran</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" name="idMapelupdate" id="idMapelupdate"  />
                            <input type="text" class="form-control" name="namaMapelupdate" id="namaMapelupdate" placeholder="Nama Mata Pelajaran" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-danger pull-left" onclick="removeMapel('<?=$infoMapel['_id']?>')" name="hapusMapel"><i class="font-icon-trash"></i> Hapus Mata Pelajaran</button>
                    <button type="submit" class="btn btn-rounded btn-primary" name="updateMapel" value="send" >Simpan</button>
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
                </div>
                </form>
            </div>
        </div>
    </div><!--.modal-->

	<div class="page-content">
		<div class="profile-header-photo" style="background-image: url('assets/img/Artboard 1.png');">
			<div class="profile-header-photo-in">
				<div class="tbl-cell">
					<div class="info-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<div class="tbl info-tbl">
										<div class="tbl-row">
											<div class="tbl-cell">
												<p><i class="font-icon font-icon-user"></i>&nbsp;&nbsp;<small><?=($hakKelas['posisi'] == 'Guru Mata Pelajaran'?'Tutor (Pengampu Mata Pelajaran)':($hakKelas['posisi'] == 'Tutor'?'Tutor (Pendamping)':$hakKelas['posisi']))?></small></p>
												<br><br><br><br><br>
                                                <p class="title"><?=$_SESSION['lms_name']?></p>
												<p class="title">Mata Pelajaran <?=$infoMapel['nama']?></p>
												<p><?=$infoKelas['nama']?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			if ($_SESSION['lms_id'] == $infoMapel['creator']) {
			?>
			<button type="button" class="change-cover" onclick="update()">
				<i class="font-icon font-icon-pencil"></i>
				Pengaturan Mata Pelajaran
			</button>
			<?php
			}
			?>

		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
                <div class="col-xl-3 col-lg-4">
					<?php
						require("includes/mapel-menu.php");
					?>
				</div>
				<div class="col-xl-9 col-lg-8">
					<section class="card card-default">
						<div class="card-block">
                            <h5 class="with-border"><b>Perkembangan Akademik / <a href=""><?=$infoKelas['nama']?></a></b></h5>
                            <div class="col-md-12">
                                <h6>Pilah Berdasarkan : </h6>
                                <form id="form_tambah" method="POST">
                                    <div class="row">
                    					<div class="col-md-6 col-sm-6">
                    						<fieldset class="form-group">
                    							<label class="form-control-label" for="modulFilter">Kegiatan Belajar</label>
                                                <select class="form-control" name="modulFilter" id="modulFilter" required>
                                                <?php

                                                    $jmlhModul = $listModul->count();
                                                    if ($jmlhModul > 0) {
                                                        echo "<option value=''>-- Pilih Kegiatan Belajar --</option>";
                                                        foreach ($listModul as $data) {
                                                            echo "<option value='$data[_id]'>$data[nama]</option>";
                                                        }
                                                    }else {
                                                        echo "<option value=''>-- Belum Tersedia --</option>";
                                                    }
                                                ?>
                                                </select>
                    						</fieldset>
                    					</div>
                    					<div class="col-md-6 col-sm-6">
                                        <?php
                                            if($_SESSION['lms_status'] == 'guru'){
                                        ?>
                    						<fieldset class="form-group">
                                                <label class="form-control-label" for="tkbFilter">Kelompok Belajar</label>
                                                <select class="form-control" name="tkbFilter" id="tkbFilter">
                                                <?php
                                                    $jmlhTKB = explode(',', $infoKelas['tkb']);
                                                    sort($jmlhTKB);
                                                    if ($jmlhTKB > 0) {
                                                            echo "<option value='0'>-- Semua Kelompok Belajar --</option>";
                                                        foreach ($jmlhTKB as $data) {
                                                            echo "<option value='$data'>$data</option>";
                                                        }
                                                    }else {
                                                        echo "<option value=''>Tidak ada Kelompok Belajar</option>";
                                                    }
                                                ?>
                                                </select>
                    						</fieldset>
                                        <?php
                                            }
                                        ?>
                    					</div>
                    				</div><!--.row-->
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <button type="submit" name="filterData" class="btn pull-right">Tampilkan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
				</div>
			</div><!--.row-->

            <?php
            if (isset($_POST['filterData'])) {
                $idmodul    = $_POST['modulFilter'];
                $idtkb      = isset($_POST['tkbFilter']) ? $_POST['tkbFilter'] : 0;
                //$cari       =
                $no         = 0;
                $siswa      = array();

                // ----> Cek Tugas <---- //
                $infoModul  = $modulClass->getInfoModul($idmodul);

                // ----> Cek Tugas <---- //
                $infoTugas  = $tugasClass->getListTugas($idmodul);
                $jmlhTugas  = $infoTugas->count();

                // ----> Cek Ujian <---- //
                $listUjian  = $quizClass->getListbyModul($idmodul);
                $jmlhUjian  = $listUjian->count();
                foreach ($listUjian as $dataUjian) {
                    $ujian[] = $dataUjian;
                }
				$clspn	= $jmlhTugas > 0 ? ($jmlhUjian > 0 ? (1+$jmlhTugas+$jmlhUjian) : (2+$jmlhTugas)) : ($jmlhUjian > 0 ? (2+$jmlhUjian) : '3');
                $table = '<div class="row">
                            <div class="col-md-12">
                                <section class="card card-default">
                                    <div class="card-block">
                                        <h5 class="with-border"><b>Perkembangan Siswa / <a href="kelas.php?id='.$infoKelas['_id'].'">'.$infoKelas['nama'].'</a> / <a href="modul.php?modul='.$infoModul['_id'].'">'.$infoModul['nama'].'</a></b></h5>
                                        <div class="col-md-12 p-y">
                                            <table id="perkembangan" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th rowspan="3" class="text-center">Nama Siswa</th>
                                                    <th rowspan="3" class="text-center">Sekolah/Instansi</th>
                                                    <th rowspan="3" class="text-center">Kelompok<br>Belajar</th>
                                                    <th rowspan="3" class="text-center">Nilai Akhir</th>
                                                    <th colspan="'.$clspn.'" class="text-center">'.$infoModul['nama'].'</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" class="text-center">Nilai Membaca Materi</th>
                                                    <th colspan="'.$jmlhTugas.'" class="text-center">Nilai Tugas</th>
                                                    <th colspan="'.$jmlhUjian.'" class="text-center">Nilai Evaluasi</th>
                                                </tr>
                                                <tr>';
                            if ($jmlhTugas > 0) {
                                foreach ($infoTugas as $value) {
                                    $table  .= '    <th class="text-center" style="width:100px;">'.$value['nama'].'</th>';
                                }
                            }else{
								$table  .= '<th class="text-center">Tidak Ada</th>';
							}

                             if ($jmlhUjian > 0) {
                                foreach ($ujian as $valueUjian) {
                                    $table  .= '    <th class="text-center" style="width:100px;">'.$valueUjian['nama'].'</th>';
                                }
                            }else{
                                $table  .= '<th class="text-center">Belum Ada</th>';
                            }



                // $table  .= '                        <th class="text-center">Nilai Evaluasi <br> '.@$ujian[0]['nama'].'</th>
                //                                 </tr>
                //                             </thead>
                //                             <tbody>';
                $table  .= '                                    </tr>
                                                             </thead>
                                                           <tbody>';

                // Siswa melihat Perkembangannya sendiri....
                if ($_SESSION['lms_status'] == 'siswa') {
                    $nilaiMateri= 0;
                    $nilaiTugas = 0;
                    $nilaiUjian = 0;
                    $anggota        = $kelasClass->getKeanggotaan($infoMapel['id_kelas'], "$_SESSION[lms_id]");

                    if ($anggota['status'] == '4') {
                        $siswa[]        = $userClass->GetData("$_SESSION[lms_id]");
                        // $siswa[$no]['sekolah']   = @$anggota['tkb'];
                        $siswa[$no]['tkb']   = @$anggota['tkb'];

                        // --- Nilai Membaca Materi
                        $CekNilaiMateri = $modulClass->getStatusMateri($idmodul, "$_SESSION[lms_id]");
                        $nilaiMateri    = $nilaiMateri + $CekNilaiMateri['nilai'];
                        $siswa[$no]['nilai']['modul'] = $nilaiMateri;

                        // --- Melihat nilai tugas berdasarkan jumlah tugas yang tersedia.
                        $kumpulTugas = 0;
                        if ($jmlhTugas > 0) {
                            $siswa[$no]['nilai']['totalTugas'] = 0;
                            foreach ($infoTugas as $tugas) {
                                $nilaiTugas     = 0;
                                $cekNilaiTugas  = $tugasClass->getStatusTugas($tugas['_id'], "$_SESSION[lms_id]");
                                $nilaiTugas     = $nilaiTugas + $cekNilaiTugas['nilai'];
                                $siswa[$no]['nilai']['tugas'][$kumpulTugas]['id']   = $tugas['_id'];
                                $siswa[$no]['nilai']['tugas'][$kumpulTugas]['nama'] = $tugas['nama'];
                                $siswa[$no]['nilai']['tugas'][$kumpulTugas]['nilai']= $nilaiTugas;
                                $siswa[$no]['nilai']['totalTugas'] += $nilaiTugas;
                                $kumpulTugas++;
                            }
                            $totalTugas = round(($siswa[$no]['nilai']['totalTugas']/$jmlhTugas), 2);

                            // --- Nilai Ujian
                            if ($jmlhUjian > 0) {
                                $cekNilaiUjian  = $tugasClass->getStatusQuiz($ujian[0]['_id'], "$_SESSION[lms_id]");
                                $nilaiUjian     = $nilaiUjian + $cekNilaiUjian['nilai'];
                                $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                            }else {
                                $nilaiUjian     = 0;
                                $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                            }
                        }else {
                            // --- Nilai Tugas
                            $totalTugas = 100;

                            // --- Nilai Ujian
                            if ($jmlhUjian > 0) {
                                $cekNilaiUjian      = $tugasClass->getStatusQuiz($ujian[0]['_id'], "$_SESSION[lms_id]");
                                $nilaiUjian         = $nilaiUjian + $cekNilaiUjian['nilai'];
                                $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                            }else {
                                $nilaiUjian     = 0;
                                $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                            }
                        }

                        // Kriteria Penilaian (Persentase) yg diambil dari Pengaturan Modul.
                        $persentaseModul = $infoModul['nilai']['materi'];
                        $persentaseTugas = $infoModul['nilai']['tugas'];
                        $persentaseUjian = $infoModul['nilai']['ujian'];
                        $nilaiMinimal    = $infoModul['nilai']['minimal'];

                        // Penghitungan nilai sesuai persentase yang ada pada tiap Modul.
                        $nilaiAkhirMateri   = $persentaseModul == 0 ? 0 : round($nilaiMateri * ($persentaseModul/100), 2);
                        $nilaiAkhirTugas    = $persentaseTugas == 0 ? 0 : round($totalTugas * ($persentaseTugas/100), 2);
                        $nilaiAkhirUjian    = $persentaseUjian == 0 ? 0 : round($nilaiUjian * ($persentaseUjian/100), 2);

                        $hasil  = round($nilaiAkhirMateri + $nilaiAkhirTugas + $nilaiAkhirUjian, 2);

                        $table  .= '    <tr>
                                            <td class="text-center">'.$siswa[$no]['nama'].'</td>
                                            <td class="text-center">'.$siswa[$no]['sekolah'].'</td>
                                            <td class="text-center">'.$siswa[$no]['tkb'].'</td>
                                            <td class="text-center"><h4 class="no-margin"><b><u>'.$hasil.'</u></b></h4></td>
                                            <td class="text-center">'.$nilaiMateri.'</td>';
                        if ($jmlhTugas > 0) {
                            $t=1;
                            foreach ($siswa[$no]['nilai']['tugas'] as $tableTugas) {
                                $table  .= '<td class="text-center">'.$tableTugas['nilai'].'</td>';
                                $t++;
                            }
                        }else{
							$table  .= '<td class="text-center">'.$totalTugas.'</td>';
						}

                        $table  .= '        <td class="text-center">'.$nilaiUjian.'</td>
                                        </tr>';

                        $no++;
                    }


                    $table  .= '                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div><!--.row-->';

                // Guru melihat Perkembangannya siswa/i nya....
                }else{
                    // ----> Anggota Kelas <---- //
					$no_list	= 0;
                    $listA      = $infoKelas['list_member'];
                    foreach ($listA as $dataA) {
						// if($no_list > 100){
						// 	break;
						// }else{
						// 	$no_list++;
						// }

						$no_list++;
                        $nilaiMateri= 0;
                        $nilaiTugas = 0;
                        $nilaiUjian = 0;
                        $anggota        = $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $dataA);

                        if ($anggota['status'] == '4') {
                            if ($idtkb == '0') {

                                $siswa[]        = $userClass->GetData($dataA);
                                $siswa[$no]['tkb']   = @$anggota['tkb'];

                                // --- Nilai Membaca Materi
                                $CekNilaiMateri = $modulClass->getStatusMateri($idmodul, $dataA);
                                $nilaiMateri    = $nilaiMateri + $CekNilaiMateri['nilai'];
                                $siswa[$no]['nilai']['modul'] = $nilaiMateri;

                                // --- Nilai Tugas
                                $kumpulTugas = 0;
                                if ($jmlhTugas > 0) {
                                    $siswa[$no]['nilai']['totalTugas'] = 0;
                                    foreach ($infoTugas as $tugas) {
                                        $nilaiTugas     = 0;
                                        $cekNilaiTugas  = $tugasClass->getStatusTugas($tugas['_id'], $dataA);
                                        $nilaiTugas     = $nilaiTugas + $cekNilaiTugas['nilai'];
                                        $siswa[$no]['nilai']['tugas'][$kumpulTugas]['id']   = $tugas['_id'];
                                        $siswa[$no]['nilai']['tugas'][$kumpulTugas]['nama'] = $tugas['nama'];
                                        $siswa[$no]['nilai']['tugas'][$kumpulTugas]['nilai']= $nilaiTugas;
                                        $siswa[$no]['nilai']['totalTugas'] += $nilaiTugas;
                                        $kumpulTugas++;
                                    }
                                    $totalTugas = round(($siswa[$no]['nilai']['totalTugas']/$jmlhTugas), 2);

                                    // --- Nilai Ujian
                                    $kumpulUjian = 0;
                                    if ($jmlhUjian > 0) {
                                        $siswa[$no]['nilai']['totalUjian'] = 0;
                                        foreach ($ujian as $valueUjian) {
                                            $nilaiUjian  = 0;
                                            $cekNilaiUjian  	= $tugasClass->getStatusQuiz($valueUjian['_id'], $dataA);
											//$cekNilaiUjianNew  	= $tugasClass->getStatusQuizNew($valueUjian['_id'], $valueUjian['id_paket'], $dataA);

											$nilaiUjian     = $nilaiUjian + @$cekNilaiUjian['nilai'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['id']   = $valueUjian['_id'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['nama'] = $valueUjian['nama'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['nilai']= $nilaiUjian;
                                            $siswa[$no]['nilai']['totalUjian'] += $nilaiUjian;

                                            //  echo "Siswa : $dataA <br>
                                            //         Nilai : $nilaiUjian <br>
                                            //         total : ".$siswa[$no]['nilai']['totalUjian'].'<br>';
                                            // print_r($cekNilaiUjian);
                                            // echo "</pre>";

                                            $kumpulUjian++;
                                            // $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                                        }
                                        $totalUjian = round(($siswa[$no]['nilai']['totalUjian']/$jmlhUjian), 2);
                                    }else {
                                        $totalUjian = 0;
                                    }
                                }else {
                                    // --- Nilai Tugas
                                    $totalTugas = 100;

                                    // --- Nilai Ujian
                                    $kumpulUjian = 0;
                                    if ($jmlhUjian > 0) {
                                        $siswa[$no]['nilai']['totalUjian'] = 0;
                                        foreach ($ujian as $valueUjian) {
                                            $nilaiUjian  = 0;
                                            $cekNilaiUjian  = $tugasClass->getStatusQuiz($valueUjian['_id'], $dataA);
											//$cekNilaiUjian  = $tugasClass->getStatusQuizNew($valueUjian['_id'], $valueUjian['id_paket'], $dataA);
											$nilaiUjian     = $nilaiUjian + $cekNilaiUjian['nilai'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['id']   = $valueUjian['_id'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['nama'] = $valueUjian['nama'];
                                            $siswa[$no]['nilai']['ujian'][$kumpulUjian]['nilai']= $nilaiUjian;
                                            $siswa[$no]['nilai']['totalUjian'] += $nilaiUjian;
                                            $kumpulUjian++;
                                            // $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
                                        }
                                        $totalUjian = round(($siswa[$no]['nilai']['totalUjian']/$jmlhUjian), 2);
                                    }else {
                                        $totalUjian = 0;
                                    }
                                }

                                $persentaseModul = $infoModul['nilai']['materi'];
                                $persentaseTugas = $infoModul['nilai']['tugas'];
                                $persentaseUjian = $infoModul['nilai']['ujian'];
                                $nilaiMinimal    = $infoModul['nilai']['minimal'];

                                $nilaiAkhirMateri   = $persentaseModul == 0 ? 0 : round($nilaiMateri * ($persentaseModul/100), 2);
                                $nilaiAkhirTugas    = $persentaseTugas == 0 ? 0 : round($totalTugas * ($persentaseTugas/100), 2);
                                $nilaiAkhirUjian    = $persentaseUjian == 0 ? 0 : round($totalUjian * ($persentaseUjian/100), 2);

                                $hasil  = round($nilaiAkhirMateri + $nilaiAkhirTugas + $nilaiAkhirUjian, 2);

                                if ($hakKelas['status'] == 1 || $infoModul['creator'] == $_SESSION['lms_id']) {
                                    $table  .= '    <tr>
                                                        <td class="text-center">'.$siswa[$no]['nama'].'</td>
                                                        <td class="text-center">'.$siswa[$no]['sekolah'].'</td>
                                                        <td class="text-center">'.$siswa[$no]['tkb'].'</td>
                                                        <td class="text-center"><h4 class="no-margin"><b><u>'.$hasil.'</u></b></h4></td>
                                                        <td class="text-center" id="tdAwal'.$no.'"><span id="nilaiMateri'.$no.'" ondblclick="updateNilai(this.id, \'tdAwal'.$no.'\', \'updtNMateri\', \''.$siswa[$no]['_id'].'\', \''.$idmodul.'\', \''.$nilaiMateri.'\')">'.$nilaiMateri.'</span></td>';
                                    if ($jmlhTugas > 0) {
                                        $t=1;
                                        foreach ($siswa[$no]['nilai']['tugas'] as $tableTugas) {
                                    $table  .=  '       <td class="text-center" id="tdTugas'.$no.'-'.$t.'"><span id="nilaiTugas'.$no.'-'.$t.'" ondblclick="updateNilai(this.id, \'tdTugas'.$no.'-'.$t.'\', \'updtNTugas\', \''.$siswa[$no]['_id'].'\', \''.$tableTugas['id'].'\', \''.$tableTugas['nilai'].'\')">'.$tableTugas['nilai'].'</span></td>';
                                    $t++;
                                        }
                                    }else{
                                    $table  .= '        <td class="text-center">'.$totalTugas.'</td>';
                                    }

                                    if ($jmlhUjian > 0) {
                                        // echo "<pre>";
                                        // print_r($siswa[$no]['nilai']['ujian']);
                                        // echo "</pre>";
                                        $t=1;
                                        foreach ($siswa[$no]['nilai']['ujian'] as $tableUjian) {
                                    $table  .=  '       <td class="text-center" id="tdAkhir'.$no.'-'.$t.'"><span id="nilaiUjian'.$no.'-'.$t.'" ondblclick="updateNilai(this.id, \'tdAkhir'.$no.'-'.$t.'\', \'updtNUjian\', \''.$siswa[$no]['_id'].'\', \''.$tableUjian['id'].'\', \''.$tableUjian['nilai'].'\')">'.$tableUjian['nilai'].'</span></td>';
                                    $t++;
                                        }
                                    }else{
                                    $table  .= '        <td class="text-center">'.$nilaiUjian.'</td>';
                                    }

                                    // $table  .= '        <td class="text-center" id="tdAkhir'.$no.'"><span id="nilaiUjian'.$no.'" ondblclick="updateNilai(this.id, \'tdAkhir'.$no.'\', \'updtNUjian\', \''.$siswa[$no]['_id'].'\', \''.$ujian[0]['_id'].'\', \''.$nilaiUjian.'\')">'.$nilaiUjian.'</span></td>
                                    //                 </tr>';
                                }else{
                                    $table  .= '    <tr>
                                                        <td class="text-center">'.$siswa[$no]['nama'].'</td>
                                                        <td class="text-center">'.$siswa[$no]['sekolah'].'</td>
                                                        <td class="text-center">'.$siswa[$no]['tkb'].'</td>
                                                        <td class="text-center"><h4 class="no-margin"><b><u>'.$hasil.'</u></b></h4></td>
                                                        <td class="text-center">'.$nilaiMateri.'</td>';
                                    if ($jmlhTugas > 0) {
                                        $t=1;
                                        foreach ($siswa[$no]['nilai']['tugas'] as $tableTugas) {
                                            $table  .= '<td class="text-center">'.$tableTugas['nilai'].'</td>';
                                            $t++;
                                        }
                                    }else{
                                        $table  .= '<td class="text-center">'.$totalTugas.'</td>';
                                    }

                                    $table  .= '        <td class="text-center">'.$nilaiUjian.'</td>
                                                    </tr>';
                                }

                                $no++;

                            }else {
                                if (isset($anggota['tkb']) && $anggota['tkb'] == $idtkb) {
									$siswa[]        = $userClass->GetData($dataA);
									$siswa[$no]['tkb']   = @$anggota['tkb'];

									// --- Nilai Membaca Materi
									$CekNilaiMateri = $modulClass->getStatusMateri($idmodul, $dataA);
									$nilaiMateri    = $nilaiMateri + $CekNilaiMateri['nilai'];
									$siswa[$no]['nilai']['modul'] = $nilaiMateri;

									// --- Nilai Tugas
									$kumpulTugas = 0;
									if ($jmlhTugas > 0) {
										$siswa[$no]['nilai']['totalTugas'] = 0;
										foreach ($infoTugas as $tugas) {
											$nilaiTugas     = 0;
											$cekNilaiTugas  = $tugasClass->getStatusTugas($tugas['_id'], $dataA);
											$nilaiTugas     = $nilaiTugas + $cekNilaiTugas['nilai'];
											$siswa[$no]['nilai']['tugas'][$kumpulTugas]['id']   = $tugas['_id'];
											$siswa[$no]['nilai']['tugas'][$kumpulTugas]['nama'] = $tugas['nama'];
											$siswa[$no]['nilai']['tugas'][$kumpulTugas]['nilai']= $nilaiTugas;
											$siswa[$no]['nilai']['totalTugas'] += $nilaiTugas;
											$kumpulTugas++;
										}
										$totalTugas = round(($siswa[$no]['nilai']['totalTugas']/$jmlhTugas), 2);

										// --- Nilai Ujian
										$kumpulUjian = 0;
										if ($jmlhUjian > 0) {
											$siswa[$no]['nilai']['totalUjian'] = 0;
											foreach ($ujian as $valueUjian) {
												$nilaiUjian  = 0;
												$cekNilaiUjian  	= $tugasClass->getStatusQuiz($valueUjian['_id'], $dataA);
												//$cekNilaiUjianNew  	= $tugasClass->getStatusQuizNew($valueUjian['_id'], $valueUjian['id_paket'], $dataA);

												$nilaiUjian     = $nilaiUjian + @$cekNilaiUjian['nilai'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['id']   = $valueUjian['_id'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['nama'] = $valueUjian['nama'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['nilai']= $nilaiUjian;
												$siswa[$no]['nilai']['totalUjian'] += $nilaiUjian;

												//  echo "Siswa : $dataA <br>
												//         Nilai : $nilaiUjian <br>
												//         total : ".$siswa[$no]['nilai']['totalUjian'].'<br>';
												// print_r($cekNilaiUjian);
												// echo "</pre>";

												$kumpulUjian++;
												// $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
											}
											$totalUjian = round(($siswa[$no]['nilai']['totalUjian']/$jmlhUjian), 2);
										}else {
											$totalUjian = 0;
										}
									}else {
										// --- Nilai Tugas
										$totalTugas = 100;

										// --- Nilai Ujian
										$kumpulUjian = 0;
										if ($jmlhUjian > 0) {
											$siswa[$no]['nilai']['totalUjian'] = 0;
											foreach ($ujian as $valueUjian) {
												$nilaiUjian  = 0;
												$cekNilaiUjian  = $tugasClass->getStatusQuiz($valueUjian['_id'], $dataA);
												//$cekNilaiUjian  = $tugasClass->getStatusQuizNew($valueUjian['_id'], $valueUjian['id_paket'], $dataA);
												$nilaiUjian     = $nilaiUjian + $cekNilaiUjian['nilai'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['id']   = $valueUjian['_id'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['nama'] = $valueUjian['nama'];
												$siswa[$no]['nilai']['ujian'][$kumpulUjian]['nilai']= $nilaiUjian;
												$siswa[$no]['nilai']['totalUjian'] += $nilaiUjian;
												$kumpulUjian++;
												// $siswa[$no]['nilai']['ujian'] = $nilaiUjian;
											}
											$totalUjian = round(($siswa[$no]['nilai']['totalUjian']/$jmlhUjian), 2);
										}else {
											$totalUjian = 0;
										}
									}

									$persentaseModul = $infoModul['nilai']['materi'];
									$persentaseTugas = $infoModul['nilai']['tugas'];
									$persentaseUjian = $infoModul['nilai']['ujian'];
									$nilaiMinimal    = $infoModul['nilai']['minimal'];

									$nilaiAkhirMateri   = $persentaseModul == 0 ? 0 : round($nilaiMateri * ($persentaseModul/100), 2);
									$nilaiAkhirTugas    = $persentaseTugas == 0 ? 0 : round($totalTugas * ($persentaseTugas/100), 2);
									$nilaiAkhirUjian    = $persentaseUjian == 0 ? 0 : round($totalUjian * ($persentaseUjian/100), 2);

									$hasil  = round($nilaiAkhirMateri + $nilaiAkhirTugas + $nilaiAkhirUjian, 2);

									if ($hakKelas['status'] == 1 || $infoModul['creator'] == $_SESSION['lms_id']) {
										$table  .= '    <tr>
															<td class="text-center">'.$siswa[$no]['nama'].'</td>
															<td class="text-center">'.$siswa[$no]['sekolah'].'</td>
															<td class="text-center">'.$siswa[$no]['tkb'].'</td>
															<td class="text-center"><h4 class="no-margin"><b><u>'.$hasil.'</u></b></h4></td>
															<td class="text-center" id="tdAwal'.$no.'"><span id="nilaiMateri'.$no.'" ondblclick="updateNilai(this.id, \'tdAwal'.$no.'\', \'updtNMateri\', \''.$siswa[$no]['_id'].'\', \''.$idmodul.'\', \''.$nilaiMateri.'\')">'.$nilaiMateri.'</span></td>';
										if ($jmlhTugas > 0) {
											$t=1;
											foreach ($siswa[$no]['nilai']['tugas'] as $tableTugas) {
										$table  .=  '       <td class="text-center" id="tdTugas'.$no.'-'.$t.'"><span id="nilaiTugas'.$no.'-'.$t.'" ondblclick="updateNilai(this.id, \'tdTugas'.$no.'-'.$t.'\', \'updtNTugas\', \''.$siswa[$no]['_id'].'\', \''.$tableTugas['id'].'\', \''.$tableTugas['nilai'].'\')">'.$tableTugas['nilai'].'</span></td>';
										$t++;
											}
										}else{
										$table  .= '        <td class="text-center">'.$totalTugas.'</td>';
										}

										if ($jmlhUjian > 0) {
											// echo "<pre>";
											// print_r($siswa[$no]['nilai']['ujian']);
											// echo "</pre>";
											$t=1;
											foreach ($siswa[$no]['nilai']['ujian'] as $tableUjian) {
										$table  .=  '       <td class="text-center" id="tdAkhir'.$no.'-'.$t.'"><span id="nilaiUjian'.$no.'-'.$t.'" ondblclick="updateNilai(this.id, \'tdAkhir'.$no.'-'.$t.'\', \'updtNUjian\', \''.$siswa[$no]['_id'].'\', \''.$tableUjian['id'].'\', \''.$tableUjian['nilai'].'\')">'.$tableUjian['nilai'].'</span></td>';
										$t++;
											}
										}else{
										$table  .= '        <td class="text-center">'.$nilaiUjian.'</td>';
										}

										// $table  .= '        <td class="text-center" id="tdAkhir'.$no.'"><span id="nilaiUjian'.$no.'" ondblclick="updateNilai(this.id, \'tdAkhir'.$no.'\', \'updtNUjian\', \''.$siswa[$no]['_id'].'\', \''.$ujian[0]['_id'].'\', \''.$nilaiUjian.'\')">'.$nilaiUjian.'</span></td>
										//                 </tr>';
									}else{
										$table  .= '    <tr>
															<td class="text-center">'.$siswa[$no]['nama'].'</td>
															<td class="text-center">'.$siswa[$no]['sekolah'].'</td>
															<td class="text-center">'.$siswa[$no]['tkb'].'</td>
															<td class="text-center"><h4 class="no-margin"><b><u>'.$hasil.'</u></b></h4></td>
															<td class="text-center">'.$nilaiMateri.'</td>';
										if ($jmlhTugas > 0) {
											$t=1;
											foreach ($siswa[$no]['nilai']['tugas'] as $tableTugas) {
												$table  .= '<td class="text-center">'.$tableTugas['nilai'].'</td>';
												$t++;
											}
										}else{
											$table  .= '<td class="text-center">'.$totalTugas.'</td>';
										}

										$table  .= '        <td class="text-center">'.$nilaiUjian.'</td>
														</tr>';
									}

									$no++;

                                }
                            }
                        }

                    }
                }

                $table  .= '                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div><!--.row-->';

                // echo "<div class='col-md-12'>
                //         <pre>";
                // print_r($siswa);
                // echo "  </pre>
                //     </div>";

                echo "$table";
            }
            ?>

		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
    <script src="assets/js/lib/datatables-net/datatables.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/buttons.flash.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/buttons.print.min.js"></script>

	<script>
        var table;

        <?php
        if (isset($_POST['filterData'])) {
            echo 'table = $("#perkembangan").dataTable({
					"dom"			 : "Bfrtip",
					"buttons"		 : ["copy", "excel", "pdf", "print"],
                    "scrollX"        : true,
                    "scrollCollapse" : true,
                    "fixedColumns"   : true,
                    "order"          : [[ 1, "asc" ],[ 0, "asc" ]],
                    "bInfo"          : true,
					"bLengthChange"  : false,
                    "pagingType"     : "simple",
                    "lengthMenu"     : [5],
            });';
        }
        ?>

		$(document).ready(function() {
			$('.note-statusbar').hide();
		});

		function clearText(elementID){
			$(elementID).html("");
		}

        function updateNilai(idKlik, idtd, jenis, siswa, id, nilai){
            // alert(idKlik+' - '+idtd+' - '+user+' - '+modul+' : '+nilai);
            $('#'+idKlik).html('<input type="number" class="form-group thVal" min="0" max="100" maxlength="3" style="padding: 5px; border: 1px solid #ddd; border-radius: 3px; z-index: 9999; text-align: center" value="'+nilai+'">');

            $(".thVal").focus();
            $(".thVal").keyup(function (event) {
                if (event.keyCode == 27 ) {
                    $('#'+idKlik).html(nilai);
                }

                if ($(this).val() > 100) {
                    alert('Nilai Maksimal adalah 100');
                    $('#'+idKlik).html(nilai);
                }

                if (event.keyCode == 13) {
                    nilai   = $(".thVal").val().trim();
                    $('#'+idtd).html('<span id="'+idKlik+'" ondblclick="updateNilai(\''+idKlik+'\', \''+idtd+'\', \''+jenis+'\', \''+siswa+'\', \''+id+'\', \''+$(".thVal").val().trim()+'\')">'+$(".thVal").val().trim()+'</span>');
                    // $('#'+idKlik).html($(".thVal").val().trim());

                    $.ajax({
                        type: 'POST',
                        url: 'url-API/Kelas/Modul/',
                        data: {'action':jenis, 's':siswa, 'i':id, 'n':nilai, "user": "<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
                        success: function(res) {
    						swal({
    				            title: res.response,
    				            text: res.message,
    				            type: res.icon
    				        }, function() {
    				            location.reload();
    				        });
          				},
          				error: function () {
          					swal("Maaf!", "Data tidak tersimpan!", "error");
          				}
                    });
                }

            });

            $(".thVal").focusout(function () { // you can use $('html')
                $('#'+idtd).html('<span id="'+idKlik+'" ondblclick="updateNilai(\''+idKlik+'\', \''+idtd+'\', \''+jenis+'\', \''+siswa+'\', \''+modul+'\', \''+$(".thVal").val().trim()+'\')">'+$(".thVal").val().trim()+'</span>');
            });
        }

        function update(){
      		$('#updateMapel').trigger("reset");
      		$('#updateMapel').modal("show");
      		$('#updateMapelLabel').text(
      		   $('#updateMapelLabel').text().replace('Tambah Modul', 'Pengaturan Mata Pelajaran')
      		).show();
			$('#namaMapelupdate').val("<?=$infoMapel['nama']?>");
			$('#idMapelupdate').val("<?=$_GET['id']?>");
      	}

		function removeMapel(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Data yang sudah dihapus tidak dapat dikembalikan!",
      		  type: "warning",
      		  showCancelButton: true,
			  	confirmButtonText: "Setuju!",
      			confirmButtonClass: "btn-danger",
      		  closeOnConfirm: false,
      		  showLoaderOnConfirm: true
      		}, function () {
      			$.ajax({
                    type: 'POST',
                    url: 'url-API/Kelas/Mapel/',
                    data: {"action": "removeMapel", "ID": ID, "user": "<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoKelas['_id']?>"},
                    success: function(res) {
                        swal({
                            title: res.response,
                            text: res.message,
                            type: res.icon
                        }, function() {
                            location.href='kelas.php?id=<?=$infoKelas['_id']?>';
                        });
                    },
                    error: function () {
                        swal("Gagal!", "Data tidak terhapus!", "error");
                    }
                });
      		});
      	}
        //
        // var nilai;
        // var idKlik;
        // var idtd;
        //
        // $('.editable').click(function(e){
        //     idKlik  = $(this).attr('id');
        //     nilai   = $(this).html();
        //
        //     if (idKlik == 'nilaiMateri') {
        //         idtd    = '#tdAwal';
        //         $('#tdAwal').html('<input type="number" class="form-group thVal" min="0" max="100" maxlength="3" style="padding: 5px; border: 1px solid #ddd; border-radius: 3px; z-index: 9999; text-align: center" value="'+nilai+'">');
        //     }else if (idKlik == 'nilaiUjian') {
        //         idtd    = '#tdAkhir';
        //         $('#tdAkhir').html('<input type="number" class="form-group thVal" min="0" max="100" maxlength="3" style="padding: 5px; border: 1px solid #ddd; border-radius: 3px; z-index: 9999; text-align: center" value="'+nilai+'">');
        //     }
        //
        //     updateVal(idtd, idKlik, nilai);
        //     console.log("Nilai dari "+idtd+" adalah "+nilai);
        // });
        //
        // function updateVal(currentEle, spanEle, value) {
        //     $(".thVal").focus();
        //     $(".thVal").keyup(function (event) {
        //         if (event.keyCode == 13) {
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+$(".thVal").val().trim()+'</span>');
        //         }
        //
        //         if (event.keyCode == 27 ) {
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+value+'</span>');
        //         }
        //
        //         if ($(this).val() > 100) {
        //             alert('Nilai Maksimal adalah 100');
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+value+'</span>');
        //         }
        //     });
        //     //
        //     $(".thVal").focusout(function () { // you can use $('html')
        //         if ($(this).val() > 100) {
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+value+'</span>');
        //         } else if ($(this).val() == 0) {
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+value+'</span>');
        //         } else {
        //             $(currentEle).html('<span class="editable" id="'+spanEle+'">'+$(".thVal").val().trim()+'</span>');
        //         }
        //     });
        // }
	</script>

<script src="assets/js/app.js"></script>
<script src="assets/js/lib/datatables-net/datatables.min.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
