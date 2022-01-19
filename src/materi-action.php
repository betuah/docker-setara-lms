<?php
require("includes/header-top.php");
?>
<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

<!-- Style for html code -->
<link type="text/css" rel="stylesheet" href="./assets/tinymce4/css/prism.css" />

<?php
require("includes/header-menu.php");

$kelasClass     = new Kelas();
$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$materiClass 	= new Materi();

$menuModul		= 2;
$infoModul		= $modulClass->getInfoModul($_GET['modul']);
$infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);

if(isset($_POST['addMateri']) || isset($_POST['updateMateri'])){
    $judul  = mysql_escape_string(trim($_POST['judul']));
    $isi    = $_POST['isi_materi'];
    $stat   = $_POST['publikasi'];
	if(isset($_POST['addMateri'])){
		$rest   = $materiClass->addMateri($_GET['modul'], $judul, $isi, $stat, $infoMapel['id_kelas'], $_SESSION['lms_id']);
	}else{
        // $rest['status'] = "Success";
		$rest 	= $materiClass->updateMateri($_GET['materi'], $judul, $isi, $stat, $infoMapel['id_kelas']);
	}

	if ($rest['status'] == "Success") {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
		echo "<script>alert('".$rest['status']."'); document.location='modul?modul=".$_GET['modul']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}

$hakKelas       = $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
if(!$hakKelas['status']){
    echo "<script>
            swal({
                title: 'Maaf!',
                text: 'Anda tidak terdaftar pada Kelas / Kelas tidak tsb tidak ada.',
                type: 'error'
            }, function() {
                 window.location = 'index';
            });
        </script>";
        die();
}

$logIDKelas = $infoMapel['id_kelas'];

?>
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
												<p class="title">Kegiatan Belajar <?=$infoModul['nama']?></p>
												<p>Mata Pelajaran <?=$infoMapel['nama']?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-12 col-lg-12">
					<section class="card card-default">
                    <?php
                        if (isset($_GET['act']) && ($_GET['act'] == 'update')) {
                            $infoMateri		= $materiClass->getInfoMateri($_GET['materi']);
                    ?>
                        <script type="text/javascript">document.title = "Kelola Materi Kegiatan Pembelajaran <?=$infoModul['nama']?> - seTARA daring";</script>
                        <div class="card-block">
                            <h5 class="with-border">Perubahan Materi</h5>
                            <form id="form_tambah" method="POST">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Terbitkan ?</label>
                                    <div class="col-md-8">
                                        <div class="radio">
            								<input type="radio" name="publikasi" id="radio-1" value="publish" <?=$infoMateri['status'] == "publish" ? "checked" : ""?> >
            								<label for="radio-1">Ya </label> &nbsp;
            								<input type="radio" name="publikasi" id="radio-2" value="draft" <?=$infoMateri['status'] == "draft" ? "checked" : ""?>>
            								<label for="radio-2">Tidak </label>
            							</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Judul materi</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="judul" placeholder="Judul dari materi" value="<?=$infoMateri['judul']?>" required="require" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Isi materi</label>
                                    <div class="col-md-9">
                                        <div id="editorContainer">
                                            <div id="toolbarLocation"></div>
                                            <textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example">
                                                <?=$infoMateri['isi']?>
                                            </textarea>
                                            <input id="editor" type="text" name="isi_materi" style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group pull-right">
                                    <button type="submit" name="updateMateri" class="btn">Simpan</button>
                                    <button type="button" class="btn btn-default" onclick="history.go(-1)">Batal</button>
                                </div>
                            </form>
                        </div>
                    <?php
                        }else{
                    ?>
                        <script type="text/javascript">document.title = "Tambah Materi Kegiatan Pembelajaran <?=$infoModul['nama']?> - SIAJAR LMS";</script>
						<div class="card-block">
                            <h5 class="with-border">Penambahan Materi</h5>
                            <form id="form_tambah" method="POST">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Terbitkan ?</label>
                                    <div class="col-md-8">
                                        <div class="radio">
            								<input type="radio" name="publikasi" id="radio-1" value="publish">
            								<label for="radio-1">Ya </label>
                                            &nbsp;
            								<input type="radio" name="publikasi" id="radio-2" value="draft" checked>
            								<label for="radio-2">Tidak </label>
            							</div>
                                    </div>
                                </div>
            					<div class="form-group row">
            						<label class="col-md-2 form-control-label">Judul materi</label>
            						<div class="col-md-8">
            							<input type="text" class="form-control" name="judul" placeholder="Judul dari materi" required="require">
            						</div>
            					</div>
                                <div class="form-group row">
            						<label class="col-md-2 form-control-label">Isi materi</label>
            						<div class="col-md-9">
                                        <div id="editorContainer">
                    						<div id="toolbarLocation"></div>
                							<textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example"></textarea>
                							<input id="editor" type="text" name="isi_materi" style="display: none;" />
                    					</div>
            						</div>
            					</div>
                                <?php
                                if (($hakKelas['status'] == 1) || ($infoMapel['creator'] == $_SESSION['lms_id'])) {
                                    # code...
                                ?>
                                <hr>
                                <div class="form-group pull-right">
									<button type="submit" name="addMateri" class="btn">Tambah</button>
									<button type="button" class="btn btn-default" onclick="history.go(-1)">Batal</button>
								</div>
                                <?php
                                }
                                ?>
            				</form>
						</div>
                    <?php
                        }
                    ?>
					</section>
				</div>
			</div><!--.row-->

		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script>
        $("#form_tambah").submit(function(e){
            $("#editor").val(tinyMCE.get('editormce').getContent());
        });

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

	<script src="assets/js/app.js"></script>
    <script type="text/javascript" src="./assets/tinymce4/js/wirislib.js"></script>
	<script type="text/javascript" src="./assets/tinymce4/js/prism.js"></script>

<?php
	require('includes/footer-bottom.php');
?>
