<footer class="footer col-md-12">
	All rights reserved <b><a href='http://bindiktara.kemdikbud.go.id/'>Direktorat Pembinaan Pendidikan Keaksaraan dan Kesetaraan</a></b> &copy; 2019
</footer>
	<script src="../assets/js/lib/jquery/jquery.min.js"></script>
	<script src="../assets/js/lib/tether/tether.min.js"></script>
	<script src="../assets/js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="../assets/js/plugins.js"></script>
	<script src="../assets/js/lib/salvattore/salvattore.min.js"></script>
	<script src="../assets/js/lib/ion-range-slider/ion.rangeSlider.js"></script>

	<script type="text/javascript" src="../assets/js/lib/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../assets/js/lib/lobipanel/lobipanel.min.js"></script>
	<script type="text/javascript" src="../assets/js/lib/match-height/jquery.matchHeight.min.js"></script>
	<script type="text/javascript" src="../assets/js/loader.js"></script>
	<script src="../assets/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="../assets/js/lib/select2/select2.full.min.js"></script>
	<script src="../assets/js/lib/jquery-tag-editor/jquery.caret.min.js"></script>
	<script src="../assets/js/lib/jquery-tag-editor/jquery.tag-editor.min.js"></script>

	<script src="../assets/js/lib/fancybox/jquery.fancybox.pack.js"></script>
	<script src="../assets/js/lib/blockUI/jquery.blockUI.js"></script>
	<script>
		$(function() {
			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});
	</script>


	<script type="text/javascript">

		var pesanText = 'Anda sudah Keluar dari seTARA Daring';
		function signOut(){
			$.ajax({
				type: 'POST',
				url: '../url-API/authOut.php',
				data: {"action": "out", "u":"<?=$_SESSION['lms_id']?>"},
				success: function(res) {
					swal({
						title: 'Pemberitahuan!',
						text: pesanText,
						type: 'warning',
						allowEscapeKey: false
					}, function() {
						 window.location = './';
					});
				},
				error: function () {
					swal('Gagal!', 'Anda belum Keluar dari SIAJAR LMS!', 'warning');
				}
			});
		}

		function loading(){
			$('html').block({
				message: '<div class="blockui-default-message"><i class="fa fa-circle-o-notch fa-spin"></i><h6>Mohon tunggu...</h6></div>',
				overlayCSS:  {
					background: '#3ac9d6',
					opacity: 0.5,
					cursor: 'wait'
				},
				css: {
					width: '50%'
				},
				blockMsgClass: 'block-msg-default'
			});
		}

		function loaded(){
			$('html').unblock();
		}

		$(function() {
			$('#tags-editor-textarea').tagEditor();
		});
		$('#option').change(function() {
		    opt = $(this).val();
		    if (opt=="0") {
		        $('#data').html('<h2>Pilihan</h2>');
		    }else if (opt == "opt1") {
		        $('#data').html('<fieldset class="form-group"> <label class="form-label">Judul</label> <input type="text" class="form-control maxlength-simple" id="exampleInput" placeholder="Judul" > </fieldset>');
		    }else if (opt == "opt2") {
		        $('#data').html('<div class="box" style="padding-top: 5%;"> <input hidden="true" type="file" name="file-7[]" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple /> <label for="file-7"><span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label></div>');
		    }
		});
	</script>

	<script src="../assets/js/lib/datatables-net/datatables.js"></script>
	<script src="../assets/js/lib/datatables-net/rowGroups.js"></script>
