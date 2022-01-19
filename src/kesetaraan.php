<?php

    if($_SERVER['SERVER_NAME'] == 'lms.sikk.sch.id'){
        header('Location: https://lms-siln.kemdikbud.go.id');
    }

    if($_SERVER['SERVER_NAME'] == 'umt-online.seamolec.org'){
        header('Location: http://umt-online.seamolec.org/umt');
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
<!--[if IE 7 ]>    <html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en-gb" class="isie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en-gb" class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->

	<title> seTARA daring | Dit. PMPK </title>

	<meta name="description" content="seTARA daring adalah sebuah aplikasi Learning Management System yang dirancang untuk pembelajaran jarak jauh pada pendidikan kesetaraan">
	<meta name="author" content="designthemes">

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<!--[if lte IE 8]>
		<script type="text/javascript" src="http://explorercanvas.googlecode.com/svn/trunk/excanvas.js"></script>
	<![endif]-->

    <!-- **Favicon** -->
	<link href="assets/images-front/kemendikbud.png" rel="shortcut icon" type="image/x-icon" />

    <!-- **CSS - stylesheets** -->
    <link id="default-css" href="assets/css-front/style.css" rel="stylesheet" media="all" />
    <link id="shortcodes-css" href="assets/css-front/shortcode.css" rel="stylesheet" media="all" />
	<link href="assets/css-front/meanmenu.css" rel="stylesheet" type="text/css" media="all" />
    <link id="skin-css" href="assets/css-front/cyan/style.css" rel="stylesheet" media="all" />

    <link href="assets/css-front/responsive.css" rel="stylesheet" type="text/css" />

    <!-- **Animation stylesheets** -->
    <link href="assets/css-front/animations.css" rel="stylesheet" type="text/css" />
	<link href="assets/css-front/isotope.css" rel="stylesheet" type="text/css" media="all" />
	<link href="assets/css-front/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />

    <!-- **Font Awesome** -->
    <link href="assets/css-front/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css-front/font-awesome-ie7.min.css" />
    <![endif]-->

    <!-- **Google - Fonts** -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300italic,400italic,600' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <!-- SLIDER STYLES STARTS -->
	<link rel="stylesheet" type="text/css" href="assets/js-front/revolution/settings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="assets/js-front/layerslider/layerslider.css" media="screen">
    <!-- SLIDER STYLES ENDS -->

    <!-- **jQuery** -->
    <script src="assets/js-front/modernizr-2.6.2.min.js"></script>
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


<body>
	<div class="wrapper">
    	<div class="inner-wrapper">
    	<!-- Header div Starts here -->
    	<header id="header">
        	<div class="container">
            	<div id="logo">
                	<a href=""> <img src="assets/images-front/setara-kemendikbud.png" alt="" title="" style="width: 225px; margin-top: -6px;"> </a>
                </div>
                <div id="menu-container">
                    <nav id="main-menu">
                        <ul class="group">
                            <li class="menu-item current_page_item"><a href="" style="color:#00aeae;">Beranda</a></li>
                            <li class="menu-item"><a href="paket-a" target="_blank" style="color:#00aeae;">Paket A</a></li>
                            <li class="menu-item"><a href="paket-b" target="_blank" style="color:#00aeae;">Paket B</a></li>
                            <li class="menu-item"><a href="paket-c" target="_blank" style="color:#00aeae;">Paket C</a></li>
                            <li class="menu-item"><a href="Panduan-Teknis-Penyelenggaraan-UPK-melalui-seTARA-daring.pdf" target="_blank" style="color:#00aeae;">Panduan UPK seTARA daring</a></li>
                        </ul>
                    </nav>
				</div>
            </div>
        </header>
        <!-- Header div Ends here -->
        <div id="main">
        	<!-- home section Starts here -->
        	<section id="home" class="content" style="padding-bottom: unset;">
                <div class="fullwidthbanner-container banner">
                    <div class="fullwidthbanner">
                        <ul>
<li data-transition="random" data-slotamount="7" data-masterspeed="300" data-delay="10000" >
                                <img src="assets/images-front/banner-login.png"  alt="slider-bg"  data-fullwidthcentering="on">
                                <div class="tp-caption lfb"
                                    data-x="67"
                                    data-y="410"
                                    data-speed="1000"
                                    data-start="1500"
                                    data-easing="easeOutExpo"><a href="https://apps.apple.com/id/app/setaradaring/id1546552242?l=id" target="_blank"><img src="assets/images-front/revolution/appstore.png" alt="Image 2"></a>
                                </div>

                                <div class="tp-caption lfb"
                                    data-x="285"
                                    data-y="410"
                                    data-speed="1000"
                                    data-start="2000"
                                    data-easing="easeOutExpo"><a href="https://play.google.com/store/apps/details?id=com.setaradaring" target="_blank"><img src="assets/images-front/revolution/googlepay.png" alt="Image 3"></a>
                                </div>

                                <div class="tp-caption custom_title lft"
                                    data-x="67"
                                    data-y="58"
                                    data-speed="1000"
                                    data-start="2500"
                                    data-easing="easeOutExpo"><font style="color: white;">seTARA daring kini hadir</font> <br>
                                    <font style="color: white;">dalam ponsel pintar</font>
                                </div>

                                <div class="tp-caption custom_reviewtext sfr"
                                    data-x="67"
                                    data-y="178"
                                    data-speed="1000"
                                    data-start="3500"
                                    data-easing="easeOutExpo"><font style="color: white;">"seTARA daring hadir sebagai solusi untuk perluasan akses pendidikan kesetaraan."</font>
                                </div>

                                <div class="tp-caption custom_reviewtext sfr"
                                    data-x="115"
                                    data-y="212"
                                    data-speed="1000"
                                    data-start="4000"
                                    data-easing="easeOutExpo"><font style="color: white;">- Dr. Samto (Direktur Pendidikan Masyarakat dan Pendidikan Khusus)</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="140"
                                    data-y="238"
                                    data-speed="1000"
                                    data-start="4500"
                                    data-easing="easeOutExpo"><img src="assets/images-front/revolution/reviews-splitter.jpg" alt="Image 9">
                                </div>

								<div class="tp-caption custom_reviewtext sfr"
                                    data-x="67"
                                    data-y="298"
                                    data-speed="1000"
                                    data-start="5000"
                                    data-easing="easeOutExpo"><font style="color: white;">"Pendidikan Kesetaraan telah lebih siap menghadapi era industri 4.0 dengan seTARA daring<br><br>dalam mewujudkan pendidikan kesetaraan yang bermutu, berkualitas dan bermartabat."</font>
                                </div>

                                <div class="tp-caption custom_reviewtext sfr"
                                    data-x="173"
                                    data-y="352"
                                    data-speed="1000"
                                    data-start="5500"
                                    data-easing="easeOutExpo"><font style="color: white;">- Dr. Subi Sudarto (Koordinator Fungsi Kesetaraan)</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="140"
                                    data-y="378"
                                    data-speed="1000"
                                    data-start="6000"
                                    data-easing="easeOutExpo"><img src="assets/images-front/revolution/reviews-splitter.jpg" alt="Image 9">
                                </div>
                            </li>

                            <li data-transition="random" data-slotamount="7" data-masterspeed="300">
                                <img src="assets/images-front/banner-mapel.png"  alt="slider-bg2">
                                <div class="tp-caption lfb"
                                    data-x="-75"
                                    data-y="48"
                                    data-speed="1000"
                                    data-start="1000"
                                    data-easing="easeOutExpo"><img src="assets/images-front/baca-materi.png" alt="Image 1">
                                </div>

                                <div class="tp-caption custom_title lft"
                                    data-x="400"
                                    data-y="120"
                                    data-speed="1000"
                                    data-start="1500"
                                    data-easing="easeOutExpo"><font style="color: white;">Fitur seTARA mobile</font>
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="182" 					 data-speed="1000"
                                    data-start="2000"
                                    data-easing="easeOutExpo"><font style="color: white;">Registrasi Akun</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="188"
                                    data-speed="1000"
                                    data-start="2000"
                                    data-easing="easeInCubic"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="228"
                                    data-speed="1000"
                                    data-start="2500"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="222"
                                    data-speed="1000"
                                    data-start="2500"
                                    data-easing="easeOutExpo"><font style="color: white;">Login Akun</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="268"
                                    data-speed="1000"
                                    data-start="2800"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="262"
                                    data-speed="1000"
                                    data-start="3100"
                                    data-easing="easeOutExpo"><font style="color: white;">Gabung Kelas</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="308"
                                    data-speed="1000"
                                    data-start="3400"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="302"
                                    data-speed="1000"
                                    data-start="3700"
                                    data-easing="easeOutExpo"><font style="color: white;">Akses Daftar Kelas</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="348"
                                    data-speed="1000"
                                    data-start="4000"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="342"
                                    data-speed="1000"
                                    data-start="4300"
                                    data-easing="easeOutExpo"><font style="color: white;">Akses Daftar Mapel</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="450"
                                    data-y="388"
                                    data-speed="1000"
                                    data-start="4600"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="382"
                                    data-speed="1000"
                                    data-start="4900"
                                    data-easing="easeOutExpo"><font style="color: white;">Akses Materi Modul</font>
                                </div>

								<div class="tp-caption fade"
                                    data-x="450"
                                    data-y="428"
                                    data-speed="1000"
                                    data-start="5200"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="477"
                                    data-y="426"
                                    data-speed="1000"
                                    data-start="5500"
                                    data-easing="easeOutExpo"><font style="color: white;">Akses Penugasan Modul</font>
                                </div>

                                <div class="tp-caption sft"
                                    data-x="430"
                                    data-y="58"
                                    data-speed="300"
                                    data-start="1300"
                                    data-easing="easeInOutBounce"><img src="assets/images-front/revolution/bulb.png" alt="Image 15">
                                </div>

                            </li>

							<li data-transition="random" data-slotamount="7" data-masterspeed="300">
                                <img src="assets/images-front/banner-emodul.png"  alt="slider-bg">

                                <div class="tp-caption lfb"
                                    data-x="360"
                                    data-y="246"
                                    data-speed="1000"
                                    data-start="500"
                                    data-easing="easeOutExpo"><img src="assets/images-front/emodul.png" alt="Image 2">
                                </div>

                                <div class="tp-caption custom_title2 lft"
                                    data-x="67"
                                    data-y="66"
                                    data-speed="1000"
                                    data-start="1500"
                                    data-easing="easeOutExpo"><font style="color: white;">seTARA mobile <br>Terintegrasi eModul</font>
                                </div>

                                <div class="tp-caption custom_content sfl"
                                    data-x="67"
                                    data-y="166"
                                    data-speed="1000"
                                    data-start="2500"
                                    data-easing="easeOutExpo"><font style="color: white;">Semua modul dalam satu genggaman</font>
                                </div>

								<div class="tp-caption fade"
                                    data-x="67"
                                    data-y="248"
                                    data-speed="1000"
                                    data-start="3000"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="95"
                                    data-y="242"
                                    data-speed="1000"
                                    data-start="3300"
                                    data-easing="easeOutExpo"><font style="color: white;">eModul Paket A</font>
                                </div>

                                <div class="tp-caption fade"
                                    data-x="67"
                                    data-y="288"
                                    data-speed="1000"
                                    data-start="3600"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="95"
                                    data-y="282"
                                    data-speed="1000"
                                    data-start="3900"
                                    data-easing="easeOutExpo"><font style="color: white;">eModul Paket B</font>
                                </div>

								<div class="tp-caption fade"
                                    data-x="67"
                                    data-y="328"
                                    data-speed="1000"
                                    data-start="4200"
                                    data-easing="easeInQuad"><img src="assets/images-front/revolution/tick-white.png" alt="Image 4">
                                </div>

                                <div class="tp-caption custom_contenttext sfr"
                                    data-x="95"
                                    data-y="326"
                                    data-speed="1000"
                                    data-start="4500"
                                    data-easing="easeOutExpo"><font style="color: white;">eModul Paket C</font>
                                </div>

								<div class="tp-caption custom_content lfb"
                                    data-x="67"
                                    data-y="400"
                                    data-speed="1000"
                                    data-start="4800"
                                    data-easing="easeOutExpo"><a class="button small" href="https://emodul.kemdikbud.go.id/" target="_blank"><span class="icon-book"></span> eModul Pendidikan Kesetaraan <span class="icon-caret-right"></span></a>
                                </div>
                            </li>

							<li data-transition="random" data-slotamount="7" data-masterspeed="300">
                                <img src="assets/images-front/banner-bg.png"  alt="slider-bg">
                                <div class="tp-caption lfr"
                                    data-x="360"
                                    data-y="105"
                                    data-speed="1000"
                                    data-start="500"
                                    data-easing="easeOutExpo"><img src="assets/images-front/all-device.png" alt="Image 1">
                                </div>

                                <div class="tp-caption custom_title2 lft"
                                    data-x="67"
                                    data-y="166"
                                    data-speed="1000"
                                    data-start="1500"
                                    data-easing="easeOutExpo"><font style="color: white;">Akses seTARA daring <br>melalui berbagai perangkat</font>
                                </div>

                                <div class="tp-caption custom_content sfl"
                                    data-x="67"
                                    data-y="274"
                                    data-speed="1000"
                                    data-start="2500"
                                    data-easing="easeOutExpo"><font style="color: white;">"Belajar kapan saja dan dimana saja <br>menggunakan berbagai perangkat"</font>
                                </div>

                                <div class="tp-caption custom_content lfb"
                                    data-x="67"
                                    data-y="403"
                                    data-speed="1000"
                                    data-start="3500"
                                    data-easing="easeOutExpo"><a class="button small" href="#layanan">Daftar Sekarang <span class="icon-caret-right"></span></a>
                                </div>
                            </li>

							<li data-transition="random" data-slotamount="7" data-masterspeed="300">
                                <img src="assets/images-front/banner-setara.png"  alt="slider-bg">

                                <div class="tp-caption custom_content lfb"
                                    data-x="250"
                                    data-y="350"
                                    data-speed="1000"
                                    data-start="1000"
                                    data-easing="easeOutExpo"><a class="button small" href="http://bit.ly/setara_daring" target="_blank" style="border-color: #474747; background-color: #2e2e2e; margin-right: 10px;"><span class="icon-book"></span> Panduan Penggunaan</a>
                                </div>

								<div class="tp-caption custom_content lfb"
									data-x="500"
									data-y="350"
									data-speed="1000"
									data-start="2000"
									data-easing="easeOutExpo"><a class="button small" href="#services" style="border-color: #474747; background-color: #2e2e2e;"><span class="icon-check"></span> Pengajuan Penyelenggaraan</a>
								</div>
                            </li>
                        </ul>

                    </div>

                </div>
            </section>
            <!-- home section Ends here -->
			<!-- pricing section Starts here -->
			<section id="layanan" class="content">
				<div class="content-main" style="padding-top: 50px;">
					<div class="container">
						<div class="aligncenter welcome">
	                        <h2>Pilih Program yang Diikuti untuk Melanjutkan Pembelajaran</h2>
							<div class="one-third column" style="margin-right: 1%">
								<div class="service">
									<a href="http://setara.kemdikbud.go.id/paket-a" target="_blank" style="color:#676767">
										<i class="icon-laptop animate" data-animation="rollIn" data-delay="100"></i>
										<div class="margin20"></div>
										<h4>Paket A</h4>
									</a>
									<p style="text-align:justify">Paket A merupakan program layanan pendidikan kesetaraan pada jenjang pendidikan dasar setara dengan SD</p>
								</div>
							</div>
							<div class="one-third column" style="margin-right: 1%">
								<div class="service">
									<a href="http://setara.kemdikbud.go.id/paket-b" target="_blank" style="color:#676767">
										<i class="icon-laptop animate" data-animation="rollIn" data-delay="300"></i>
										<div class="margin20"></div>
										<h4>Paket B</h4>
									</a>
									<p style="text-align:justify">Paket B merupakan program layanan pendidikan kesetaraan pada jenjang pendidikan dasar lanjutan setara SMP</p>
								</div>
							</div>
							<div class="one-third column" style="margin-right: 1%">
								<div class="service">
									<a href="http://setara.kemdikbud.go.id/paket-c" target="_blank" style="color:#676767">
										<i class="icon-laptop animate" data-animation="rollIn" data-delay="500"></i>
										<div class="margin20"></div>
										<h4>Paket C</h4>
									</a>
									<p style="text-align:justify">Paket C merupakan program layanan pendidikan kesetaraan pada jenjang pendidikan menengah setara SMA</p>
								</div>
							</div>
	                    </div>
						<div class="margin50 border-title"></div>
						<div class="one-fourth column">
							<div class="pr-tb-col animate" data-animation="flipInY">
								<div class="tb-header">
									<div class="price" style="font-size: 40px;"> <?php echo $countSekolah ;?> </div>
									<div class="guarantee">
										<p style="font-size: 16px;"><i class="icon-building animate" data-animation="rollIn" data-delay="100"></i> Satuan Pendidikan</p>
									</div>
								</div>
							</div>
						</div>
						<div class="one-fourth column">
							<div class="pr-tb-col animate" data-animation="flipInY">
								<div class="tb-header">
									<div class="price" style="font-size: 40px;"> <?php echo $countUser ;?> </div>
									<div class="guarantee">
										<p style="font-size: 16px;"><i class="icon-group animate" data-animation="rollIn" data-delay="100"></i> User</p>
									</div>
								</div>
							</div>
						</div>
						<div class="one-fourth column">
							<div class="pr-tb-col animate" data-animation="flipInY">
								<div class="tb-header">
									<div class="price" style="font-size: 40px;"> <?php echo $countguru ;?> </div>
									<div class="guarantee">
										<p style="font-size: 16px;"><i class="icon-group animate" data-animation="rollIn" data-delay="100"></i> Tutor</p>
									</div>
								</div>
							</div>
						</div>
						<div class="one-fourth column last active">
							<div class="pr-tb-col animate" data-animation="flipInY">
								<div class="tb-header">
									<div class="price" style="font-size: 40px;"> <?php echo $countsiswa ;?> </div>
									<div class="guarantee">
										<p style="font-size: 16px;"><i class="icon-group animate" data-animation="rollIn" data-delay="100"></i> Warga Belajar</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- pricing section Ends here -->
			<!-- features section Starts here -->
            <section id="features" class="content">
            	<div class="main-title">
                	<div class="container">
                    	<h2>Fitur seTARA mobile</h2>
                    </div>
                </div>
                <div class="content-main">
                	<div class="container">
                    	<div class="one-half column">
                        	<img src="assets/images-front/feature.png" alt="mobilemap" title="" class="aligncenter rollImage animate" data-animation="fadeInLeft">
                        </div>
                        <div class="one-half column last">
                        	<div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Registrasi Akun</h3>
                                <p style="text-align:justify">Kamu bisa melakukan registrasi akun melalui seTARA mobile agar bisa belajar menggunakan seTARA daring. Registrasi akun cukup kamu lakukan satu kali saja.</p>
                            </div>
                            <div class="margin35"></div>
                            <div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Login Akun</h3>
                                <p style="text-align:justify">Untuk kamu yang sudah mempunyai akun, kamu bisa login dengan mengetikkan username dan password kamu di halaman login. Pastikan kamu terhubung jaringan internet yah.</p>
                            </div>
                            <div class="margin35"></div>
                            <div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Melihat Postingan</h3>
                                <p style="text-align:justify">Sesaat setelah kamu berhasil login, kamu bisa melihat postingan dari bapak ibu Tutor. Isi postingan biasanya berupa pemberitahuan dan pengumuman dari bapak ibu Tutor.</p>
                            </div>
                        </div>
                        <div class="margin65"></div>
                        <div class="one-half column pull-up">
                        	<div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Gabung Kelas</h3>
                                <p style="text-align:justify">Kamu bisa bergabung ke dalam kelas dengan menginputkan KODE kelas yang sudah diberikan oleh bapak ibu Tutor. Jika KODE kelas yang diinputkan sesuai, kamu bisa mulai belajar di dalam kelas daringnya.</p>
                            </div>
                            <div class="margin35"></div>
                            <div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Akses Daftar Kelas</h3>
                                <p style="text-align:justify">Daftar kelas yang kamu lihat adalah kelas-kelas yang sedang kamu ikuti. Pastikan kamu sudah mengikuti kelas yang sesuai dengan jenjang pendidikan kamu yah.</p>
                            </div>
                            <div class="margin35"></div>
                            <div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Akses Daftar Anggota Kelas</h3>
                                <p style="text-align:justify">Pada saat kamu berada di dalam menu kelas, kamu bisa mengetahui teman-teman kamu dengan mengakses daftar anggota kelas. Selain itu, kamu juga bisa mengatahui bapak ibu Tutor yang mengajar di kelas tersebut.</p>
                            </div>
                        </div>
                        <div class="one-half column last">
                        	<img src="assets/images-front/feature-2.png" alt="mobiletwo" title="" class="aligncenter fadeImage animate" data-animation="fadeInRight" style="margin-left: 70px;">
                        </div>
                        <div class="margin90"></div>
                        <div class="one-third column">
                        	<div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Akses Daftar Mapel</h3>
                                <p style="text-align:justify">Pada saat kamu berada di dalam menu kelas, kamu bisa mengatahui mata pelajaran apa saja yang akan kamu pelajari di dalam kelas tersebut.</p>
                            </div>
                        </div>
                        <div class="one-third column">
                        	<div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Akses Materi Modul</h3>
                                <p style="text-align:justify">Materi di dalam seTARA daring disajikan berdasarkan mata pelajaran dan modul. Kamu bisa mempelajari materi dalam bentuk teks, gambar, audio bahkan video.</p>
                            </div>
                        </div>
                        <div class="one-third column last">
                        	<div class="custom-services">
                            	<span class="icon-one animate" data-animation="bounceIn"></span>
                                <h3>Akses Penugasan Modul</h3>
                                <p style="text-align:justify">Bentuk soal penugasan modul berbentuk soal isian, dan kamu bisa mengumpulkan tugas yang diberikan oleh bapak ibu Tutor sebelum tengat waktu pengumpulan tugas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- features section Ends here -->
            <!-- services section Starts here -->
            <section id="services" class="content">
            	<div class="main-title">
                	<div class="container">
                    	<h2>Pengajuan Penyelenggaraan</h2>
                    </div>
                </div>
                <div class="content-main" style="padding-top:25px;">
                    <div class="container">
                        <h2 class="border-title">Satuan Pendidikan Belum Terdaftar?<span></span></h2>
                        <div class="margin15"></div>
                        <div class="tabs-vertical-container">
                            <ul class="tabs-vertical-frame one-third column">
                                <li><a href="#"> <span> 1</span> Akses Form Pengajuan </a></li>
                                <li><a href="#"> <span> 2</span> Lengkapi Form Pengajuan </a></li>
                                <li><a href="#"> <span> 3</span> Tunggu Konfirmasi </a></li>
                            </ul>
                            <div class="tabs-vertical-frame-content two-third column last">
                            		<h3> Akses Form Pengajuan </h3>
                                <p>Form pengajuan diisi oleh kepala atau perwakilan satuan pendidikan ketika akan menggunakan seTARA daring di satuan pendidikannya pada saat pertama kali.</p>
                                <p>Form pengajuan diakses melalui tautan <a href="http://bit.ly/pengajuan_daring">http://bit.ly/pengajuan_daring</a></p>
                            </div>
                            <div class="tabs-vertical-frame-content two-third column last">
                                <h3> Lengkapi Form Pengajuan </h3>
                                <p> Silakan lengkapi form pengajuan berdasarkan data satuan pendidikan saat ini. Pastikan data yang diinputkan sesuai dengan data yang ada di DAPODIK.</p>
								<p> Ketidak sesuaian data yang diinputkan dapat menyebabkan kendala pada saat proses administrasi pembelajaran dilakukan.</p>
                            </div>
                            <div class="tabs-vertical-frame-content two-third column last">
                            		<h3> Tunggu Konfirmasi </h3>
                                <p> Silakan tunggu konfirmasi hasil pengajuan melalui email yang digunakan pada saat melakukan pengisian form pengajuan. Pastikan email yang digunakan adalah email aktif karena akan digunakan untuk menerima email konfirmasi.</p>
                                <p> Pengajuan akan diproses paling cepat 1 X 24 jam.</p>
                            </div>
                        </div>
                    </div>
				</div>
            </section>
            <!-- services section Ends here -->
            <!-- contact section Starts here -->
            <section id="contact" class="content">
            	<div class="main-title">
                	<div class="container">
                    	<h2>Hubungi Kami</h2>
                    </div>
                </div>
                <div class="content-main">
                	<div class="container">
                    	<div class="location">
                        	<h4 class="map-title">Location Map</h4>
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2724.5253037832767!2d106.79817956077393!3d-6.268467084023371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f199c5981c2b%3A0x8fa6c2c0e3838fa8!2sKementerian%20Pendidikan%20dan%20Kebudayaan%20-%20Direktorat%20Pembinaan%20Sekolah%20Menengah%20Atas!5e0!3m2!1sen!2sid!4v1611419684669!5m2!1sen!2sid" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

                            <div class="margin55"></div>
                            <div class="contact-info">
                                <div class="one-half column">
                                    <h4>Direktorat Pendidikan Masyarakat dan Pendidikan Khusus</h4>
                                     <p><i class="icon-building"></i> Jalan RS Fatmawati, Gedung B dan E Kompleks Kemendikbud Cipete, <br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jakarta Selatan 12410 <br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DKI Jakarta, Indonesia <br><br>
										<i class="icon-phone"></i> (021) 769 3260, 0838 7110 4637 <br>
										<i class="icon-envelope"></i> <a href="#">ditpmpk@kemdikbud.go.id</a>
									</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- contact section Ends here -->
            <footer>
                <div class="copyright">
                	<div class="container">
	                    <p>&copy; 2021 Direktorat Pendidikan Masyarakat dan Pendidikan Khusus | All Rights Reserved</p>
                    </div>
                </div>
            </footer>
		</div>


        </div>
    </div><!-- Wrapper End -->

    <!-- Java Scripts -->
    <script type="text/javascript" src="assets/js-front/jquery-1.10.2.min.js"></script>

    <script type="text/javascript" src="assets/js-front/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="assets/js-front/jquery.inview.js"></script>

    <script type="text/javascript" src="assets/js-front/jquery.nav.js"></script>
    <script type="text/javascript" src="assets/js-front/jquery-menu.js"></script>
	<script type="text/javascript" src="assets/js-front/jquery.meanmenu.min.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.quovolver.min.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.donutchart.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.isotope.min.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.prettyPhoto.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.validate.min.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.tabs.min.js"></script>

	<script type="text/javascript" src="assets/js-front/jquery.nicescroll.min.js"></script>

	<!-- Layer Slider Starts -->
	<script src="assets/js-front/layerslider/jquery-easing-1.3.js" type="text/javascript"></script>
    <script src="assets/js-front/layerslider/jquery-transit-modified.js" type="text/javascript"></script>
    <script src="assets/js-front/layerslider/layerslider.transitions.js" type="text/javascript"></script>
    <script src="assets/js-front/layerslider/layerslider.kreaturamedia.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
	jQuery(document).ready(function($){
		$('#layerslider').layerSlider({
			skinsPath : 'js/layerslider/skins/',
			skin : 'borderlessdark3d',
			width : '940px',
			height : '500px',
			responsive : true,
			thumbnailNavigation : 'hover',
			showCircleTimer : false,
			navPrevNext	 : true,
			navButtons	 : true,
			hoverPrevNext: true
		});
	});
	</script>
	<!-- Layer Slider Ends -->

    <!-- Revolution Slider Starts -->
    <script src="assets/js-front/revolution/jquery.themepunch.revolution.min.js" type="text/javascript"></script>
    <script type="text/javascript">
		// Initialize and add the map
	jQuery(document).ready(function($){
	if($.fn.cssOriginal != undefined)
		$.fn.css = $.fn.cssOriginal;

		var api = $('.fullwidthbanner').revolution(
		{
			delay:9000,
			startwidth:940,
			startheight:570,

			onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off

			thumbWidth:100,							// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
			thumbHeight:50,
			thumbAmount:3,

			hideThumbs:200,
			navigationType:"none",				// bullet, thumb, none
			navigationArrows:"solo",				// nexttobullets, solo (old name verticalcentered), none

			navigationStyle:"round",				// round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom

			navigationHAlign:"center",				// Vertical Align top,center,bottom
			navigationVAlign:"bottom",					// Horizontal Align left,center,right
			navigationHOffset:30,
			navigationVOffset:-40,

			soloArrowLeftHalign:"left",
			soloArrowLeftValign:"center",
			soloArrowLeftHOffset:20,
			soloArrowLeftVOffset:0,

			soloArrowRightHalign:"right",
			soloArrowRightValign:"center",
			soloArrowRightHOffset:20,
			soloArrowRightVOffset:0,

			touchenabled:"on",						// Enable Swipe Function : on/off

			stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
			stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

			hideCaptionAtLimit:0,					// It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
			hideAllCaptionAtLilmit:0,				// Hide all The Captions if Width of Browser is less then this value
			hideSliderAtLimit:0,					// Hide the whole slider, and stop also functions if Width of Browser is less than this value

			fullWidth:"on",

			shadow:0								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)
		});
	});
	</script>
    <!-- Revolution Slider Ends -->

    <!-- **To Top** -->
    <script src="assets/js-front/jquery.ui.totop.min.js"></script>

	<script type="text/javascript" src="assets/js-front/custom.js"></script>


</body>
</html>
