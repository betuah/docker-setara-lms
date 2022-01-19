<?php
    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
        include 'setting/controller/' .$class . '.php';
    });
    $ClassUser = new User();
    $ClassKelas = new Kelas();
    $ClassMedia = new Media();
    $countUser  = $ClassUser->CountUserByProgram("Paket B");
    $countguru = $ClassUser->CountGuruByProgram("Paket B");
    $countsiswa = $ClassUser->CountSiswaByProgram("Paket B");
    $countmedia = $ClassMedia->CountMediaByKategori("5acadc74865eacc15c8f190c");
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
             id="login"
             tabindex="-1"
             role="dialog"
             aria-labelledby="loginLabel"
             aria-hidden="true"
             data-backdrop="static"
             data-keyboard="false">
            <div class="modal-dialog" style="width: 350px;" role="document">
                <div class="modal-content">
                    <form method='POST' class="sign-box" id="form-login" onsubmit="return false;">
                    <div style="display: flex">
                        <div class="sign-avatar">
                            <img src="assets/img/kemendikbud.png" style="border-radius: 0px !important;" alt="">
                        </div>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title text-center">seTARA daring</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username atau NIK" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Kata sandi" data-toggle="password" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div style="float: right;">
                                <a href="account-recovery">Lupa kata sandi anda ?</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-rounded btn-primary pull-right" name="updateMapel" value="send" >Masuk</button>
                        <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal">Batal</button>
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
                        <h4 class="modal-title text-center">seTARA daring</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#menu1">Warga Belajar</a></li>
                            <li><a data-toggle="tab" href="#menu2">Tutor</a></li>
                        </ul>
                        <br />
                        <div class="tab-content">
                            <div id="menu1" class="tab-pane fade in active">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="kode_kelas" id="kode_kelas" placeholder="Kode Kelas" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" placeholder="Nama Lengkap" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" pattern="([a-z0-9-_.@]){4,}" class="form-control" name="username_siswa" id="username_siswa" placeholder="Username atau NIK, minimal 4 karakter" title="Hanya dapat diisi menggunakan huruf kecil, angka, titik (.), underscore (_), strip (-), dan at (@)" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="password" class="form-control" name="password_siswa" id="password_siswa" placeholder="Kata sandi" data-toggle="password" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="password" class="form-control re_password_siswa" name="re_password_siswa" id="re_password_siswa" placeholder="Kata ulang sandi" data-toggle="password" />
                                        <span id="icon_re_password_siswa"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-rounded btn-primary pull-right" name="submit_siswa">Daftar</button>
                                    <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal"  onclick="clear();">Batal</button>
                                </div>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="nama_guru" id="nama_guru" placeholder="Nama Lengkap" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input type="text" pattern="([a-z0-9-_.@]){4,}" class="form-control" name="username_guru" id="username_guru" placeholder="Username atau NIK, minimal 4 karakter" title="Hanya dapat diisi menggunakan huruf kecil, angka, titik (.), underscore (_), strip (-), dan at (@)" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input type="email" class="form-control" name="email_guru" id="email_guru" placeholder="Email" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input type="password" class="form-control" name="password_guru" id="password_guru" placeholder="Kata sandi" data-toggle="password" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input type="password" class="form-control re_password_guru" name="re_password_guru" id="re_password_guru" placeholder="Kata ulang sandi" data-toggle="password" />
                                                <span id="icon_re_password_guru"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-rounded btn-primary pull-right" name="submit_guru" value="guru">Daftar</button>
                                    <button type="button" class="btn btn-rounded btn-default btn-cancel" data-dismiss="modal"  onclick="clear();">Batal</button>
                                </div>
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
             aria-labelledby="registerLabel"
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

        <div class="modal fade"
             id="notify-2"
             tabindex="-1"
             role="dialog"
             aria-labelledby="registerLabel"
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
                            <font color="#3ac9d6">Kepada Seluruh Pengguna seTARA daring</font>
                            <hr />
                            Untuk sementara waktu, <font color="red">aplikasi <i>mobile</i> setara daring</font> tidak dapat digunakan karena sedang dalam proses recovery server oleh PUSDATIN.
                            <br>
                            <br>
                            <br>
                            Terima kasih.
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
                            <li><a href="#" data-toggle="modal" data-target="#login"><i class="fa fa-user" aria-hidden="true"></i> Masuk</a>
                            </li>
                            <li><a href="#" data-toggle="modal" data-target="#register"><i class="fa fa-user-plus" aria-hidden="true"></i> Daftar</a>
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
                        <br>
                        <a href="#" data-toggle="modal" data-target="#register" class="border_radius btn_common yellow" style="border-color: white;"><i class="fa fa-user-plus" aria-hidden="true"></i> Daftar</a>
                        <a href="#" data-toggle="modal" data-target="#login" class="border_radius btn_common yellow" style="border-color: white;"><i class="fa fa-user" aria-hidden="true"></i> Masuk</a>
                    </div>
                </div>
                <!-- <div class="item" style="background:url(assets/img/front/paket-A.png) no-repeat; background-size: cover;">
                    <div class="rotate_caption text-center">
                        <h1>Selamat datang di seTARA daring</h1>
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
        <hr style="margin: 0">
        <!--Text Banner ends-->

        <section style="-moz-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); -o-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); -webkit-box-shadow: 0 3px 3px -3px rgba(0,0,0,.35); box-shadow: 0 3px 3px -3px rgba(0,0,0,.35);">
            <div class="text-center">
                <div style="width: 200px; margin:0 auto; padding: 10px; background: #3ac9d6; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; font-family: 'museo_slab700'; color: #fff; font-weight: normal;">Kerjasama:</div>
            </div>
            <div id="owl-demo" class="owl-carousel owl-theme" style="opacity: 1; display: block;">
                <div class="owl-item">
                    <div align="center">
                        <a href="https://kemdikbud.go.id/" target="_blank" title="Kementerian Pendidikan dan Kebudayaan">
                            <img style="max-height: 150px; max-width:150px; padding: 20px;" src="assets/img/kemendikbud.png" alt="KEMENDIKBUD" title="Kementerian Pendidikan dan Kebudayaan">
                        </a>
                    </div>
                </div>
                <div class="owl-item">
                    <div align="center">
                        <a href="http://www.seamolec.org/" target="_blank" title="SEAMEO SEAMOLEC">
                            <img style="max-height: 150px; max-width:150px; padding: 20px;" src="assets/img/seamolec.png" alt="SEAMEO SEAMOLEC">
                        </a>
                    </div>
                </div>
                <div class="owl-item">
                    <div align="center">
                        <a href="https://kemdikbud.go.id/" target="_blank" title="Kementerian Pendidikan dan Kebudayaan">
                            <img style="max-height: 150px; max-width:150px; padding: 20px;" src="assets/img/kemendikbud.png" alt="KEMENDIKBUD" title="Kementerian Pendidikan dan Kebudayaan">
                        </a>
                    </div>
                </div>
                <div class="owl-item">
                    <div align="center">
                        <a href="http://www.seamolec.org/" target="_blank" title="SEAMEO SEAMOLEC">
                            <img style="max-height: 150px; max-width:150px; padding: 20px;" src="assets/img/seamolec.png" alt="SEAMEO SEAMOLEC">
                        </a>
                    </div>
                </div>
            </div>

            <div class="owl-controls clickable">
                <div class="owl-pagination"></div>
            </div>
        </section>

        <!--ABout US-->
        <section id="about" class="padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-6 priorty wow fadeInLeft">
                        <h2 class="heading bottom25">Selamat datang di seTARA daring  <span class="divider-left"></span></h2>
                        <p class="half_space text-justify">Secara tersistem seTARA daring terhubung sekaligus terintegrasi dengan <a href="sumberbelajar.seamolec.org"><b>Sumber Belajar</b></a> sehingga guru dapat mengelola pembelajaran dengan aman dan cepat. seTARA daring dirancang untuk memberikan kemudahan tampilan sekaligus kontrol dalam pelaksanaan kelas digital.<br><br>
                        Sebagai <i>Learning Management System</i> (LMS), seTARA daring menyediakan kelengkapan pembelajaran dari perancangan, pelaksanaan pembelajaran, sampai ke penilaian. Karena Penilaian Akhir seperti PTS (Penilaian Tengah Semester), PAS (Penilaian Akhir Semester), dan PAT (Penilaian Akhir Tahun) dilaksanakan di sekolah, maka seTARA daring hanya menyediakan materi pembelajaran dan soal yang dikembangkan oleh guru sebagai wahana berlatih menghadapi Penilaian Akhir tahun dan Ujian Nasional yang sesungguhnya.
</p>
                    </div>
                    <div class="col-md-5 col-sm-6 wow fadeInRight">
                        <img src="assets/img/front/paket-B.png" alt="our priorties" class="img-responsive" style="width:100%;">
                    </div>
                </div>
            </div>

            <div class="container margin_top">
                <div class="row">
                    <div class="icon_wrap padding-bottom-half clearfix">
                        <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="300ms">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <h4 class="text-capitalize bottom20 margin10">Mudah</h4>
                            <p class="no_bottom" style="text-align: justify;">
                                <?php
                                    $isi = "Dengan fitur-fitur intuitif dan penyimpanan yang tidak terbatas dengan Gudang Media,
                                            dengan cepat membuat grup, memberikan pekerjaan rumah,
                                            menjadwalkan kuis, mengelola kemajuan dan banyak lagi.
                                            Dengan segala sesuatu pada satu platform,
                                            seTARA daring memperkuat dan meningkatkan apa yang telah anda lakukan di dalam kelas.";

                                    echo substr($isi, 0, 362)." ...";
                                ?>
                            </p>
                        </div>
                        <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="400ms">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <h4 class="text-capitalize bottom20 margin10">Aman</h4>
                            <p class="no_bottom" style="text-align: justify;">seTaRA daring dirancang untuk memberikan kontrol penuh atas kelas digital Anda.
                            Dengan alat yang memungkinkan Anda menentukan siapa yang dapat gabung dengan grup, memantau aktivitas
                            anggota.</p>
                        </div>
                        <div class="col-sm-4 icon_box text-center heading_space wow fadeInUp" data-wow-delay="500ms">
                            <i class="icon-icons20"></i>
                            <h4 class="text-capitalize bottom20 margin10">Serba Guna</h4>
                            <p class="no_bottom" style="text-align: justify;" >
                            <?php
                                $isi = "Apakah Anda ingin menciptakan ruang kelas tanpa kertas,
                                        membina keterampilan kewarganegaraan digital, mengintegrasikan konten
                                        pendidikan dari Gudang Media, atau tumbuh jaringan pembelajaran profesional Anda,
                                        Anda dapat mempersonalisasikan bagaimana Anda menggunakan seTARA daring.";

                                echo substr($isi, 0, 350)." ...";
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--ABout US-->

        <!--Fun Facts-->
        <section id="counter" class="parallax padding" style="background: #6cecf9">
            <div class="container">
                <h2 class="hidden">hidden</h2>
                <div class="row number-counters">
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
                    <div class="col-md-3 col-sm-6 col-xs-6 counters-item text-center wow fadeInUp" data-wow-delay="600ms">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <strong data-to="<?php echo $countmedia ;?>">0</strong>
                        <p>Bahan Ajar</p>
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
                        <p>Copyright &copy; 2020 <a href="http://bindikmas.kemdikbud.go.id" target="_blank">Direktorat Pendidikan Masyarakat dan Pendidikan Khusus</a>. all rights reserved.</p>
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
                        fd.append('program', "Paket B");
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
                //$("#notify-2").modal();
                $("#owl-demo").owlCarousel({
                   autoPlay: 4000, //Set AutoPlay to 3 seconds
                   items : 3,
                   itemsDesktop : [1199,3],
                   itemsDesktopSmall : [979,3],
                   itemsTablet : [768,3],
                   itemsMobile : [479,1]
                });
            });
        </script>
    </body>
</html>
