<aside id="menu-fixed" class="profile-side" style="margin: 0 0 20px">
    <section class="box-typical">
        <header class="box-typical-header-sm bordered">
            <div class="btn-group" style="float: right;">
                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Kembali
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="kelas?id=<?=$infoMapel['id_kelas']?>" class="dropdown-item" title="Kelas" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Kembali ke halaman kelas"><i class="font-icon font-icon-build"></i> Halaman kelas</a>
                </div>
            </div>
            Menu
        </header>
        <div class="box-typical-inner">
            <ul class="side-menu-list">
                <li class="blue <?php if($menuMapel==1){echo 'opened';} ?>">
                    <a href="silabus?id=<?=$_GET['id']?>">
                        <i class="font-icon font-icon-home <?php if($menuMapel==1){echo 'active';} ?>"></i>
                        <span class="lbl">Petunjuk Belajar</span>
                    </a>
                </li>
                <li class="blue <?php if($menuMapel==2){echo 'opened';} ?>">
                    <a href="mapel?id=<?=$_GET['id']?>">
                        <i class="glyphicon glyphicon-book <?php if($menuMapel==2){echo 'active';} ?>"></i>
                        <span class="lbl">Pelajaran</span>
                    </a>
                </li>
                <li class="blue <?php if($menuMapel==3){echo 'opened';} ?>">
                    <a href="perkembangan?id=<?=$_GET['id']?>">
                        <i class="font-icon font-icon-zigzag <?php if($menuMapel==3){echo 'active';} ?>"></i>
                        <span class="lbl">Perkembangan</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>

</aside><!--.profile-side-->
