<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$materiClass 	= new Materi();
$kelasClass		= new Kelas();
$diskusiClass	= new Diskusi();

$menuModul		= 5;
$infoModul		= $modulClass->getInfoModul($_GET['modul']);
$infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoMateri		= $materiClass->getTotalMateri($_GET['modul']);
$listTopik		= $diskusiClass->getListbyModul($infoModul['_id']);

if ($_SESSION['lms_status'] == 'superadmin' || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengawas' || $_SESSION['lms_status'] == 'kepsek' ) {
	# code...
	$hakKelas['status'] = 3;
	$hakKelas['posisi']	= 'Super Administrator';
}else{
	$hakKelas		= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
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
}
	

if(isset($_POST['addMateri']) || isset($_POST['updateMateri'])){


	if(isset($_POST['addMateri'])){
		$rest 	= $materiClass->addMateri($_GET['modul'], $_POST['judul'], $_POST['isi'], $_SESSION['lms_id']);
	}else{
		$rest 	= $materiClass->updateMateri($_GET['modul'], $_POST['judul'], $_POST['isi']);
	}

	if ($rest['status'] == "Success") {
		echo "<script>alert('".$rest['status']."'); document.location='materi.php?modul=".$rest['IDModul']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}
$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Diskusi Kegiatan Pembelajaran <?=$infoModul['nama']?> - seTARA daring";</script>

<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">

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
												<p><i class="font-icon font-icon-user"></i>&nbsp;&nbsp;<small><?=($hakKelas['posisi'] == 'Guru Mata Pelajaran'?'Tutor (Pengampu Mata Pelajaran)':($hakKelas['posisi'] == 'Tutor'?'Tutor (Pendamping)':$hakKelas['posisi']))?></small></p>
												<br><br><br><br><br>
												<p class="title"><?=$_SESSION['lms_name']?></p>
												<p class="title">Diskusi <?=$infoModul['nama']?></p>
												<p>Mata Pelajaran <?=$infoMapel['nama']?></p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><?=$listTopik['count'];?></p>
													<p>Topik Diskusi</p>
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
			<!-- <button type="button" class="change-cover">
				<i class="font-icon font-icon-picture-double"></i>
				Ganti sampul
				<input type="file"/>
			</button> -->
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4">
					<?php
						require("includes/modul-menu.php");
					?>
				</div>

				<div id="right-content" class="col-xl-9 col-lg-8" style="position: static;">

					<section class="tabs-section">
						<div class="tab-content no-styled profile-tabs">
							<form id="form-tambah-topik" class="box-typical" method="post" action="">
								<input type="text" class="write-something" name="topik" id="topik" placeholder="Topik Diskusi" title="Topik Diskusi" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan topik diskusi yang akan dibuat!" required/>
								<textarea class="write-something" name="textTopik" id="textTopik" placeholder="Apa yang ingin anda diskusikan?" title="Hal yang didiskusikan" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan apa yang akan ada didiskusikan!" style="min-height: 88px;" required></textarea>
								<div class="box-typical-footer">
									<div class="tbl">
										<div class="tbl-row">
											<div class="tbl-cell tbl-cell-action">
												<button type="submit" name="postingText" class="btn btn-rounded pull-right">Send</button>
											</div>
										</div>
									</div>
								</div>
							</form><!--.box-typical-->
					<?php
					foreach ($listTopik['data'] as $posting) {
						$image		= empty($posting['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$posting['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
						$listReply	= $diskusiClass->getListReply($posting['_id']);
					?>
						<article id="topik-<?=$posting['_id']?>" class="box-typical profile-post" style="border: 2px solid #d8e2e7">
							<div class="profile-post-header">
								<div class="user-card-row">
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-photo">
												<?=$image?>
										</div>
										<div class="tbl-cell">
											<div class="user-card-row-name"><?=$posting['user']?> &nbsp; <i class="fa fa-play" style="font-size: 70%; display: inline-block;"></i> &nbsp; <?=$posting['judul']?></div>
											<div class="color-blue-grey-lighter"><?=selisih_waktu($posting['date_created'])?></div>
										</div>
									</div>
								</div>
								<?php
								if ($_SESSION['lms_id'] == $posting['creator']) {
									echo '		<a class="shared" onclick="removePost(\''.$posting['_id'].'\', 1)" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Kiriman yang sudah dibuat.">
													<i class="font-icon font-icon-trash"></i>
												</a>';
								}
								?>
							</div>
							<div class="profile-post-content">
								<p>
									<?=nl2br($posting['deskripsi'])?>
								</p>
							</div>
							<?php

							?>
							<div class="box-typical-footer profile-post-meta">
								<a href="#demo<?=$posting['_id']?>" data-toggle="collapse" data-parent="#accordion" class="meta-item">
									<text id="jumlah-comment-<?=$posting['_id']?>"><?=$listReply['count']?></text> Comment
								</a></text>
								<a href="" onclick="return writeReply('<?=$posting['_id']?>');" class="meta-item">
									<i class="font-icon font-icon-comment"></i>
									Komentari
								</a>
							</div>
							<div class="comment-rows-container" style="position: static;background-color: #ecf2f5; max-height: none;">
								<div id="demo<?=$posting['_id']?>" class="collapse">
								<?php
									foreach ($listReply['data'] as $reply) {
										$image				= empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
										$listCommentReply	= $diskusiClass->getListReply($reply['_id']);
								?>
								<div class="comment-row-item">
									<div class="tbl-row">
										<div class="avatar-preview avatar-preview-32">
											<a href="#"><?=$image?></a>
										</div>
										<div class="tbl-cell comment-row-item-header">
											<div class="user-card-row-name" style="font-weight: 600"><?=$reply['user']?></div>
											<div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($reply['date_created'])?></div>
										</div>
										<?php
											if ($_SESSION['lms_id'] == $reply['creator']) {
												echo '		<a style="right: 20px; position: absolute" onclick="removePost(\''.$reply['_id'].'\', 2)" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																<i class="font-icon font-icon-trash"></i>
															</a>';
											}
										?>
									</div>
									<div class="comment-row-item-content" style="margin-top: 5px;">
										<p><?=$reply['deskripsi']?></p>
										<div class="comment-row-item-box-typical-footer profile-post-meta" style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
											<a href="#demo<?=$reply['_id']?>" data-toggle="collapse" data-parent="#accordion" class="meta-item" style="font-size: .875rem">
												<text id="jumlah-comment-reply-<?=$reply['_id']?>"><?=$listCommentReply['count']?></text> Comment
											</a>
											<a href="" onclick="return writeCommentReply('<?=$reply['_id']?>');" class="meta-item" style="font-size: .875rem">
												<i class="font-icon font-icon-comment"></i>
												Komentari
											</a>
										</div>
									</div>
									<div id="demo<?=$reply['_id']?>"  class="collapse">
									<?php
									foreach ($listCommentReply['data'] as $commentReply) {
										$image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
									?>
									<div class="comment-row-item quote" style="padding-right: 45px;">
										<div class="tbl-row">
											<div class="avatar-preview avatar-preview-32">
												<a href="#"><?=$image?></a>
											</div>
											<div class="tbl-cell comment-row-item-header">
												<div class="user-card-row-name" style="font-weight: 600"><?=$commentReply['user']?></div>
												<div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($commentReply['date_created'])?></div>
											</div>
											<?php
											if ($_SESSION['lms_id'] == $commentReply['creator']) {
												echo '		<a style="right: 50px; position: absolute" onclick="removePost(\''.$commentReply['_id'].'\', 2)" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																<i class="font-icon font-icon-trash"></i>
															</a>';
											}
											?>
										</div>
										<div class="comment-row-item-content" style="margin-top: 5px;">
											<p><?=$commentReply['deskripsi']?></p>
										</div>
									</div><!--.comment-row-item-->
									<?php } ?>
									</div>
								</div><!--.comment-row-item-->
								<div class="comment-row-item" id="for-comment-reply-<?=$reply['_id']?>" style="padding-right: 60px;">

								</div>
								<?php
								}
								?>
							</div>
							<input id="write-reply-<?=$posting['_id']?>" onfocus="setReplyComment('<?=$posting['_id']?>');" type="text" class="write-something comment" placeholder="Tuliskan komentar disini"/>
							</div><!--.comment-rows-container-->
						</article>
			<?php
					}
			?>
						</div><!--.tab-content-->
					</section><!--.tabs-section-->
				</div><!--.col-->
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script src="assets/js/lib/autoresize/autoresize-textarea.js"></script>

	<script>

		var id_reply;
		var komentar;

		$(document).ready(function() {

			$('#textTopik').autoResize();

			$('#form-tambah-topik').on('submit', function( event ) {
				loading();

				$.ajax({
					type: 'POST',
					url: 'url-API/Kelas/Modul/Diskusi/',
					data: {
						"action": "insert-topik",
						"id_modul": '<?=$infoModul['_id']?>',
						"judul": $('#topik').val(),
						"deskripsi": $('#textTopik').val(),
						"kelas": '<?=$infoMapel['id_kelas']?>',
						"creator": '<?=$_SESSION['lms_id']?>'
					},
					success: function(html) {
						$('#right-content').html(html);
						$('html, body').animate({ scrollTop: $('.new').offset().top }, 'slow');
						loaded();
					},
					error: function () {
						swal("Gagal!", "Diskusi gagal ditambahkan!", "error");
					}
				});

				event.preventDefault();
			});

			$(document).on('keyup', '.comment',function( event ) {

				if (event.keyCode == '13'){
					loading();
				    komentar = $(this).val();

					$.ajax({
					    type	: 'POST',
					    url		: 'url-API/Kelas/Modul/Diskusi/',
					    data	: {	"action": "insert-reply",
									"id_reply": id_reply,
									"komentar": komentar,
									"kelas": '<?=$infoMapel['id_kelas']?>',
									"creator": '<?=$_SESSION['lms_id']?>'
								},
					    success	: function(html) {
									$('#write-reply-'+id_reply).val('');
									$('#write-reply-'+id_reply).blur();

									$('#demo'+id_reply).html(html);
									$('#demo'+id_reply).collapse('show');
									$('#jumlah-comment-'+id_reply).html($('#new-jumlah-comment-'+id_reply).text());

									$('html, body').animate({ scrollTop: $('.new').offset().top-100 }, 'slow');
									loaded();
					   			},
					    error: function () {
					        swal("Gagal!", "Data tidak terhapus!", "error");
					    		}
					});
				}
			});

			$(document).on('keyup', '.comment-reply',function( event ) {

				if (event.keyCode == '13'){
					loading();
					komentar = $(this).val();

					$.ajax({
						type	: 'POST',
						url		: 'url-API/Kelas/Modul/Diskusi/',
						data	: {	"action": "insert-comment-reply",
									"id_reply": id_reply,
									"komentar": komentar,
									"kelas": '<?=$infoMapel['id_kelas']?>',
									"creator": '<?=$_SESSION['lms_id']?>'
								},
						success	: function(html) {
									$('#write-comment-reply-'+id_reply).val('');
									$('#write-comment-reply-'+id_reply).blur();

									$('#demo'+id_reply).html(html);
									$('#demo'+id_reply).collapse('show');
									$('#jumlah-comment-reply-'+id_reply).html($('#new-jumlah-comment-reply-'+id_reply).text());

									$('html, body').animate({ scrollTop: $('.new-comment-reply').offset().top-100 }, 'slow');
									loaded();
								},
						error: function () {
							swal("Gagal!", "Data tidak terhapus!", "error");
								}
					});
				}
			});
		});

		function writeReply(idPosting){

			$('#write-reply-'+idPosting).focus();

			return false;
		}

		function writeCommentReply(idReply){

			if ($('#write-comment-reply-'+idReply).length == 0){

				$('#for-comment-reply-'+idReply).append('<input id="write-comment-reply-'+idReply+'" onfocus="setReplyComment(\''+idReply+'\');" type="text" class="write-something comment-reply" placeholder="Tuliskan komentar disini"/>');
			}

			$('#write-comment-reply-'+idReply).focus();

			return false;
		}

		function setReplyComment(idReply){

			id_reply = idReply;
		}

		function removePost(ID, tipe){
			swal({
			  title: "Apakah anda yakin untuk menghapus?",
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
					url: 'url-API/Kelas/Modul/Diskusi/',
					data: {"action": "remv", "ID": ID, "tipe":tipe, "user": "<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
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
	<script type="text/javascript" src="./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>


<?php
	require('includes/footer-bottom.php');
?>
