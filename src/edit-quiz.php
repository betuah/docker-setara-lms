<?php
require("includes/header-top.php");
?>
<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

<!-- Style for html code -->

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
              //"table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris eqneditor"
              "table contextmenu directionality emoticons paste textcolor responsivefilemanager code eqneditor"
         ],
         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
         //toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry eqneditor",
         toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | eqneditor",
         image_advtab: true,

         external_filemanager_path:"assets/filemanager/",
         filemanager_title:"Tata Kelola File"
        });
</script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script> -->

<!-- Style for html code -->
<link type="text/css" rel="stylesheet" href="./assets/tinymce4/css/prism.css" />

<?php
require("includes/header-menu.php");

$mapelClass		= new Mapel();
$modulClass		= new Modul();
$materiClass	= new Materi();
$soalClass		= new Soal();
$quizClass		= new Quiz();

$infoQuiz		= $quizClass->getInfoQuiz($_GET['qz']);

if(isset($_GET['md'])) {
	$infoModul	= $modulClass->getInfoModul($_GET['md']);
	$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
}

if(isset($_GET['id'])) {
	# code...
	$infoSoal = $soalClass->getInfoSoal($_GET['id']);
	$listJawaban = $soalClass->getListJawaban($_GET['id']);
}


if(isset($_POST['updateQuiz'])){
    // print_r($_POST);
    $soal       = $_POST['soal'];
    $jawaban    = $_POST['jawaban'];
    $benar      = $_POST['benar'];

    if (!isset($_GET['id'])) {
		$id_paket = $_GET['qz'];
    }else{
		$id_paket = $infoQuiz['id_paket'];
    }

    $rest = $soalClass->updateSoal($_GET['id'], $infoMapel['id_kelas'], $soal, $jawaban, $benar, $id_paket, $_SESSION['lms_id']);

    // echo "string ".$rest['status'];
    if ($rest['status'] == "Sukses") {

        if (isset($_GET['md'])){
			echo "<script>
					swal({title : '".$rest['status']."', text: '".$rest['pesan']."', type: '".$rest['icon']."', allowOutsideClick: false, allowEscapeKey: false},
                    function(){
					 	window.location = 'quiz-action?act=update&md=".$_GET['md']."&qz=".$_GET['qz']."';
					});
				</script>";
        }elseif (isset($_GET['id'])) {
			echo "<script>alert('".$rest['status']."'); document.location='paket-detail?id=".$_GET['qz']."'</script>";
        }

    }else{
        echo "<script>
        		swal({title : '".$rest['status']."', text: '".$rest['pesan']."', type: '".$rest['icon']."'});
				window.location = 'quiz-action?act=update&md=".$_GET['md']."&qz=".$_GET['qz']."';
			</script>";
    }
}

if(isset($_POST['addQuiz'])){
	$soal 		= $_POST['soal'];
	$jawaban 	= $_POST['jawaban'];
	$benar 		= $_POST['benar'];
	$rest		= $soalClass->addSoal($infoMapel['id_kelas'], $soal,$jawaban,$benar,$infoQuiz['id_paket'], $_SESSION['lms_id']);

	// echo "<script>
	// 			swal({title : '$rest[hasil]', text: '$rest[pesan]', type: '$rest[icon]'});

	// 			window.location = 'quiz-action?act=update&md=$_GET[md]&qz=$_GET[qz]';

	// 		</script>";
	if ($rest['hasil'] == "Sukses") {

		if (isset($_GET['md'])){
			echo "<script>
					swal({title : '".$rest['hasil']."', text: '".$rest['pesan']."', type: '".$rest['icon']."', allowOutsideClick: false, allowEscapeKey: false},
					function(){
							window.location = 'quiz-action?act=update&md=".$_GET['md']."&qz=".$_GET['qz']."';
					});
				</script>";
		}elseif (isset($_GET['id'])) {
			echo "<script>alert('".$rest['hasil']."'); document.location='paket-detail?id=".$_GET['qz']."'</script>";
		}

	}else{
		echo "<script>
				swal({title : '".$rest['hasil']."', text: '".$rest['pesan']."', type: '".$rest['icon']."'});
				window.location = 'quiz-action?act=update&md=".$_GET['md']."&qz=".$_GET['qz']."';
			</script>";
	}
}

if(isset($_GET['act'])){
	?>
	<script type="text/javascript">document.title = "Kelola Soal Penilaian <?=$infoQuiz['nama']?> - seTARA daring";</script>
	<?php
}else{
	?>
	<script type="text/javascript">document.title = "Tambah Soal Penilaian <?=$infoQuiz['nama']?> - seTARA daring";</script>
	<?php
}
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
												<p class="title"><?=$_SESSION['lms_name']?></p>
												<p class="title"><?php if (isset($_GET['md'])) {?><u>Penilaian:</u> <?=$infoModul['nama']; }?></p>
												<p><?php if (isset($_GET['md'])) {?><u>Mata Pelajaran:</u> <?=$infoMapel['nama']; }?></p>
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
                        if (isset($_GET['act']) && isset($_GET['id']) && ($_GET['act'] == 'update')) {
                    ?>
                        <div class="card-block">
                            <h5 class="with-border" id="judul1">Perbarui Soal</h5>
                           <form id="form_tambah" method="POST">
                                <div class="modal-body opsitambahan">
                                    <fieldset class="form-group">
										<h4 class="semibold" for="exampleInput">Soal</h4>
										<textarea class ="myeditablediv" name="soal" ><?=$infoSoal['soal']?></textarea>
                                    </fieldset>
                                    <hr />
									<h4 class="semibold" for="exampleInput">Jawaban</h4>
                                    <?php
                                    $i = 1;
									$u = 0;
                                    foreach ($listJawaban as $jawaban) {
                                    ?>
                                    <fieldset class="form-group" id="pilihan<?=$i?>">
                                       <label class="form-label " for="exampleInput">Pilihan <?=$i;?></label>
                                        <textarea class ="myeditablediv" name="jawaban[]" ><?=$jawaban['text'];?></textarea>
                                        Atur Jawaban Benar <input type="radio" name="benar" value="<?=$u?>" <?php if($jawaban['status'] == "benar"){echo "checked";} ?>>
                                    </fieldset>
                                    <?php
										$i++;
										$u++;
                                    }
                                    ?>
                                </div>
								<a style="align:right;" class="btn btn-sm btn-primary" id="tambahopsi" onclick="tambahOpsi();">+ Tambah Pilihan</a>
								<a style="align:right;" class="btn btn-sm btn-danger" id="tambahopsi" onclick="hapusOpsi();"><i class="fa fa-trash"></i> Hapus Pilihan</a>
                                <div class="modal-footer">
                                    <button type="submit" name="updateQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
                                    <a href="quiz-action?act=update&md=<?=$_GET['md']?>&qz=<?=$_GET['qz']?>" type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Kembali</a>
                                </div>
                            </form>
                        </div>
                    <?php
                        }else {
							$u = 0;
                    ?>
						<div class="card-block">
						  <h5 class="with-border" id="judul1">Penambahan Soal</h5>
						  <form id="form_tambah" method="POST">
							<div class="modal-body opsitambahan">
							  <fieldset class="form-group">
								<h4 class="semibold" for="exampleInput">Soal</h4>
								<textarea class ="myeditablediv" id="soal" name="soal" ></textarea>
							  </fieldset>
							  <hr />
							  <h4 class="semibold" for="exampleInput">Jawaban</h4>
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
							</div>
							<a style="align:right;" class="btn btn-sm btn-primary" id="tambahopsi" onclick="tambahOpsi();">+ Tambah Pilihan</a>
							<a style="align:right;" class="btn btn-sm btn-danger" id="tambahopsi" onclick="hapusOpsi();"><i class="fa fa-trash"></i> Hapus Pilihan</a>
							<div class="modal-footer">
							  <button type="submit" name="addQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
							  <a href="quiz-action?act=update&md=<?=$_GET['md']?>&qz=<?=$_GET['qz']?>" type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Batal</a>
							</div>
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

		if($('#judul1').html() == 'Penambahan Soal'){
			var i = 3;
			var j = 2;
		}else{
			var i = <?=($u+1)?>;
			var j = <?=$u?>;
		}

		function tambahOpsi(){
			// js.src = "./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image";
			// js.src = "./assets/tinymce4/js/tinymce/tinymce.min.js";

			// $(".opsitambahan").append("<fieldset class='form-group'><label class='form-label' for='exampleInput'>Pilihan "+i+"</label><textarea class='myeditablediv' name='jawaban[]' ></textarea>Atur Jawaban Benar <input type='radio' name='benar' value='"+j+"'></fieldset>");
			$(".opsitambahan").append("<fieldset class='form-group' id='pilihan"+i+"'>"+
											"<label class='form-label' for='exampleInput'>Pilihan "+i+"</label>"+
											"<textarea class='myeditablediv' name='jawaban[]'></textarea>"+
											"Atur Jawaban Benar <input type='radio' name='benar' value='"+j+"'>"+
										"</fieldset>");
            tinymce.remove();
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
                  //"table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris eqneditor"
                  "table contextmenu directionality emoticons paste textcolor responsivefilemanager code eqneditor"
             ],
             toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
             //toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry eqneditor",
             toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | eqneditor",
             image_advtab: true,

             external_filemanager_path:"assets/filemanager/",
             filemanager_title:"Tata Kelola File"
            });

			i++;
			j++;
			window.scrollTo(0,document.body.scrollHeight);
		}

        $("#form_tambah").submit(function(e){
            $("#editor").val(tinyMCE.get('editormce').getContent());
        });

		$(document).ready(function() {
			$('.note-statusbar').hide();
		});

		function hapusOpsi(){
			if(j > '2'){
				var ab = '#pilihan'+j;
				// alert(ab);
				$(ab).remove();
				j--; i--;
			}else{
				swal({
					title: 'Maaf!',
					text: '2 Pilihan terakhir tidak dapat dihapus!',
					type: 'error'
				});
			}
		}

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
