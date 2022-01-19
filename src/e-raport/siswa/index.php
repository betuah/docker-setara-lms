<?php
session_start();
include"../config/connect.php";
if(isset($_SESSION['user']) AND isset($_SESSION['pass'])){
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
$singkatnama=substr($_SESSION['nama'], 0, 9);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Raport</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons-->
    <link href='images/kemdikbud.png' rel='icon' type='image/gif'/>
    <!-- Favicons-->

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
     <!--sweetalert js-->
    <script type="text/javascript" src="../js/sweetalert.min.js"></script>

</head>

<body>


    <!-- ############################################################################### -->

    <!-- BUKA TAG HEADER-->
    <header id="header" class="page-topbar">
       <!-- Buka div navbar-fixed -->
        <div class="navbar-fixed">
            <nav class="red lighten-2">
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
                                <img src="../admin/foto/<?php echo $_SESSION['foto']; ?>" alt="" height="60px" width="60px" class="circle valign profile-image">
                            </div>
                            <div class="col col s8 m8 l8">
                                <ul id="profile-dropdown" class="dropdown-content">
                                    <li><a href="?menu=profil"><i class="mdi-action-face-unlock"></i> Profile</a>
                                    </li>
                                    <li><a href="?menu=usersiswa"><i class="mdi-action-settings"></i> Settings</a>
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
                    <li><a href="?menu=nilai"><i class="mdi-av-my-library-books"></i>Nilai Raport</a>
                    </li>
                    <li><a href="?menu=raport"><i class="mdi-av-my-library-books"></i>Raport</a>
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
                                          if($url=="nilai"){$judul="Raport Siswa";}
                                          elseif($url=="profil"){$judul="Profil";}
                                          elseif($url=="usersiswa"){$judul="Pengaturan";}
                                          else {$judul="Raport Siswa";}
                                    echo $judul;
                                ?>
                                </h5>
                                <ol class="breadcrumb">
                                Laporan Nilai Siswa
                                </ol>
                             </div>
                         </div>
                    </div>
                </div>
 <!-- Tutup breadcrumbs -->
                    <?php
                        if(isset($_GET['menu'])){
                            $menu=$_GET['menu'];
                            if($menu=="nilai"){include "menu/nilai.php";}
                            elseif($menu=="profil"){include "menu/profil.php";}
                            elseif($menu=="usersiswa"){include "menu/usersiswa.php";}
                            elseif($menu=="raport"){include "menu/raport.php";}
                            else {include "menu/nilai.php";}
                        } else {include "menu/nilai.php";}
                    ?>
            </section> <!-- Tutup content -->
        </div><!-- TUTUP WRAPPER -->
    </div>
    <!-- TUTUP MAIN -->

    <!-- ############################################################################### -->

    <!-- BUKA FOOTER -->
    <footer class="page-footer">
    <div class="footer-copyright red lighten-2">
      <div class="container">
       <span>2015 © Sistem Nilai Raport Online</span> <span class="right">Developed by <a class="grey-text text-lighten-4" href="http://www.facebook.com/rednubi">Ardika Darwis</a></span>
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
