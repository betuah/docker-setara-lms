<?php

    if($_SERVER['SERVER_NAME'] == 'lms.sikk.sch.id'){
        header('Location: http://lms.sikk.sch.id/sikk');
    }

    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
        include 'setting/controller/' .$class . '.php';
    });
    $ClassUser = new User();
    $ClassKelas = new Kelas();
    $ClassSekolah = new Sekolah();

    $countSekolah = $ClassSekolah->CountSekolah();
    $countUser  = $ClassUser->CountUserByProgram("Paket A")+$ClassUser->CountUserByProgram("Paket B")+$ClassUser->CountUserByProgram("Paket C");
    $countguru = $ClassUser->CountGuruByProgram("Paket A")+$ClassUser->CountGuruByProgram("Paket B")+$ClassUser->CountGuruByProgram("Paket C");
    $countsiswa = $ClassUser->CountSiswaByProgram("Paket A")+$ClassUser->CountSiswaByProgram("Paket B")+$ClassUser->CountSiswaByProgram("Paket C");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>seTARA daring | Dit.PMPK</title>
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
		 <!-- <script type="text/javascript">
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
		</script>-->
		<!-- End Piwik Code -->
<!-- Matomo -->
<!-- <script type="text/javascript">
    var _paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="https://analytics.seamolec.org/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '4']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
</script> -->
<!-- End Matomo Code -->

    </head>

    <body class="pushmenu-push">
        <a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
        <div class="loader">
            <div class="bouncybox">
                <div class="bouncy"></div>
            </div>
        </div>

        <div class="modal fade"
            id="pengajuan"
            tabindex="-1"
            role="dialog"
            aria-labelledby="pengajuanLabel"
            aria-hidden="true"
            data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog modal-lg" style="width: 350px;" role="document">
                <div class="modal-content">
                    <form method='POST' class="sign-box" id="form-pengajuan" autocomplete="off" onsubmit="return false;">
                    <div style="display: flex">
                        <div class="sign-avatar">
                            <img src="assets/img/kemendikbud.png" style="border-radius: 0px !important;" alt="">
                        </div>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title text-center">seTARA daring</h4>
                        <h4 class="text-center" style="margin: 10px 0;">Pengajuan Penyelenggaraan</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="npsn" style="font-size: 13px;">NPSN Satuan Pendidikan</label>
                            <input type="hidden" class="form-control" name="action" id="action" value="verification" />
                            <input type="text" class="form-control" name="npsn" id="npsn" pattern="([A-Z0-9]){6,}" placeholder="cth: P90xxxxxxxx" required title="Hanya dapat diisi menggunakan huruf kapital dan angka!" />
                        </div>
                        <!-- <div class="form-group">
                            <label for="email" style="font-size: 13px;">Email Satuan Pendidikan</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="cth: PKBMxxx@xxxxx.com" required />
                        </div>
                        <div class="form-group">
                            <label for="nama_pengelola" style="font-size: 13px;">Nama Pengelola di Satuan Pendidikan</label>
                            <input type="text" class="form-control" name="nama_pengelola" id="nama_pengelola" placeholder="cth: Pansera Oktasedu" pattern="([A-Za-z]){3,}" title="Hanya dapat diisi menggunakan huruf!" required />
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-primary pull-right" name="kirimPengajuan" value="send">Kirim Pengajuan</button>
                        <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal"  onclick="clear();">Batal</button>
                    </div>
                    </form>
                </div>
            </div>
        </div><!--.modal-->

        <div class="modal fade"
            id="register"
            tabindex="-1"
            role="dialog"
            aria-labelledby="registerLabel"
            aria-hidden="true"
            data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog" style="width: 350px;" role="document">
                <div class="modal-content">
                    <form method='POST' class="sign-box" id="form-register" onsubmit="return false;">
                    <div style="display: flex">
                        <div class="sign-avatar">
                            <img src="assets/img/kemendikbud.png" style="border-radius: 0px !important;" alt="">
                        </div>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Pengajuan Penyelenggaraan Program seTARA daring</h4>
                    </div>
                    <div class="modal-body">
                        <ol type="1" style="font-size:14px;">
                            <li>Buka alamat <a href="http://bit.ly/pengajuan_daring" target="_blank">http://bit.ly/pengajuan_daring</a></li>
                            <li>Isi form pengajuan dengan melengkapi data yang diberikan</li>
                            <li>Silakan tunggu konfirmasi hasil pengajuan melalui email yang digunakan pada saat sebelum melakukan pengisian form pengajuan</li>
                        </ol>
                        <font color="red" style="font-size:10px;">Notes: Pengajuan akan diproses paling cepat 1 X 24 jam</font>
                        <br />
                        <div class="tab-content">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal"  onclick="clear();">Tutup</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div><!--.modal-->

        <div class="modal fade"
            id="notify"
            tabindex="-1"
            role="dialog"
            aria-labelledby="notifyLabel"
            aria-hidden="true"
            data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method='POST' class="sign-box" style="max-width:100%;" onsubmit="return false;">
                    <div style="display: flex">
                        <div class="sign-avatar">
                            <img src="assets/img/kemendikbud.png" style="border-radius: 0px !important;" alt="">
                        </div>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><b><font color="red">PERHATIAN!!!</font><b></h4>
                    </div>
                    <div class="modal-body">
                        <p align="center">
                            <font color="red">Pada hari Rabu 23 September 2020, akan ada <i>maintenance</i> peningkatan kapasitas <i>storage</i> oleh PUSDATIN KEMENDIKBUD.</font>
                            <hr />
                            Jika Anda memiliki jadwal pembelajaran daring menggunakan setara daring pada jam tersebut dimohon:
                            <ol type="1" style="font-size:14px;">
                                <li>melakukan penjadwalan ulang pembelajaran daring Anda</li>
                                <li>menginformasikannya kepada peserta didik Anda</li>
                            </ol>
                        </p>
                        <br />
                        <div class="tab-content">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal"  onclick="clear();">Tutup</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div><!--.modal-->

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
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOut">
                            <li><a href="http://bit.ly/setara_daring" target="_blank"><i class="fa fa-book" aria-hidden="true"></i> &nbsp;&nbsp;Panduan Penggunaan</a>
                            </li>
                            <li><a href="#" data-toggle="modal" data-target="#pengajuan"><i class="fa fa-check-square-o" aria-hidden="true"></i> &nbsp;&nbsp;Pengajuan Penyelenggaraan</a>
                            </li>
                        </ul>
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

        <!--Text Banner-->
        <section class="" id="text_rotator_parent">
            <div id="text_rotator" class="owl-carousel">
                <div class="item" style="background:url(assets/img/front/BannerKesetaraan.png) no-repeat; background-size: cover;">
                    <div class="rotate_caption text-center">
                        <h1 style="opacity: 1.0;">&nbsp;</h1>
                        <p style="opacity: 1.0;">&nbsp;<br><b>&nbsp;</b>&nbsp;</p>
                        <br>
                        <br>
                        <a href="http://bit.ly/setara_daring" target="_blank" class="border_radius btn_common yellow" style="border-color: white;"><i class="fa fa-book" aria-hidden="true"></i> &nbsp;&nbsp;Panduan Penggunaan</a>
                        <a href="#" data-toggle="modal" data-target="#register" class="border_radius btn_common yellow" style="border-color: white;"><i class="fa fa-check-square-o" aria-hidden="true"></i> &nbsp;&nbsp;Pengajuan Penyelenggaraan</a>
                    </div>
                </div>
                <!-- <div class="item" style="background:url(assets/img/front/paket-A.png) no-repeat; background-size: cover;">
                    <div class="rotate_caption text-center">
                        <h1>Selamat datang di SIAJAR</h1>
                        <p>Cara paling aman dan mudah untuk menghubungkan guru dan siswa dalam pembelajaran<br>SMA Terbuka dan SMK PJJ (Pendidikan Jarak Jauh).</p>
                        <br>
                        <br>
                        <br>
                        <a href="#" data-toggle="modal" data-target="#register" class="border_radius btn_common yellow">Buat akun</a>
                        <a href="#" data-toggle="modal" data-target="#login" class="border_radius btn_common yellow">Masuk</a>
                    </div>
                </div> -->
            </div>
        </section>
        <!--Text Banner ends-->

        <section style="-moz-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); -o-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); -webkit-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); box-shadow: 0 3px 3px -3px rgba(0,0,0,.35);">
            <div class="text-center">
                <div style="width: 600px; margin:0 auto; padding: 10px; background: #3ac9d6; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; font-family: 'museo_slab700'; color: #fff; font-weight: normal;">Untuk melanjutkan, silakan pilih program yang diikuti:</div>
            </div>
            <!--ABout US-->
            <section id="about" class="">
                <div class="container margin_top">
                    <div class="row">
                        <div class="icon_wrap padding-bottom-half clearfix">
                            <a href="paket-a" target="_blank">
                                <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="300ms">
                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                    <h4 class="text-capitalize bottom20 margin10">Paket A</h4>
                                    <p class="no_bottom" style="text-align: justify;">
                                    </p>
                                </div>
                            </a>
                            <a href="paket-b" target="_blank">
                                <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="400ms">
                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                    <h4 class="text-capitalize bottom20 margin10">Paket B</h4>
                                    <p class="no_bottom" style="text-align: justify;">
                                    </p>
                                </div>
                            </a>
                            <a href="paket-c" target="_blank">
                                <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="500ms">
                                    <i class="fa fa-laptop"></i>
                                    <h4 class="text-capitalize bottom20 margin10">Paket C</h4>
                                    <p class="no_bottom" style="text-align: justify;" >
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <!--ABout US-->
        </section>

        <!--ABout US-->
        <!-- <section id="about" class="padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 priorty wow fadeInLeft">
                        <h2 class="heading bottom25">Tutorial Penggunaan seTARA daring Untuk Tutor<span class="divider-left"></span></h2>
                        <p class="half_space text-justify">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/fzU2Qcu9q5g?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            <hr />
                            <iframe src="http://sumberbelajar.seamolec.org/Media/Dokumen/5acb1a65865eac2e63321ca4/d637e8157fd3a727e854276d4450b743.pdf" width="100%" height="600px"></iframe>
                            <hr />
                        </p>
                    </div>
                    <div class="col-md-6 col-sm-6 wow fadeInRight">
                        <h2 class="heading bottom25">Tutorial Penggunaan seTARA daring Untuk Peserta Didik<span class="divider-left"></span></h2>
                        <p class="half_space text-justify">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/7w3sJdvBqqk?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            <hr />
                            <iframe src="http://sumberbelajar.seamolec.org/Media/Dokumen/5acb1a65865eac2e63321ca4/4df8b21e0010ecc01f0b20318a41e115.pdf" width="100%" height="600px"></iframe>
                           <hr />
                        </p>
                    </div>
                </div>
            </div>
        </section> -->
        <!--ABout US-->

        <!--Fun Facts-->
        <section id="counter" class="parallax padding" style="background: #6cecf9">
            <div class="container">
                <h2 class="hidden">hidden</h2>
                <div class="row number-counters">
                    <div class="col-md-3 col-sm-6 col-xs-6 counters-item text-center wow fadeInUp" data-wow-delay="600ms">
                        <i class="fa fa-building" aria-hidden="true"></i>
                        <strong data-to="<?php echo $countSekolah ;?>">0</strong>
                        <p>Satuan Pendidikan</p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 counters-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <strong data-to="<?php echo $countUser ;?>">0</strong>
                        <p>Jumlah Pengguna </p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 counters-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <i class="fa fa-user-secret" aria-hidden="true"></i>
                        <strong data-to="<?php echo $countguru ;?>">0</strong>
                        <p>Guru & Tutor </p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 counters-item text-center wow fadeInUp" data-wow-delay="400ms">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <strong data-to="<?php echo $countsiswa ;?>">0</strong>
                        <p>Warga Belajar</p>
                    </div>
                </div>
            </div>
        </section>
        <!--Fun Facts-->

        <!--FOOTER-->
        <footer class="padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 footer_panel bottom25">
                        <h3 class="heading bottom25">Tentang seTARA daring<span class="divider-left"></span></h3>
                        <a href="./" class="footer_logo bottom25"><img src="assets/img/front/seTARAdaring.png" width="150px" alt="Edua"></a>
                        <p>seTARA daring adalah sebuah aplikasi <i>Learning Management System</i> yang dirancang untuk pembelajaran jarak jauh pada pendidikan kesetaraan.</p>
                    </div>
                    <div class="col-md-4 col-sm-4 footer_panel bottom25">
                    </div>
                    <div class="col-md-4 col-sm-4 footer_panel bottom25">
                        <h3 class="heading bottom25">Direktorat Pendidikan Masyarakat dan Pendidikan Khusus <span class="divider-left"></span></h3>
                        <p class=" address"><p class=" address"><i class="icon-office"></i>
                        Jalan RS Fatmawati, Gedung B dan E Kompleks Kemendikbud Cipete, Jakarta Selatan 12410<br>
                        DKI Jakarta, Indonesia</p>
                        <p class=" address"><i class="icon-phone"></i>(021) 769 3260, 0838 7110 4637</p>
                        <p class=" address"><i class="icon-mail"></i><a href="ditpmpk@kemdikbud.go.id">ditpmpk@kemdikbud.go.id</a></p>
                        <img src="assets/img/front/footer-map-white.png" alt="we are here" class="img-responsive">
                    </div>
                </div>
                <div class="row copyright">
                    <div class="col-md-12 text-center">
                        <p>Copyright &copy; 2020 <a href="http://bindikmas.kemdikbud.go.id" target="_blank">Direktorat Pendidikan Masyarakat dan Pendidikan Khusus</a>. all rights reserved. <?=$_SERVER['SERVER_NAME']?></p>
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

        <script>
            $(function() {

                $('#form-pengajuan').submit(function() {
                    var fd = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: 'url-API/Satuan_Pendidikan/',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(res){
                            swal({
                                title: res.response,
                                text: res.message,
                                type: res.icon
                            }, function() {

                            });
                        },
                        error: function(){
                            swal({
                                title: res.response,
                                text: res.message,
                                type: res.icon
                            }, function() {

                            });
                        }
                    });

                    $('#pengajuan').modal('hide');
                    clear();
                });

                // $('.btn-cancel').click(function() {
                //     $('#npsn').val('');
                // });
            });

            function clear() {
                $('#npsn').val('');
            }

            $(document).ready(function() {
                // $("#notify").modal();
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
