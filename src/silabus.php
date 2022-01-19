<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$mapelClass = new Mapel();
$modulClass = new Modul();
$kelasClass = new Kelas();
$tugasClass = new Tugas();

$menuMapel	= 1;
$infoMapel	= $mapelClass->getInfoMapel($_GET['id']);
$listModul	= $modulClass->getListbyMapel($_GET['id']);
$infoKelas	= $kelasClass->getInfoKelas($infoMapel['id_kelas']);

if ($_SESSION['lms_status'] == 'superadmin' || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengawas' || $_SESSION['lms_status'] == 'kepsek' ) {
	# code...
	$hakKelas['status'] = 3;
	$hakKelas['posisi']	= 'Super Administrator';
}else{
	$hakKelas	= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
	$anggota	= in_array($_SESSION['lms_id'], array_values($infoKelas['list_member'])) ? true : false;
	if(!$anggota){
		echo "<script>
				swal({
					title: 'Maaf!',
					text: 'Anda tidak terdaftar pada Kelas ini.',
					type: 'error'
				}, function() {
					 window.location = 'index.php';
				});
			</script>";
			die();
	}
}

if(isset($_POST['addSilabus'])){

	$rest = $mapelClass->updateSilabus($_POST['deskripsi'], $_GET['id'], "$infoKelas[_id]");

	if ($rest['status'] == "success") {
		echo "<script>
				swal({
						title	: '".$rest['judul']."',
						text	: '".$rest['message']."',
						type	: '".$rest['status']."'
					}, function(){
						window.location = 'silabus.php?id=".$rest['IDMapel']."';
					});
				</script>";
	}
	// print_r($rest);
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
						 window.location = 'silabus.php?id=$rest[IDMapel]';
					});
				</script>";
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam merubah Pengaturan kelas.',
						type: 'error'
					}, function() {
						 window.location = './';
					});
				</script>";
	}
}
$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Petunjuk Belajar Mata Pelajaran <?=$infoMapel['nama']?> - seTARA daring";</script>
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">
<link rel="stylesheet" href="./assets/tinymce4/css/prism.css" type="text/css" />
<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
<style media="screen">
	@media (max-width: 470px) {
		.tb-lg {
			display: none;
		}
	}
	@media (min-width: 470px) {
		.tb-sm {
			display: none;
		}
	}
</style>

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
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><?=$infoMapel['modul']?></p>
													<p>Modul</p>
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
					<section class="card card-default" id="tugas-editor" style="display: none;">
						<div class="card-block">
                            <h5 class="with-border" id="judul-editor">Pembuatan Petunjuk Belajar</h5>

                            <form id="form_tambah" method="POST" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Isi</label>
                                    <div class="col-md-10">
                                        <div id="editorContainer">
                                            <div id="toolbarLocation"></div>
                                            <textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example"></textarea>
                                            <input id="editor" type="text" name="deskripsi" style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group pull-right">
                                    <button type="submit" id="btn-submit" name="addSilabus" class="btn">Simpan</button>
                                    <button type="button" class="btn btn-default" id="btn-cancel">Batal</button>
                                </div>
                            </form>
                        </div>
					</section>

					<section id="tugas-preview" class="card card-inversed" style="">
						<header class="card-header">
							Petunjuk Belajar

							<?php
							if ($hakKelas['status'] == 1 || $hakKelas['status'] == 2) {
								if(($hakKelas['status'] == 2) && ($infoMapel['creator'] == $_SESSION['lms_id'])){
									echo '<div class="btn-group" style="float: right;">
										<button id="btn-tambah" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan tugas baru." class="btn btn-sm btn-rounded"><i class="fa fa-pencil"></i> Edit</button>
									</div>';
								}elseif($hakKelas['status'] == 1) {
									echo '<div class="btn-group" style="float: right;">
											<button id="btn-tambah" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan tugas baru." class="btn btn-sm btn-rounded"><i class="fa fa-pencil"></i> Edit</button>
										</div>';
								}
							}
							?>
						</header>

						<div class="card-block" id="accordion">
							<?php
								if (isset($infoMapel['silabus']) && ($infoMapel['silabus'] != '')) {
										echo '<article class="box-typical profile-post panel">
												<div>
													<div class="profile-post-content">
														'.$infoMapel['silabus'].'
													</div>
												</div>
											</article>';
								}else {
									echo '	<article class="box-typical profile-post">
												<div class="add-customers-screen tbl">
													<div class="add-customers-screen-in">
														<div class="add-customers-screen-user">
															<i class="fa fa-file-text"></i>
														</div>
														<h2>Belum ada Petunjuk Belajar</h2>
													</div>
												</div>
											</article>';
								}
							?>
						</div>
					</section>
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>

	<script>
		function clearText(elementID){
			$(elementID).html("");
		}

		$("#form_tambah").submit(function(e){
			$("#editor").val(tinyMCE.get('editormce').getContent());
		});

		// Initialize action.
		$('#btn-tambah').on('click', function () {
			$('#form_tambah').trigger("reset");
			$('#tugas-editor').show();
			$('#tugas-preview').hide();


	      		$.ajax({
	      			type: 'POST',
	      			url: 'url-API/Kelas/Mapel/',
	      			data: {"action": "show", "ID": "<?=$_GET['id']?>"},
	      			success: function(res) {
						tinyMCE.activeEditor.setContent(res.data.silabus);
	      			},
	      			error: function () {
	      				swal("Gagal!", "Data tidak ditemukan!", "error");
	      			}
	      		});
		});

		// Destroy action.
		$('#btn-cancel').on('click', function () {
			$('#tugas-preview').show();
			$('#tugas-editor').hide();
			$('#form_tambah').trigger("reset");
			$('#btn-submit').attr('name', 'addSilabus');
		});

		function add(){
      		$('#addModulForm').trigger("reset");
      		$('#addModul').modal('show');
			$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Edit Modul', 'Tambah Modul')
      		).show();
			$('#btn-submit').attr('name', 'addModul');
			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/',
				data: {"action": "showAll", "IDKelas": '<?=$_GET['id']?>'},
				success: function(res) {
					if(res.data.length > 0){
						var html	= '<option value="0">-- Tidak ada --</option>';
			          	for(var i=0;i<res.data.length;i++){
			       			html += '<option value="'+res.data[i]._id.$id+'">'+res.data[i].nama+'</option>';
			          	}
			          $("#prasyaratmodul").html('');
			          $("#prasyaratmodul").html(html);
				  	}else {
				  		swal("Gagal!", "Data tidak tersedia!", "error");
				  	}
			  	},
				error: function () {
					swal("Gagal!", "Data tidak dapat diambil!", "error");
				}
			});
      	};

		function cModul(modul){
			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/',
				data: {"action": "updtNMateri", 's':'<?=$_SESSION['lms_id']?>', 'i':modul, 'n':'100'},
				success: function(res) {
					console.log(res.response+' '+res.message);
			  	},
				error: function () {
					console.log('Terjadi Kesalahan, Gagal melakukan aksi!');
				}
			});
      	};

		function update(){
      		$('#updateMapel').trigger("reset");
      		$('#updateMapel').modal("show");
      		$('#updateMapelLabel').text(
      		   $('#updateMapelLabel').text().replace('Tambah Modul', 'Pengaturan Mata Pelajaran')
      		).show();
			$('#namaMapelupdate').val("<?=$infoMapel['nama']?>");
			$('#idMapelupdate').val("<?=$_GET['id']?>");
      	}

		function edit(ID){
      		$('#addModulForm').trigger("reset");
      		$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Tambah Modul', 'Edit Modul')
      		).show();
			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/',
				data: {"action": "showAll", "IDKelas": '<?=$_GET['id']?>'},
				success: function(res) {
					if(res.data.length > 0){
						var html	= '<option value="0">-- Tidak ada --</option>';
			          	for(var i=0;i<res.data.length;i++){
							if(res.data[i]._id.$id != ID){
			       				html += '<option value="'+res.data[i]._id.$id+'">'+res.data[i].nama+'</option>';
							}
			          	}
			          $("#prasyaratmodul").html('');
			          $("#prasyaratmodul").html(html);

					  $.ajax({
		        			type: 'POST',
		        			url: 'url-API/Kelas/Modul/',
		        			data: {"action": "show", "ID": ID},
		        			success: function(res) {
		        				if(res.data._id.$id){
		        					$('#btn-submit').attr('name', 'updateModul');
		        					$('#addModul').modal('show');
		        					$('#idmodul').val(ID);
		        					$('#namamodul').val(res.data.nama);
		        					$('#nilaimateri').val(res.data.nilai.materi);
		        					$('#nilaitugas').val(res.data.nilai.tugas);
		        					$('#nilaiujian').val(res.data.nilai.ujian);
		        					$('#nilaiminimal').val(res.data.nilai.minimal);
		  							SelectElement('prasyaratmodul', res.data.prasyarat);
		        				}else {
		        					swal("Gagal!", "Data tidak ditemukan!", "error");
		        				}
		        			},
		        			error: function () {
		        				swal("Gagal!", "Gagal mencari data!", "error");
		        			}
		        		});
			        }else {
			          swal("Error!", "Data tidak ditemukan!", "error");
			        }
				},
				error: function () {
					swal("Gagal!", "Data tidak tersedia!", "error");
				}
			});
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

		$(document).ready(function() {

			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});
	</script>

<script src="assets/js/app.js"></script>
<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML">
	MathJax.Hub.Config({
		extensions: ["mml2jax.js"],
		jax: ["input/MathML","output/HTML-CSS"]
	});
</script>
<script type="text/javascript" src="./assets/tinymce4/js/wirislib.js"></script>
<script type="text/javascript" src="./assets/tinymce4/js/prism.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
