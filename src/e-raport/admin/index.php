<?php
session_start();
include"../config/connect.php";
if(isset($_SESSION['user']) AND isset($_SESSION['pass'])){
$ceksession=mysql_query("SELECT nama_user FROM user WHERE username='$_SESSION[user]'");
$datasession=mysql_fetch_array($ceksession);
$_SESSION['nama']=$datasession['nama_user'];

if(isset($_GET['menu'])){
    $url=$_GET['menu'];
    $url2="home";
} elseif(isset($_GET['aksi'])){
    $url="home";
    $url2=$_GET['aksi'];
} else {
    $url="home";
    $url2="home";
}
$singkatnama=substr($_SESSION['nama'], 0, 7);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='../images/kemdikbud.png' rel='icon' type='image/gif'/>


    <!-- CORE CSS-->
    <link href="../css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/jquery-ui.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="../js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- jQuery Library -->
    <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>

    <!-- jQuery UI -->
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script>
/*autocomplete muncul setelah user mengetikan minimal1 karakter */
    $(function() {
        $( "#kode_guru" ).autocomplete({
         source: "autocomplete_kelas.php",
           minLength:1,
        });
    });
    $(function() {
        $( "#tmp_lahir" ).autocomplete({
         source: "autocomplete_tmp_lahir.php",
           minLength:1,
        });
    });
    $(function() {
        $( "#kode_siswa" ).autocomplete({
         source: "autocomplete_kelassiswa.php",
           minLength:1,
        });
    });
    </script>

     <!--sweetalert js-->
    <script type="text/javascript" src="../js/sweetalert.min.js"></script>

</head>

<body>


    <!-- ############################################################################### -->

    <!-- BUKA TAG HEADER-->
    <header id="header" class="page-topbar">
       <!-- Buka div navbar-fixed -->
        <div class="navbar-fixed">
            <nav class="info lighten-2">
                <div class="nav-wrapper">
                    <h1 class="logo-wrapper"><a href="index.php" class="brand-logo darken-1">E-Raport</a></h1>
                    <ul class="right hide-on-med-and-down">
                        <li class="search-out">
                            <input type="text" class="search-out-text">
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect waves-block waves-light show-search"><i class="mdi-action-search"></i></a>
                        </li>
                    </ul>
                </div> <!-- Tutup div nav-wrapper -->
            </nav> <!-- Tutup tag nav-->
        </div> <!-- Tutup div navbar-fixed-->
    </header> <!-- TUTUP TAG HEADER -->


    <!-- ############################################################################### -->

    <!-- START MAIN -->
    <div id="main">
        <div class="wrapper"> <!-- START WRAPPER -->
            <!-- Buka left-sidebar-nav -->
            <aside id="left-sidebar-nav">
                <ul id="slide-out" class="side-nav fixed leftside-navigation">
                    <li class="user-details">
                        <div class="row">
                            <div class="col col s4 m4 l4">
                                <img src="foto/<?php echo $_SESSION['foto']; ?>" alt="" height="60px" width="60px" class="circle valign profile-image">
                            </div>
                            <div class="col col s8 m8 l8">
                                <ul id="profile-dropdown" class="dropdown-content">
                                    <li><a href="?menu=profil"><i class="mdi-action-face-unlock"></i> Profile</a>
                                    </li>
                                    <li><a href="?menu=user"><i class="mdi-action-settings"></i> Settings</a>
                                    </li>
                                    <li><a onClick="swal({
                                            title: 'Anda yakin?',
                                            text: 'Anda harus login lagi jika anda keluar.',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#DD6B55',
                                            confirmButtonText: 'Ya, keluar!',
                                            cancelButtonText: 'Tidak, Batalkan!',
                                            closeOnConfirm: false,
                                            closeOnCancel: false
                                          },
                                          function(isConfirm){
                                            if (isConfirm) {
                                              window.location.href='logout.php'
                                            } else {
                                                swal('Dibatalkan', 'Silahkan lanjut :)', 'success');
                                            }
                                          });" href="#" ><i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                                    </li>
                                </ul>
                                <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><i class="mdi-navigation-arrow-drop-down right"></i><?php echo $singkatnama; ?></a>
                                <p class="user-roal"><?php echo $_SESSION['hak']; ?></p>
                            </div>
                        </div>
                    </li>
                    <li class="bold"><a href="?menu=home" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard </a>
                    </li>
                    <li class="bold"><a href="?menu=guru" class="waves-effect waves-cyan"><i class="mdi-social-person"></i> Tutor</a>
                    </li>
                    <li class="bold"><a href="?menu=siswa" class="waves-effect waves-cyan"><i class="mdi-social-people"></i> Warga Belajar</a>
                    </li>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-social-school"></i> Akademik</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="?menu=pelajaran"><i class="mdi-av-my-library-books"></i>Mata Pelajaran</a>
                                        </li>
                                        <li><a href="?menu=kelas"><i class="mdi-action-home"></i> Kelas</a>
                                        </li>
                                        <li><a href="?menu=kelassiswa"><i class="mdi-action-account-box"></i> Kelas Siswa</a>
                                        </li>
                                        <li><a href="?menu=nilai"><i class="mdi-action-grade"></i>Nilai</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class=" mdi-maps-local-print-shop"></i> Cetak</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="?menu=laporansiswa"><i class="mdi-action-description"></i>Laporan Siswa</a>
                                        </li>
                                        <li><a href="?menu=laporanguru"><i class="mdi-action-description"></i>Laporan Tutor</a>
                                        </li>
                                        <li><a href="?menu=laporankelas"><i class="mdi-action-description"></i>Laporan Kelas</a>
                                        </li>
                                        <li><a href="?menu=laporannilai"><i class="mdi-action-description"></i>Laporan Nilai</a>
                                        </li>
                                        <li><a href="?menu=laporanpelajaran"><i class="mdi-action-description"></i>Lap. Pelajaran</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
                <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu" ></i></a>
            </aside>
            <!-- Tutup left-sidebar-nav -->

            <!-- Buka content -->
            <section id="content">
               <!-- Buka breadcrumbs -->
                <div id="breadcrumbs-wrapper" class=" grey lighten-3">
                    <div class="container">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <h5 class="breadcrumbs-title">
                                <?php
                                          if($url=="home"){$judul="Home";}
                                          elseif($url=="guru"){$judul="Guru";}
                                          elseif($url=="siswa"){$judul="Siswa";}
                                          elseif($url=="pelajaran"){$judul="Mata Pelajaran";}
                                          elseif($url=="kelas"){$judul="Kelas";}
                                           elseif($url=="kelassiswa"){$judul="Kelas Siswa";}
                                          elseif($url=="nilai"){$judul="Nilai";}
                                          elseif($url=="profil"){$judul="Profil";}
                                          elseif($url=="user"){$judul="User";}
                                          elseif($url=="laporansiswa"){$judul="Laporan Siswa";}
                                          elseif($url=="laporanguru"){$judul="Laporan Guru";}
                                          elseif($url=="laporankelas"){$judul="Laporan Kelas";}
                                          elseif($url=="laporannilai"){$judul="Laporan Nilai";}
                                          elseif($url=="laporanpelajaran"){$judul="Laporan Pelajaran";}
                                          else {$judul="Home";}
                                    echo $judul;
                                ?>
                                </h5>
                                <ol class="breadcrumb">
                                <?php
                                    if(isset($_GET['menu'])){
                                        if($url=="home"){ ?>  <li class="active">Dashboard</li> <?php }
                                        elseif($url=="guru"){ if($url2=="tambahdataguru"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=guru">Tutor</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdataguru"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=guru">Tutor</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Guru</li> <?php } }
                                        elseif($url=="siswa"){ if($url2=="tambahdatasiswa"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=siswa">Warga Belajar</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatasiswa"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=siswa">Warga Belajar</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Siswa</li> <?php } }
                                         elseif($url=="pelajaran"){ if($url2=="tambahdatapelajaran"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=pelajaran">Mata Pelajaran</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatapelajaran"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=pelajaran">Mata Pelajaran</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Mata Pelajaran</li> <?php } }
                                         elseif($url=="kelas"){ if($url2=="tambahdatakelas"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=kelas">Kelas</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatakelas"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=kelas">Kelas</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Kelas</li> <?php } }
                                        elseif($url=="kelassiswa"){ if($url2=="tambahdatakelassiswa"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=kelassiswa">Kelas Siswa</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatakelassiswa"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=kelassiswa">Kelas Siswa</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Kelas Siswa</li> <?php } }
                                         elseif($url=="nilai"){ if($url2=="tambahdatanilai"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=nilai">Nilai</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatanilai"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=nilai">Nilai</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">Nilai</li> <?php } }
                                         elseif($url=="user"){ if($url2=="tambahdatauser"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=user">User</a></li> <li class="active">Tambah Data</li> <?php }
                                                            elseif($url2=="editdatauser"){ ?> <li><a href="?menu=home">Dashboard</a></li> <li><a href="?menu=user">User</a></li> <li class="active">Edit Data</li> <?php }
                                                            else { ?> <li><a href="?menu=home">Dashboard</a></li>
                                                            <li class="active">User</li> <?php } }
                                         elseif($url=="profil"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Profil</li> <?php }
                                         elseif($url=="laporansiswa"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Laporan Siswa</li> <?php }
                                         elseif($url=="laporanguru"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Laporan Tutor</li> <?php }
                                         elseif($url=="laporankelas"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Laporan Kelas</li> <?php }
                                         elseif($url=="laporannilai"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Laporan Nilai</li> <?php }
                                         elseif($url=="laporanpelajaran"){ ?> <li><a href="?menu=home">Dashboard</a></li>
                                                                    <li class="active">Laporan Pelajaran</li> <?php }
                                    } else {
                                        echo "<li class='active'>Dashboard</li>";
                                }
                                ?>
                                </ol>
                             </div>
                         </div>
                    </div>
                </div>
 <!-- Tutup breadcrumbs -->
                    <?php
                        if(isset($_GET['menu'])){
                            $menu=$_GET['menu'];
                            if($menu=="guru"){include "menu/guru.php";}
                            elseif($menu=="siswa"){include "menu/siswa.php";}
                            elseif($menu=="pelajaran"){include "menu/pelajaran.php";}
                            elseif($menu=="kelas"){include "menu/kelas.php";}
                            elseif($menu=="kelassiswa"){include "menu/kelassiswa.php";}
                            elseif($menu=="nilai"){include "menu/nilai.php";}
                            elseif($menu=="profil"){include "menu/profil.php";}
                            elseif($menu=="user"){include "menu/user.php";}
                            elseif($menu=="laporansiswa"){include "menu/laporansiswa.php";}
                            elseif($menu=="laporanguru"){include "menu/laporanguru.php";}
                            elseif($menu=="laporankelas"){include "menu/laporankelas.php";}
                            elseif($menu=="laporannilai"){include "menu/laporannilai.php";}
                            elseif($menu=="laporanpelajaran"){include "menu/laporanpelajaran.php";}
                            else {include "menu/home.php";}
                        } else {include "menu/home.php";}
                    ?>
            </section> <!-- Tutup content -->
        </div><!-- TUTUP WRAPPER -->
    </div>
    <!-- TUTUP MAIN -->

    <!-- ############################################################################### -->

    <!-- BUKA FOOTER -->
    <footer class="page-footer">
    <div class="footer-copyright info lighten-2">
      <div class="container">
       <span>2019 © Sistem Nilai Raport Online</span> <span class="right">Developed by <a class="grey-text text-lighten-4" href="#">Direktorat Pembinaan Pendidikan Keaksaraan dan Kesetaraan</a></span>
        </div>
    </div>
  </footer>
    <!-- TUTUP FOOTER -->


    <!-- =======   Scripts ======== -->
    <script type="text/javascript" src="../datatables/media/js/jquery.dataTables.min.js"></script>
    <link href="../datatables/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    $(document).ready(function() {
   $('#tabelku').DataTable({
    "language": {
    "sProcessing":   "Sedang memproses...",
    "sLengthMenu":   "Tampilkan _MENU_ entri",
    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
    "sInfoPostFix":  "",
    "sSearch":       "Cari:",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Pertama",
        "sPrevious": "Sebelumnya",
        "sNext":     "Selanjutnya",
        "sLast":     "Terakhir"
    }
}
});
     } );
 </script>

  <?php
  include "../validasi_form.php";
  ?>



    <!--materialize js-->
    <script type="text/javascript" src="../js/materialize.min.js"></script>
        <!--scrollbar-->
    <script type="text/javascript" src="../js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!--prism-->
    <script type="text/javascript" src="../js/prism.js"></script>
     <!-- data-tables -->
    <script type="text/javascript" src="../js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/plugins/data-tables/data-tables-script.js"></script>
      <!-- chartist -->
    <script type="text/javascript" src="../js/plugins/chartist-js/chartist.min.js"></script>

    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="../js/plugins.js"></script>
</body>

</html>
<?php
}
else{
    echo "<html>
            <head>
            <link href='../css/sweetalert.css' type='text/css' rel='stylesheet' media='screen,projection'>
            <script type='text/javascript' src='../js/sweetalert.min.js'></script>
            </head>
            <body>
                <script>swal({
            title: 'Akses Ditolak',
            text: 'Anda harus login terlebih dahulu!',
            type: 'error'
            },
            function(){
                window.location.href='../index.php';
            }); </script>
            </body>
          </html>";
}
?>
