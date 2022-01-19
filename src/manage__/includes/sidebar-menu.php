	<div class="mobile-menu-left-overlay"></div>
	<nav class="side-menu">
	    <div class="side-menu-avatar">
	        <div class="avatar-preview avatar-preview-100">
	            <img src="../assets/img/avatar-1-256.png" alt="">
	        </div>
	        <div style="margin: 5px 10px; text-align: center; font-weight: bold;">
	        	<?=$_SESSION['admin_name']?>
	        </div>
	    </div>
	    <ul class="side-menu-list">
	    	<?php
	    		if ($_SESSION['admin_status'] == 'superadmin') {
	    			$menu = '<li class="brown">
					            <a href="./">
					                <i class="font-icon font-icon-home"></i>
					                <span class="lbl">Halama Utama</span>
					            </a>
					        </li>
					        <li class="blue">
					            <a href="pengguna.php">
					                <i class="font-icon font-icon-users"></i>
					                <span class="lbl">Pengguna</span>
					            </a>
					        </li>
					        <li class="red">
					            <a href="sekolah.php">
					                <i class="font-icon font-icon-build"></i>
					                <span class="lbl">Sekolah Induk</span>
					            </a>
					        </li>
							<li class="gold">
					            <a href="kelas.php">
					                <i class="font-icon font-icon-comments-2"></i>
					                <span class="lbl">Kelas</span>
					            </a>
					        </li>
					        <li class="red">
					            <a href="mata_pelajaran.php">
					                <i class="fa fa-book"></i>
					                <span class="lbl">Mata Pelajaran</span>
					            </a>
					        </li>
					        <li class="yellow">
					            <a href="modul.php">
					                <i class="font-icon font-icon-notebook"></i>
					                <span class="lbl">Kegiatan Pembelajaran</span>
					            </a>
					        </li>
					        <li class="aquamarine">
					            <a href="#">
					                <i class="font-icon font-icon-list-square"></i>
					                <span class="lbl">Tugas</span>
					            </a>
					        </li>
					        <li class="brown">
					            <a href="#">
					                <i class="font-icon font-icon-event"></i>
					                <span class="lbl">Evaluasi</span>
					            </a>
					        </li>
							<li class="magenta">
					            <a href="aktifitas.php">
					                <i class="font-icon font-icon-calend"></i>
					                <span class="lbl">Aktifitas</span>
					            </a>
					        </li>
					        <li class="orange-red with-sub">
					            <span>
					                <i class="font-icon font-icon-help"></i>
					                <span class="lbl">Bantuan</span>
					            </span>
					            <ul>
					                <li><a href="#"><span class="lbl">Feedback</span></a></li>
					                <li><a href="#"><span class="lbl">FAQ</span></a></li>
					            </ul>
					        </li>';
	    		}elseif ($_SESSION['admin_status'] == 'admin') {
	    			$menu = '<li class="brown">
					            <a href="./">
					                <i class="font-icon font-icon-home"></i>
					                <span class="lbl">Halama Utama</span>
					            </a>
					        </li>
					        <li class="blue">
					            <a href="pengguna.php">
					                <i class="font-icon font-icon-users"></i>
					                <span class="lbl">Pengguna</span>
					            </a>
					        </li>
					        <li class="red">
					            <a href="sekolah.php">
					                <i class="font-icon font-icon-build"></i>
					                <span class="lbl">Sekolah Induk</span>
					            </a>
					        </li>
							<li class="gold">
					            <a href="kelas.php">
					                <i class="font-icon font-icon-comments-2"></i>
					                <span class="lbl">Kelas</span>
					            </a>
					        </li>
					        <li class="red">
					            <a href="mata_pelajaran.php">
					                <i class="fa fa-book"></i>
					                <span class="lbl">Mata Pelajaran</span>
					            </a>
					        </li>
					        <li class="yellow">
					            <a href="modul.php">
					                <i class="font-icon font-icon-notebook"></i>
					                <span class="lbl">Kegiatan Pembelajaran</span>
					            </a>
					        </li>
					        <li class="aquamarine">
					            <a href="#">
					                <i class="font-icon font-icon-list-square"></i>
					                <span class="lbl">Tugas</span>
					            </a>
					        </li>
					        <li class="brown">
					            <a href="#">
					                <i class="font-icon font-icon-event"></i>
					                <span class="lbl">Evaluasi</span>
					            </a>
					        </li>
							<li class="magenta">
					            <a href="aktifitas.php">
					                <i class="font-icon font-icon-calend"></i>
					                <span class="lbl">Aktifitas</span>
					            </a>
					        </li>
					        <li class="orange-red with-sub">
					            <span>
					                <i class="font-icon font-icon-help"></i>
					                <span class="lbl">Bantuan</span>
					            </span>
					            <ul>
					                <li><a href="#"><span class="lbl">Feedback</span></a></li>
					                <li><a href="#"><span class="lbl">FAQ</span></a></li>
					            </ul>
					        </li>';
	    		}elseif ($_SESSION['admin_status'] == 'pengawas' || $_SESSION['admin_status'] == 'kepsek') {
	    			$menu = '<li class="brown">
					            <a href="./">
					                <i class="font-icon font-icon-home"></i>
					                <span class="lbl">Halama Utama</span>
					            </a>
					        </li>
					        <li class="red">
					            <a href="sekolah.php">
					                <i class="font-icon font-icon-build"></i>
					                <span class="lbl">Sekolah Induk</span>
					            </a>
					        </li>
							<li class="magenta with-sub">
					            <span>
					                <i class="font-icon font-icon-calend"></i>
					                <span class="lbl">Aktifiktas</span>
					            </span>
					            <ul>
					                <li>
										<a href="aktifitas.php"><span class="lbl">Aktifitas Perorangan</span></a>
									</li>
					                <li>
										<a href="aktifitas-laporan.php"><span class="lbl">Aktifitas Kelas</span></a>
									</li>
					            </ul>
					        </li>
					        <li class="orange-red with-sub">
					            <span>
					                <i class="font-icon font-icon-help"></i>
					                <span class="lbl">Bantuan</span>
					            </span>
					            <ul>
					                <li><a href="#"><span class="lbl">Feedback</span></a></li>
					                <li><a href="#"><span class="lbl">FAQ</span></a></li>
					            </ul>
					        </li>';
	    		}

	    		echo $menu;
	    	?>
	        
	    </ul>
	</nav><!--.side-menu-->