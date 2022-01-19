<?php
    //header('Location: http://setara.kemdikbud.go.id/kesetaraan.php');
    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
        include 'setting/controller/' .$class . '.php';
    });
    $ClassUser = new User();
    $ClassKelas = new Kelas();
    $ClassMedia = new Media();
    $countguru = $ClassUser->CountGuru();
    $countsiswa = $ClassUser->CountSiswa();
    $countkelas = $ClassKelas->CountKelas();
    $countmedia = $ClassMedia->CountMedia();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>seTARA daring | DITBINDIKTARA</title>
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/edua-icons.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/animate.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/owl.carousel.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/owl.transitions.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/cubeportfolio.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/settings.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/bootsnav.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/style.css">
        <link rel="stylesheet" type="text/css" href="assets/css/lib/front/loader.css">
        <link rel="stylesheet" type="text/css" href="assets/css/separate/pages/login.css">
        <script src="assets/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
        <link rel="stylesheet" href="assets/css/lib/bootstrap-sweetalert/sweetalert.css">

        <link href="assets/img/favicon.ico" rel="shortcut icon">

        <style>
            *{
                font-family: 'Proxima Nova',sans-serif;
            }

            p{
                line-height: 150%;
            }

            .sign-box a{
                color: #29b7c4;
            }

            .errspan {
                float: right;
                margin-right: -25px;
                margin-top: -25px;
                position: relative;
                z-index: 2;
                color: red;
            }
        </style>

		<!-- Piwik -->
		<script type="text/javascript">
			var _paq = _paq || [];
			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function(){
				var u="//analytics.seamolec.org/";
				_paq.push(['setTrackerUrl', u+'piwik.php']);
				_paq.push(['setSiteId', '1']);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
				g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<!-- End Piwik Code -->

    </head>

    <body class="pushmenu-push">
        <a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
        <div class="loader">
            <div class="bouncybox">
                <div class="bouncy"></div>
            </div>
        </div>

        
 
        <header>
            <nav class="navbar navbar-default navbar-sticky bootsnav pushy">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="./">
                            <img src="assets/img/front/seTARAdaring.png" class="logo" alt="">
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <!--Search-->
        <!-- <div id="search">
            <button type="button" class="close">×</button>
            <form>
                <input type="search" value="" placeholder="Search here...."  required/>
                <button type="submit" class="btn btn_common yellow">Search</button>
            </form>
        </div> -->
        <!--FOOTER-->
        <footer class="padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 footer_panel bottom25">
                        <h3 class="heading bottom25" style="text-align: center;">Mohon Maaf, SETARA DARING sedang dalam proses <i>maintenace</i>.<span class="divider-left"></span></h3>
                 </div>
                </div>
                <div class="row copyright">
                    <div class="col-md-12 text-center">
                        <p>Copyright &copy; 2021 <a href="http://bindikmas.kemdikbud.go.id/bindiktara/" target="_blank">seTARA daring</a>. all rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
        <!--FOOTER ends-->

        <script src="assets/js/lib/front/jquery-2.2.3.js"></script>
        <script src="assets/js/lib/front/bootstrap.min.js"></script>
        <script src="assets/js/lib/front/bootsnav.js"></script>
        <script src="assets/js/lib/front/jquery.appear.js"></script>
        <script src="assets/js/lib/front/jquery-countTo.js"></script>
        <script src="assets/js/lib/front/jquery.parallax-1.1.3.js"></script>
        <script src="assets/js/lib/front/owl.carousel.min.js"></script>
        <script src="assets/js/lib/front/jquery.cubeportfolio.min.js"></script>
        <script src="assets/js/lib/front/jquery.themepunch.tools.min.js"></script>
        <script src="assets/js/lib/front/jquery.themepunch.revolution.min.js"></script>
        <script src="assets/js/lib/front/revolution.extension.layeranimation.min.js"></script>
        <script src="assets/js/lib/front/revolution.extension.navigation.min.js"></script>
        <script src="assets/js/lib/front/revolution.extension.parallax.min.js"></script>
        <script src="assets/js/lib/front/revolution.extension.slideanims.min.js"></script>
        <script src="assets/js/lib/front/revolution.extension.video.min.js"></script>
        <script src="assets/js/lib/front/wow.min.js"></script>
        <script src="assets/js/lib/front/functions.js"></script>
        <script src="assets/js/lib/front/bootstrap-show-password.js"></script>


        <script>
            $(function() {
                var register        = "siswa";
                var match_password  = false;

                $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var target = $(e.target).attr("href") // activated tab
                    if(target=="#menu1"){
                        register    = "siswa";
                        $("#kode_kelas").prop('required', true);
                        $("#nama_siswa").prop('required', true);
                        $("#username_siswa").prop('required', true);
                        $("#password_siswa").prop('required', true);
                        $("#nama_guru").prop('required', false);
                        $("#username_guru").prop('required', false);
                        $("#password_guru").prop('required', false);
                        $('#nama_guru').val('');
                        $('#username_guru').val('');
                        $('#password_guru').val('');
                        $('#re_password_guru').val('');
                        $('#icon_re_password_guru').html('');
                    }else{
                        register    = "guru";
                        $("#kode_kelas").prop('required', false);
                        $("#nama_siswa").prop('required', false);
                        $("#username_siswa").prop('required', false);
                        $("#password_siswa").prop('required', false);
                        $("#nama_guru").prop('required', true);
                        $("#username_guru").prop('required', true);
                        $("#password_guru").prop('required', true);
                        $('#kode_kelas').val('');
                        $('#nama_siswa').val('');
                        $('#username_siswa').val('');
                        $('#password_siswa').val('');
                        $('#re_password_siswa').val('');
                        $('#icon_re_password_siswa').html('');
                    }
                });

    			$('#form-login').submit(function() {
    				var fd = new FormData(this);
    				fd.append('action','login');
    				$.ajax({
          				type: 'POST',
          				url: 'url-API/auth.php',
          				data: fd,
          				contentType: false,
          				processData: false,
          				success: function(res){
    						swal({
                                title: res.response,
                                text: res.message,
                                type: res.icon
                            }, function() {
                                if (res.icon != 'error') {
                                    if(res.page == 'manage'){
                                        window.location = './manage';
                                    }else{
                                        window.location = './';
                                    }
                                }
                            });
          				},
          				error: function(){
                            swal({
                                title: res.response,
                                text: res.message,
                                type: res.icon
                            }, function() {
                                 window.location = './';
                            });
          				}
          			});
    			});

                $('#form-register').submit(function() {
                    if(match_password){
                        var fd = new FormData(this);
                        fd.append('status', register);
                        $.ajax({
                            type: 'POST',
                            url: 'url-API/auth-reg.php',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(res){
                                swal({
                                    title: res.response,
                                    text: res.message,
                                    type: res.icon
                                }, function() {
                                    if (res.icon != 'error') {
                                        window.location = './';
                                    }
                                });
                            },
                            error: function(){
                                swal({
                                    title: res.response,
                                    text: res.message,
                                    type: res.icon
                                }, function() {
                                     window.location = './';
                                });
                            }
                        });
                    }else{
                        swal({
                            title: 'Maaf!',
                            text: 'Konfirmasi password tidak sesuai',
                            type: 'warning'
                        });
                    }
                });

                $('.btn-cancel').click(function() {
                    $('#username').val('');
                    $('#password').val('');
                    $('#kode_kelas').val('');
                    $('#nama_siswa').val('');
                    $('#username_siswa').val('');
                    $('#password_siswa').val('');
                    $('#nama_guru').val('');
                    $('#username_guru').val('');
                    $('#password_guru').val('');
                });

                $( ".re_password_siswa" ).keyup(function( event ) {
                    re_password = $('#re_password_siswa').val();

                    if(re_password == $('#password_siswa').val()){
                        match_password = true;
                        $('#icon_re_password_siswa').html('<i class="fa fa-check errspan" aria-hidden="true" style="color: #3ac9d6"></i>');
                    }else{
                        match_password = false;
                        $('#icon_re_password_siswa').html('<i class="fa fa-times errspan" aria-hidden="true"></i>');
                    }
                });

                $( ".re_password_guru" ).keyup(function( event ) {
                    re_password = $('#re_password_guru').val();

                    if(re_password == $('#password_guru').val()){
                        match_password = true;
                        $('#icon_re_password_guru').html('<i class="fa fa-check errspan" aria-hidden="true" style="color: #3ac9d6"></i>');
                    }else{
                        match_password = false;
                        $('#icon_re_password_guru').html('<i class="fa fa-times errspan" aria-hidden="true"></i>');
                    }
                });
            });

            $(document).ready(function() {
                $("#owl-demo").owlCarousel({
                   autoPlay: 4000, //Set AutoPlay to 3 seconds
                   items : 4,
                   itemsDesktop : [1199,4],
                   itemsDesktopSmall : [979,4],
                   itemsTablet : [768,3],
                   itemsMobile : [479,1]
                });
            });
        </script>
    </body>
</html>
