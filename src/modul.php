<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$materiClass 	= new Materi();
$kelasClass		= new Kelas();

$menuModul		= 2;
$infoModul		= $modulClass->getInfoModul($_GET['modul']);
$infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoMateri		= $materiClass->getTotalMateri($_GET['modul']);

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
		echo "<script>alert('".$rest['status']."'); document.location='materi?modul=".$rest['IDModul']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}
$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Materi Kegiatan Pembelajaran <?=$infoModul['nama']?> - seTARA daring";</script>

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
												<p class="title"><u>Materi:</u> <?=$infoModul['nama']?></p>
												<p><u>Mata Pelajaran:</u> <?=$infoMapel['nama']?></p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><?=$infoMateri->count();?></p>
													<p>Materi</p>
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

				<div class="col-xl-9 col-lg-8">
					<section class="card card-inversed">
						<header class="card-header">
							Kumpulan Materi

							<?php
								// if($infoModul['creator'] == $_SESSION['lms_id']){
								if($hakKelas['status'] == 1 || $infoMapel['creator'] == $_SESSION['lms_id']){
									echo '<div class="btn-group" style="float: right;">
										<a href="materi-action?modul='.$infoModul['_id'].'" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan materi baru." class="btn btn-sm btn-rounded">+ Tambah Materi</a>
									</div>';
								}
							?>

						</header>
						<div class="tab-content no-styled profile-tabs card-block" id="accordion">

				<?php
					$no	= 1;
					if ($infoMateri->count() > 0) {
						foreach ($infoMateri as $materi) {
							$listReply	= $materiClass->getComment($materi['_id']);

							// if ($_SESSION['lms_id'] == $materi['creator']) {
							if ($hakKelas['status'] == 1 || $infoMapel['creator'] == $_SESSION['lms_id']) {
							echo '<article class="box-typical profile-post panel">
									<div class="profile-post-header">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-photo">
													<a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">
														<img src="assets/img/open-book.png" alt="">
													</a>
												</div>
												<div class="tbl-cell">
													<div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$no.'. '.$materi['judul'].'</a></div>
													<div class="color-blue-grey-lighter">'.($materi['date_created'] == $materi['date_modified'] ? "" : "Diperbarui ").selisih_waktu($materi['date_modified']).'</div>
												</div>
												<div class="tbl-cell" align="right">
													<span class="label label-'.($materi['status'] == "publish" ? "success" : "primary").'" style="margin-right: 20px">'.ucfirst($materi['status']).'</span>
													<a href="materi-action?act=update&modul='.$infoModul['_id'].'&materi='.$materi['_id'].'" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Materi yang sudah dibuat." style="right: 35px">
														<i class="font-icon font-icon-pencil")"></i>
													</a>
													<a onclick="remove(\''.$materi['_id'].'\')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Materi yang sudah dibuat.">
														<i class="font-icon font-icon-trash")"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
									<div id="demo'.$no.'" class="profile-post-content collapse">
										'.$materi["isi"].'
									</div>
									<div class="box-typical-footer profile-post-meta" id="accordion1">
										<a href="#demo'.$materi['_id'].'" data-toggle="collapse" data-parent="#accordion1" class="meta-item">
											<text id="jumlah-comment-'.$materi['_id'].'">'.$listReply['count'].'</text> Komentar
										</a></text>
										<a onclick="return writeReply(\''.$materi['_id'].'\');" class="meta-item">
											<i class="font-icon font-icon-comment"></i>
											Komentari
										</a>
									</div>
									<div class="comment-rows-container" style="position: static;background-color: #ecf2f5; max-height: none;">
										<div id="demo'.$materi['_id'].'" class="collapse">';
											foreach ($listReply['data'] as $reply) {
												$image				= empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
												$listCommentReply	= $materiClass->getCommentReply($reply['_id']);

												echo '<div class="comment-row-item" style="padding-bottom: 0px;">
														<div class="tbl-row">
															<div class="avatar-preview avatar-preview-32">
																<a href="#">'.$image.'</a>
															</div>
															<div class="tbl-cell comment-row-item-header">
																<div class="user-card-row-name" style="font-weight: 600">'.$reply['user'].'</div>
																<div class="color-blue-grey-lighter" style="font-size: .875rem">'.selisih_waktu($reply['date_created']).'</div>
															</div>';

												if ($_SESSION['lms_id'] == $reply['creator']) {
													echo '		<a style="right: 20px; position: absolute" onclick="removePost(\''.$reply['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																	<i class="font-icon font-icon-trash"></i>
																</a>';
												}

												echo '	</div>
														<div class="comment-row-item-content" style="margin-top: 5px;">
															<p>'.$reply['deskripsi'].'</p>
															<div class="comment-row-item-box-typical-footer profile-post-meta" id="accordion2" style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
																<a href="#demo'.$reply['_id'].'" data-toggle="collapse" data-parent="#accordion2" class="meta-item" style="font-size: .875rem">
																	<text id="jumlah-comment-reply-'.$reply['_id'].'">'.$listCommentReply['count'].'</text> Balasan
																</a>
																<a onclick="return writeCommentReply(\''.$reply['_id'].'\');" class="meta-item" style="font-size: .875rem">
																	<i class="font-icon font-icon-comment"></i>
																	Balas
																</a>
															</div>
														</div>
														<div id="demo'.$reply['_id'].'"  class="collapse">';

												foreach ($listCommentReply['data'] as $commentReply) {
													$image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />";
													echo '	<div class="comment-row-item quote" style="padding-right: 45px;">
																<div class="tbl-row">
																	<div class="avatar-preview avatar-preview-32">
																		<a href="#">'.$image.'</a>
																	</div>
																	<div class="tbl-cell comment-row-item-header">
																		<div class="user-card-row-name" style="font-weight: 600">'.$commentReply['user'].'</div>
																		<div class="color-blue-grey-lighter" style="font-size: .875rem">'.selisih_waktu($commentReply['date_created']).'</div>
																	</div>';

																if ($_SESSION['lms_id'] == $commentReply['creator']) {
																	echo '		<a style="right: 50px; position: absolute" onclick="removePost(\''.$commentReply['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																					<i class="font-icon font-icon-trash"></i>
																				</a>';
																}
													echo
															'	</div>
																<div class="comment-row-item-content" style="margin-top: 5px;">
																	<p>'.$commentReply['deskripsi'].'</p>
																</div>
															</div><!--.comment-row-item-->';
													}
												echo '	</div>
												</div><!--.comment-row-item-->
												<div class="comment-row-item" id="for-comment-reply-'.$reply['_id'].'" style="padding-right: 60px; padding-top: 5px; border-bottom: 1px solid #ccc; min-height:1px;">

												</div>';
											}
							echo		'</div>
									<input id="write-reply-'.$materi['_id'].'" onfocus="setReply(\''.$materi['_id'].'\');" type="text" class="write-something comment" placeholder="Tuliskan komentar disini"/>
									</div>
								</article>';
							}else {
								if($materi['status'] != 'draft'){
								echo '<article class="box-typical profile-post panel">
										<div class="profile-post-header">
											<div class="user-card-row">
												<div class="tbl-row">
													<div class="tbl-cell tbl-cell-photo">
														<a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">
															<img src="assets/img/open-book.png" alt="">
														</a>
													</div>
													<div class="tbl-cell">
														<div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$materi['judul'].'</a></div>
														<div class="color-blue-grey-lighter">'.($materi['date_created'] == $materi['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($materi['date_modified']).'</div>
													</div>
												</div>
											</div>
										</div>
										<div id="demo'.$no.'" class="profile-post-content collapse">
											'.$materi["isi"].'
										</div>

										<div class="box-typical-footer profile-post-meta" id="accordion1">
											<a href="#demo'.$materi['_id'].'" data-toggle="collapse" data-parent="#accordion1" class="meta-item">
												<text id="jumlah-comment-'.$materi['_id'].'">'.$listReply['count'].'</text> Komentar
											</a></text>
											<a onclick="return writeReply(\''.$materi['_id'].'\');" class="meta-item">
												<i class="font-icon font-icon-comment"></i>
												Komentari
											</a>
										</div>
										<div class="comment-rows-container" style="position: static;background-color: #ecf2f5; max-height: none;">
											<div id="demo'.$materi['_id'].'" class="collapse">';
												foreach ($listReply['data'] as $reply) {
													$image				= empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
													$listCommentReply	= $materiClass->getCommentReply($reply['_id']);

													echo '<div class="comment-row-item" style="padding-bottom: 0px;">
															<div class="tbl-row">
																<div class="avatar-preview avatar-preview-32">
																	<a href="#">'.$image.'</a>
																</div>
																<div class="tbl-cell comment-row-item-header">
																	<div class="user-card-row-name" style="font-weight: 600">'.$reply['user'].'</div>
																	<div class="color-blue-grey-lighter" style="font-size: .875rem">'.selisih_waktu($reply['date_created']).'</div>
																</div>';

													if ($_SESSION['lms_id'] == $reply['creator']) {
														echo '		<a style="right: 20px; position: absolute" onclick="removePost(\''.$reply['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																		<i class="font-icon font-icon-trash"></i>
																	</a>';
													}

													echo '	</div>
															<div class="comment-row-item-content" style="margin-top: 5px;">
																<p>'.$reply['deskripsi'].'</p>
																<div class="comment-row-item-box-typical-footer profile-post-meta" id="accordion2" style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
																	<a href="#demo'.$reply['_id'].'" data-toggle="collapse" data-parent="#accordion2" class="meta-item" style="font-size: .875rem">
																		<text id="jumlah-comment-reply-'.$reply['_id'].'">'.$listCommentReply['count'].'</text> Balasan
																	</a>
																	<a onclick="return writeCommentReply(\''.$reply['_id'].'\');" class="meta-item" style="font-size: .875rem">
																		<i class="font-icon font-icon-comment"></i>
																		Balas
																	</a>
																</div>
															</div>
															<div id="demo'.$reply['_id'].'"  class="collapse">';

													foreach ($listCommentReply['data'] as $commentReply) {
														$image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />";
														echo '	<div class="comment-row-item quote" style="padding-right: 45px;">
																	<div class="tbl-row">
																		<div class="avatar-preview avatar-preview-32">
																			<a href="#">'.$image.'</a>
																		</div>
																		<div class="tbl-cell comment-row-item-header">
																			<div class="user-card-row-name" style="font-weight: 600">'.$commentReply['user'].'</div>
																			<div class="color-blue-grey-lighter" style="font-size: .875rem">'.selisih_waktu($commentReply['date_created']).'</div>
																		</div>';

																	if ($_SESSION['lms_id'] == $commentReply['creator']) {
																		echo '		<a style="right: 50px; position: absolute" onclick="removePost(\''.$commentReply['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus komentar.">
																						<i class="font-icon font-icon-trash"></i>
																					</a>';
																	}
														echo
																'	</div>
																	<div class="comment-row-item-content" style="margin-top: 5px;">
																		<p>'.$commentReply['deskripsi'].'</p>
																	</div>
																</div><!--.comment-row-item-->';
														}
													echo '	</div>
													</div><!--.comment-row-item-->
													<div class="comment-row-item" id="for-comment-reply-'.$reply['_id'].'" style="padding-right: 60px; padding-top: 5px; border-bottom: 1px solid #ccc; min-height:1px;">

													</div>';
												}
								echo		'</div>
										<input id="write-reply-'.$materi['_id'].'" onfocus="setReply(\''.$materi['_id'].'\');" type="text" class="write-something comment" placeholder="Tuliskan komentar disini"/>
										</div>
									</article>';
								}
							}
							$no++;
						}
					}else {
						echo '	<article class="box-typical profile-post">
									<div class="add-customers-screen tbl">
										<div class="add-customers-screen-in">
											<div class="add-customers-screen-user">
												<i class="fa fa-book"></i>
											</div>
											<h2>Materi Kosong</h2>
											<p class="lead color-blue-grey-lighter">Belum ada materi  yang tersedia saat ini.</p>
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
	<script src="assets/js/lib/autoresize/autoresize-textarea.js"></script>
	<script>
		var id_reply;
		var id_materi;
		var komentar;

		// Komentar Awal
		function setReply(idMateri){
			id_materi = idMateri;
		}

		function writeReply(idPosting){
			$('#write-reply-'+idPosting).focus();

			return false;
		}

		// Cabang Komentar
		function setReplyComment(idReply){
			id_reply = idReply;
		}

		function writeCommentReply(idReply){

			if ($('#write-comment-reply-'+idReply).length == 0){

				$('#for-comment-reply-'+idReply).append('<input style="border: 1px solid #ddd;" id="write-comment-reply-'+idReply+'" onfocus="setReplyComment(\''+idReply+'\');" type="text" class="write-something comment-reply" placeholder="Tuliskan komentar disini"/>');
			}

			$('#write-comment-reply-'+idReply).focus();

			return false;
		}


		$(document).ready(function() {
			$('#textTopik').autoResize();
			$('.note-statusbar').hide();

			$(document).on('keyup', '.comment',function( event ) {
				if (event.keyCode == '13'){
					loading();
				    komentar = $(this).val();

					$.ajax({
					    type	: 'POST',
					    url		: 'url-API/Kelas/Modul/Materi/',
					    data	: {	"action": "insert-reply",
									"id_materi": id_materi,
									"komentar": komentar,
									"kelas"	: "<?=$infoMapel['id_kelas']?>",
									"creator": '<?=$_SESSION['lms_id']?>'
								},
					    success	: function(html) {
									$('#write-reply-'+id_materi).val('');
									$('#write-reply-'+id_materi).blur();

									$('#demo'+id_materi).html(html);
									$('#demo'+id_materi).collapse('show');
									$('#jumlah-comment-'+id_materi).html($('#new-jumlah-comment-'+id_materi).text());

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
						url		: 'url-API/Kelas/Modul/Materi/',
						data	: {	"action": "insert-comment-reply",
									"id_reply": id_reply,
									"komentar": komentar,
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
      				data: {"action":"remv", "ID":ID, "user":"<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
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

      	function removePost(ID){
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
					url: 'url-API/Kelas/Modul/Materi/',
					data: {"action": "remvPost", "ID": ID, "user":"<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
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
