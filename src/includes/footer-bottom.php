<?php
?>
<script>
	$(window).load(function() {
	    writeLog('access', "<?=$_SESSION['lms_id']?>", "<?=$logIDKelas?>");
	});

	function writeLog(act, uid, kls){
	    $.ajax({
			type: 'POST',
			url: 'url-API/log.php',
			data: {"action": act, "uid":uid, "kls":kls, 'pg':document.title, 'url':document.URL, 'sekolah':"<?=$_SESSION['lms_sekolah']?>"},
			success: function(res) {
				console.log(res.message);
			},
			error: function () {
				console.log('Gagal menuliskan ke dalam Log! '+document.title);
			}
		});
	}
	
	$( document ).ready(function() {
		$('.wrs_tickContainer').remove();
	});

</script>

</body>
</html>
