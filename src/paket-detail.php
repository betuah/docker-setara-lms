<?php
require("includes/header-top.php");
?>
<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
		tinymce.init({
    		selector: '.myeditablediv',
    		height : 100,
        	menubar: false,
        	auto_focus:true,


        // To avoid TinyMCE path conversion from base64 to blob objects.
        // https://www.tinymce.com/docs/configure/file-image-upload/#images_dataimg_filter
        images_dataimg_filter : function(img) {
            return img.hasAttribute('internal-blob');
        },
        setup : function(ed)
        {
            ed.on('init', function()
            {
                this.getDoc().body.style.fontSize = '16px';
                this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
            });
        },
         plugins: [
              "advlist autolink link image lists charmap print preview hr anchor pagebreak",
              "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
              "table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris"
         ],
         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
         toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry",
         image_advtab: true
  		});

function add(){
      		$('#addModul').trigger("reset");
      		$('#addModul').modal('show');
			$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Edit Modul', 'Tambah Modul')
      		).show();
      	};
</script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script> -->

<!-- Style for html code -->
<link type="text/css" rel="stylesheet" href="./assets/tinymce4/css/prism.css" />
<?php
require("includes/header-menu.php");

$mapelClass = new Mapel();
$modulClass = new Modul();
$quizClass  = new Quiz();
$soalClass	= new Soal();



$menuModul		= 4;
$infoPaket	= $quizClass->getInfoPaket($_GET['id']);
$listSoal	= $soalClass->getListbyQuiz($_GET['id']);
// print_r($listSoal);
// $infoSoal	= $soalClass->getInfoSoal($)
// $infoModul	= $modulClass->getInfoModul($_GET['md']);
// $infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$opsi=2;

if(isset($_POST['addQuiz'])){
	// print_r($_POST);
	$soal 		= $_POST['soal'];
	$jawaban 	= $_POST['jawaban'];
	$benar 		= $_POST['benar'];

// print_r($benar);
	$soalClass->addSoal($soal,$jawaban,$benar,$_GET['id'], $_SESSION['lms_id']);
}

if (isset($_POST['updateInfoQuiz'])) {
	# code...
	$nama = $_POST['nama'];
	$durasi = $_POST['durasi'];
	$mulai = $_POST['mulai'];
	$selesai = $_POST['selesai'];

	$quizClass->updateQuiz($_GET['qz'], $nama, $durasi, $mulai,$selesai);
}

?>
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
							<input type="text" class="form-control" name="namaMapelupdate" id="namaMapelupdate" placeholder="Nama Mata Pelajaran" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-danger pull-left" onclick="" name="hapusKelas"><i class="font-icon-trash"></i> Hapus Mata Pelajaran</button>
					<button type="submit" class="btn btn-rounded btn-primary" name="updateMapel" value="send" >Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade bd-example-modal-lg"
		 id="addModul"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addModulLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="form_tambah" method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addModulLabel">Tambah Kuis</h4>
				</div>
				<div class="modal-body">
					<fieldset class="form-group">
						<label class="form-label semibold" for="exampleInput">Soal</label>
						<textarea class ="myeditablediv" id="soal" name="soal" ></textarea>
					</fieldset>
					<label class="form-label semibold" for="exampleInput">Jawaban</label>
					<hr />
					<fieldset class="form-group">
						<label class="form-label " for="exampleInput">Pilhan 1</label>
						<textarea class ="myeditablediv" id="jawab1" name="jawaban[]" ></textarea>
						Atur Jawaban Benar <input type="radio" name="benar" value="0" checked="checked">
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label " for="exampleInput">Pilihan 2</label>
						<textarea class="myeditablediv" id="jawab2" name="jawaban[]" ></textarea>
						Atur Jawaban Benar <input type="radio" name="benar" value="1">
					</fieldset>
					<div class ="opsitambahan">

					</div>
					<a style="align:right;color:#009dff;" id="tambahopsi" onclick="tambahOpsi();">+ Tambah Pilihan</a>
				</div>
				<div class="modal-footer">
					<button type="submit" name="addQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="page-content">
		<div class="profile-header-photo">
			<div class="profile-header-photo-in">
				<div class="tbl-cell">
					<div class="info-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<div class="tbl info-tbl">
										<div class="tbl-row">
											<div class="tbl-cell">
												<p class="title">Paket Soal - <?=$infoPaket['nama']?></p>
												<p>List Soal Paket</p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<!-- <p class="title"><?//=$listSoal['jmlSoal']?></p> -->
													<!-- <p>Soal</p> -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <button type="button" class="change-cover" onclick="update()">
				<i class="font-icon font-icon-pencil"></i>
				Pengaturan Mata Pelajaran
			</button> -->
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-12 col-lg-12">
					<section class="widget widget-activity">
						<header class="widget-header">
							Soal Kuis Paket Soal - <?=$infoPaket['nama']?>
							<div class="btn-group" style="float:right;">
							<?php
								if ($_SESSION['lms_id'] == $infoPaket['creator']) {
							?>
									<button type="button" class="btn btn-sm btn-inline" onclick="add()" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan Modul baru.">+ Tambah Soal</button>
							<?php
								}
							?>
							</div>

						</header>
						<div>
						<div class="card-block" id="accordion">
							<?php
					$no	= 1;
					// print_r($listQuiz);
					if ($listSoal->count() > 0) {
						foreach ($listSoal as $materi) {
							echo '<article class="box-typical profile-post panel">
									<div class="profile-post-header">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-photo">
													<a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">
														<img src="assets/img/test-quiz.png" alt="">
													</a>
												</div>
												<div class="tbl-cell">
													<div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$materi['soal'].'</a></div>
													<div class="color-blue-grey-lighter">'.($materi['date_created'] == $materi['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($materi['date_modified']).'</div>
												</div>
												<div class="tbl-cell" align="right">';
												if ($_SESSION['lms_id'] == $materi['creator']) {
													// echo '<a onclick="edit(\''.$materi['_id'].'\')" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui Modul yang sudah dibuat."><i class="font-icon font-icon-pencil"></i></a>
													echo '<a href="edit-quiz.php?act=update&qz='.$_GET['id'].'&id='.$materi['_id'].'" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Materi yang sudah dibuat." style="right: 35px">
															<i class="font-icon font-icon-pencil")"></i>
														<a onclick="remove(\''.$materi['_id'].'\')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Materi yang sudah dibuat.">
															<i class="font-icon font-icon-trash")"></i>
														</a>';


												}
							echo '				</div>
											</div>
										</div>
									</div>
									<div id="demo'.$no.'" class="profile-post-content collapse">';
										$idSoal = $materi['_id'];
										$listJawaban = $soalClass->getListJawaban($idSoal);
										foreach ($listJawaban as $jawaban) {
											echo '<article class="box-typical profile-post panel">
													<div class="profile-post-header">
														<div class="user-card-row">

															<div class="col-md-11">'.$jawaban['text'].'</div>' ;
															if ($jawaban['status'] == "benar") {
																echo '<div class="col-md-1"><i class="fa fa-check success"></i></div>';
															}
											echo	'
														</div>
													</div>
												</article>';
										}
										// print_r($listJawaban);
										// if ($list) {
										// 	# code...
										// }
							echo '	</div>
								</article>
							';
							$no++;
						}
					}else {
						echo '	<article class="box-typical profile-post">
									<div class="profile-post-content" align="center">
										<span>
										 Belum ada Soal pada kuis ini saat ini. <br />
										<button type="button" class="btn btn-sm btn-inline" onclick="add()" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan Modul baru.">+ Buat Soal Pertama</button>
										</span>
									</div>
								</article>';
					}
				?>
							</div>
						</div>
					</section><!--.widget-tasks-->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
<script>
				var i = 2;
				var j = 1;
			function tambahOpsi(){
					i = i+1;
					j = j+1;
				// js.src = "./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image";
				// js.src = "./assets/tinymce4/js/tinymce/tinymce.min.js";

				$(".opsitambahan").append("<fieldset class='form-group'><label class='form-label' for='exampleInput'>Pilihan "+i+"</label><textarea class='myeditablediv' name='jawaban[]' ></textarea>Atur Jawaban Benar <input type='radio' name='benar' value='"+j+"'></fieldset>");

			tinymce.init({
	    		selector: '.myeditablediv',
	    		height : 100,
	        	menubar: false,
	        	auto_focus:true,
	        // To avoid TinyMCE path conversion from base64 to blob objects.
	        // https://www.tinymce.com/docs/configure/file-image-upload/#images_dataimg_filter
	        images_dataimg_filter : function(img) {
	            return img.hasAttribute('internal-blob');
	        },
	        setup : function(ed)
	        {
	            ed.on('init', function()
	            {
	                this.getDoc().body.style.fontSize = '16px';
	                this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
	            });
	        },
	         plugins: [
	              "advlist autolink link image lists charmap print preview hr anchor pagebreak",
	              "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
	              "table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris"
	         ],
	         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
	         toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry",
	         image_advtab: true
	  		});




			}


		$(document).ready(function() {
			$('.note-statusbar').hide();

		});

		function clearText(elementID){
			$(elementID).html("");
		}

		function remove(ID){
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
      				url: 'url-API/Kelas/Modul/Materi/',
      				data: {"action": "remv", "ID": ID},
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
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}
	</script>

    <script type="text/javascript" src="./assets/tinymce4/js/wirislib.js"></script>
	<script type="text/javascript" src="./assets/tinymce4/js/prism.js"></script>

	<script>
		function clearText(elementID){
			$(elementID).html("");
		}

		function add(){
      		// $('#addModul').trigger("reset");
      		$('#addModul').modal('show');
			// $('#addModulLabel').text(
   //    		   $('#addModulLabel').text().replace('Edit Modul', 'Tambah Modul')
   //    		).show();
      	};

		function update(){
      		$('#updateMapel').trigger("reset");
      		$('#updateMapel').modal("show");
      		$('#updateMapelLabel').text(
      		   $('#updateMapelLabel').text().replace('Tambah Modul', 'Pengaturan Mata Pelajaran')
      		).show();
			$('#namaMapelupdate').val("<?=$infoMapel['nama']?>");
      	}

		function edit(ID){
      		$('#addModul').trigger("reset").show();
      		$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Tambah Kuis', 'Edit Soal')
      		).show();
			$('#addModul').modal('show');
      		$.ajax({
      			type: 'POST',
      			url: 'url-API/Kelas/Modul/Quiz/Soal/',
      			data: {"action": "show", "ID": ID},
      			success: function(res) {
      				if(res.data._id.$id){
      					$('#addModul').modal('show');
      					$('#idmodul').val(ID);
      					$('#soal').val(res.data.text);
      					alert(data);
      				}else {
      					swal("Gagal!", "Data tidak ditemukan!", "error");
      				}
      			},
      			error: function () {
      				swal("Gagal!", "Gagal mencari data!", "error");
      			}
      		});
      	}

		function remove(ID){
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
      				url: 'url-API/Kelas/Modul/Quiz/Soal/',
      				data: {"action": "remv", "ID": ID},
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
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}

		$(document).ready(function() {
			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});
	</script>
	<script>
		$("#ohyeah").click(function(){
			$.ajax({
  				type: 'POST',
  				url: 'url-API/Siswa/index.php',
  				data: {"action": "update", "text": "tôi"},
  				success: function(res) {
	  				alert(res.text1);
	  				alert(res.text2);
	  				alert(res.text3);
  				},
  				error: function () {

  				}
  			});
		})
	</script>
<script src="assets/js/app.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
