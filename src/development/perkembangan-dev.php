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


if (isset($_POST['filterData'])) {
	$idtkb      	= isset($_POST['tkbFilter']) ? $_POST['tkbFilter'] : "0";
	$infoKelas		= $kelasClass->getInfoKelasTkb($infoMapel['id_kelas'], $idtkb);
}else{
	$infoKelas		= $kelasClass->getInfoKelas($infoMapel['id_kelas']);
}

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
                                                <p class="title"><?=$_SESSION['lms_name']?> <small>(<?=$hakKelas['posisi']?>)</small></p>
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
                            <h5 class="with-border"><b>Perkembangan Akademik Edit / <a href=""><?=$infoKelas['nama']?></a></b></h5>
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
                $no         = 0;
                $siswa      = array();

                // ----> Cek Tugas <---- //
                $infoModul  = $modulClass->getInfoModul($idmodul);

                // ----> Cek Tugas <---- //
                $infoTugas  = $tugasClass->getListTugas($idmodul);
                $jmlhTugas  = $infoTugas->count();

                // ----> Cek Ujian <---- //
                $listUjian  = $quizClass->getListbyModul_($idmodul);
                $jmlhUjian  = sizeof($listUjian);

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
	                                                    <th rowspan="3" class="text-center">Kelompok<br>Belajar</th>
	                                                    <th rowspan="3" class="text-center">Nilai Akhir<br>Modul</th>
	                                                    <th colspan="'.$clspn.'" class="text-center">'.$infoModul['nama'].'</th>
	                                                </tr>
	                                                <tr>
	                                                    <th rowspan="2" class="text-center">Nilai Membaca Materi<br/>[Bobot: '.$infoModul['nilai']['materi'].'%]</th>
	                                                    <th colspan="'.$jmlhTugas.'" class="text-center">Nilai Tugas<br/>[Bobot: '.$infoModul['nilai']['tugas'].'%]</th>
	                                                    <th colspan="'.$jmlhUjian.'" class="text-center">Nilai Evaluasi<br/>[Bobot: '.$infoModul['nilai']['ujian'].'%]</th>
	                                                </tr>
	                                                <tr>';

														$list_id_tugas = "";
							                            if ($jmlhTugas > 0) {
							                                foreach ($infoTugas as $value) {
																$list_id_tugas .= $value['_id'].";";
							                                    $table  .= '<th class="text-center" style="width:100px;">'.$value['nama'].'</th>';
							                                }
							                            }else{
															$table  .= '<th class="text-center">Belum Ada</th>';
														}

														$list_id_ujian = "";
							                            if ($jmlhUjian > 0) {
							                                foreach ($listUjian as $valueUjian) {
																$list_id_ujian .= $valueUjian['_id']."|".$valueUjian['jumlah_soal'].";";
							                                    // $table  .= '<th class="text-center" style="width:100px;">'.$valueUjian['nama'].'<br/>[Jumlah Soal: '.$valueUjian['jumlah_soal'].']</th>';
																$table  .= '<th class="text-center" style="width:100px;">'.$valueUjian['nama'].'</th>';
							                                }
							                            }else{
							                                $table  .= '<th class="text-center">Belum Ada</th>';
							                            }
				$table  .= '						</tr>
                                            	</thead>
	                                        	<tbody>
												</tbody>
		                                    </table>
		                                </div>
		                            </div>
		                        </section>
		                    </div>
		                </div><!--.row-->';

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
				if($_SESSION['lms_status']=='guru'){
		?>
					var jumlah_siswa = <?=$infoKelas['jumlah_siswa']?>;

					if(jumlah_siswa > 5){
						lengthList = [ [5, jumlah_siswa], [5, "ALL"] ];
					}else{
						lengthList = [jumlah_siswa];
					}
		<?php 	}else{ ?>
					lengthList = [1];
		<?php 	} ?>

        if(jumlah_siswa == 0){
            table = $('#perkembangan').DataTable({});
        }else{
            table = $('#perkembangan').DataTable( {
                "processing"    : true,
                "bServerSide"   : true,
                "sAjaxSource"   : "url-API/Kelas/Mapel?action=perkembangan-dev&status=<?=$_SESSION['lms_status']?>&akun=<?=$_SESSION['lms_id']?>&kelas=<?=$infoMapel['id_kelas']?>&tkb=<?=$idtkb?>&modul=<?=$idmodul?>&tugas=<?=$list_id_tugas?>&ujian=<?=$list_id_ujian?>&bobot_materi=<?=$infoModul['nilai']['materi']?>&bobot_tugas=<?=$infoModul['nilai']['tugas']?>&bobot_ujian=<?=$infoModul['nilai']['ujian']?>",
                "deferRender"   : true,
                "aoColumns"     : [
                                    { "mDataProp": "nama" },
                                    { "mDataProp": "tkb"},
                                    { "mDataProp": "nilai_akhir"},
                                    { "mDataProp": "nilai_materi"},
                                    <?php
                                    if ($jmlhTugas > 0) {
                                        foreach ($infoTugas as $value) {
                                    ?>
                                            { "mDataProp": "nilai_tugas_<?=$value['_id']?>", "sClass": "text-center"},
                                    <?php
                                        }
                                    }else{
                                    ?>
                                            { "mDataProp": "nilai_tugas", "sClass": "text-center"},
                                    <?php
                                    }

                                    if ($jmlhUjian > 0) {
                                        foreach ($listUjian as $valueUjian) {
                                    ?>
                                            { "mDataProp": "nilai_ujian_<?=$valueUjian['_id']?>", "sClass": "text-center"},
                                    <?php
                                        }
                                    }else{
                                    ?>
                                        { "mDataProp": "nilai_ujian", "sClass": "text-center"},
                                    <?php
                                    }
                                    ?>
                                    ],
                "searchCols"    : [null, null, null ],
                "order"         : [[1, 'desc']],
                "language"      : {
                                        "infoFiltered"  : "",
                                        "processing"	: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>'
                                    },
                "columnDefs": [
                    {"className": "font-weight-bold text-center", "targets": [2]},
                    {"className": "text-center", "targets": [1,2,3]}
                ],
                "dom"			 : '<"row"<"col-sm-4"l><"col-sm-4"Br><"col-sm-4"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "buttons"		 : ["copy", "excel", "pdf", "print"],
                "scrollX"        : true,
                "scrollCollapse" : true,
                "lengthMenu"     : lengthList
            } );

            new $.fn.dataTable.FixedColumns( table, {
                leftColumns: 3
            });
        }

		<?php
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
