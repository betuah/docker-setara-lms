<aside id="menu-fixed" class="profile-side" style="margin: 0 0 20px">
	<section class="box-typical">
		<header class="box-typical-header-sm bordered">
			<div class="btn-group" style="float: right;">
				<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Kembali
				</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="kelas?id=<?=$infoMapel['id_kelas']?>" class="dropdown-item" title="Kelas" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Kembali ke halaman kelas"><i class="font-icon font-icon-build"></i> Halaman kelas</a>
					<a href="mapel?id=<?=$infoMapel['_id']?>" class="dropdown-item" title="Mata Pelajaran" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Kembali ke halaman mata pelajaran"><i class="font-icon font-icon-doc"></i> Halaman mata pelajaran</a>
				</div>
			</div>
			<?=$infoModul['nama']?>

		</header>
		<div class="box-typical-inner">
			<ul class="side-menu-list">

				<li class="blue <?php if($menuModul==2){echo 'opened';} ?>">
		            <a href="modul?modul=<?=$_GET['modul']?>">
		                <i class="fa fa-book <?php if($menuModul==2){echo 'active';} ?>"></i>
		                <span class="lbl">Materi</span>
		            </a>
		        </li>
				<li class="blue <?php if($menuModul==3){echo 'opened';} ?>">
					<a href="tugas?modul=<?=$_GET['modul']?>">
						<i class="fa fa-file-text-o <?php if($menuModul==3){echo 'active';} ?>"></i>
						<span class="lbl">Penugasan</span>
					</a>
				</li>
				<li class="blue <?php if($menuModul==4){echo 'opened';} ?>">
					<a href="create-quiz?modul=<?=$_GET['modul']?>">
						<i class="font-icon font-icon-notebook <?php if($menuModul==4){echo 'active';} ?>"></i>
						<span class="lbl">Penilaian</span>
					</a>
				</li>
				<li class="blue <?php if($menuModul==5){echo 'opened';} ?>">
					<a href="modul-diskusi?modul=<?=$_GET['modul']?>">
						<i class="font-icon font-icon-comments <?php if($menuModul==5){echo 'active';} ?>"></i>
						<span class="lbl">Diskusi</span>
					</a>
				</li>
			</ul>
		</div>
	</section>

</aside><!--.profile-side-->
