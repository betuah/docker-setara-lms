<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

// echo $_SERVER['SERVER_ADDR'];
// print_r($lastPosting);

?>
<link rel="stylesheet" href="../assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../assets/css/separate/vendor/datatables-net.min.css">
	<div class="page-content">
		<div class="container-fluid">
			<header class="section-header">
				<div class="row">
					<div class="col-md-9">
						<div class="tbl-cell">
							<h2>Kelas</h2>
							<div class="subtitle">SIAJAR LMS </div>
						</div>
					</div>
					<div class="col-md-3" style="text-align: right;">
						<?php
			            if ($_SESSION['admin_status'] == 'superadmin' || $_SESSION['admin_status'] == 'admin') {
			              echo '<a class="btn btn-primary" href="?action=tambah" data-toggle="tooltip" title="Tambah Baru">Tambah Baru</a>';
			            }
			            ?>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block table-responsive">
					<table id="datatable" class="order-column display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th class="text-center">Sekolah/Institusi</th>
							<th class="text-center">Nama Kelas</th>
							<th class="text-center" style="width: 50px;">Status</th>
							<th class="text-center" style="width: 50px;">Tanggal Buat</th>
							<th class="text-center" style="width: 75px;">Aksi</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
<?php
	require('includes/footer-top.php');
?>
	<script>
		var table = $('#datatable').DataTable({
			lengthChange: true,
          	order: [[ 0, "asc" ],[3, 'desc']],
          	processing: true,
          	language: {
        		processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        	},
          	serverSide: true,
          	ajax: {
            	url: "API/Kelas/",
            	data: function ( d ) { d.action = "getAll", d.sekolah = <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : 0;?> }
          	},
          	rowsGroup: [0],
          	columnDefs: [
          		{

               		render: function ( data, type, row ) {
				         return '<a href="../kelas.php?id='+row[4]+'" target="_blank" title="Lihat halaman kelas">'+row[1]+'</a>';
				    },
            		"targets": 1
	            },
                {
            	<?php
                	if ($_SESSION['admin_status'] == 'superadmin') {
        		?>
				    render: function ( data, type, row ) {
				        return '<a onClick="view(\''+ row[4] +'\')" class="btn btn-info btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat detail kelas"><i class="fa fa-search"></i></a> '+
				        	'<a href="mata_pelajaran.php?id='+row[4]+'" class="btn btn-success btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Mata Pelajaran" target="_blank"><i class="fa fa-book"></i></a> '+
				        	'<a onClick="update(\''+ row[4] +'\')" class="btn btn-warning btn-sm" style="margin: 0 2px 5px 2px;" title="Ubah data kelas"><i class="fa fa-pencil"></i></a> '+
				        	'<a onClick="remove(\''+ row[4] +'\')" class="btn btn-danger btn-sm" style="margin: 0 2px 5px 2px;" title="Hapus kelas"><i class="fa fa-trash"></i></a>';
				    },
            		"targets": 4
        		<?php
                	}elseif($_SESSION['admin_status'] == 'admin'){
                ?>
                	render: function ( data, type, row ) {
				        return '<a onClick="view(\''+ row[4] +'\')" class="btn btn-info btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat detail kelas"><i class="fa fa-search"></i></a> '+
				        	'<a href="mata_pelajaran.php?id='+row[4]+'" class="btn btn-success btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Mata Pelajaran" target="_blank"><i class="fa fa-book"></i></a> '+
				        	'<a onClick="update(\''+ row[4] +'\')" class="btn btn-warning btn-sm" style="margin: 0 2px 5px 2px;" title="Ubah data kelas"><i class="fa fa-pencil"></i></a> ';
				    },
            		"targets": 4
                <?php
                	}else{
               	?>
               		render: function ( data, type, row ) {
				        return '<a onClick="view(\''+ row[4] +'\')" class="btn btn-primary btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat detail kelas"><i class="fa fa-search"></i></a>'+
				        	<!--'<a href="test.php?id='+row[4]+'" class="btn btn-success btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Aktifitas Kelas" target="_blank"><i class="fa fa-list-alt"></i></a>'+-->
				        	'<a href="mata_pelajaran.php?id='+row[4]+'" class="btn btn-warning btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Mata Pelajaran" target="_blank"><i class="fa fa-book"></i></a>';
				    },
            		"targets": 4
               	<?php
                	}
            	?>
	            },
                {"className": "text-center", "targets": [0,1,2,3,4]},
                {
					"searchable": false,
					"orderable": false,
					"targets": 4
				},
          	]
		});

		function remove(ID){
      		swal({
      		  title: "Apakah anda Yakin?",
      		  text: "Data akan terhapus secara permanen!",
      		  type: "error",
      		  showCancelButton: true,
      			confirmButtonClass: "btn-danger",
      			confirmButtonText: "Yes, delete it!",
      		  closeOnConfirm: false,
      		  showLoaderOnConfirm: true
      		}, function () {
      			$.ajax({
      				type: 'POST',
      				url: 'API/Kelas/',
      				data: {"action": "remv", "ID": ID},
      				success: function(res) {
      					swal(res.response, res.message, res.icon);
                		table.ajax.reload();
      				},
      				error: function () {
      					swal("Error!", "Error Deleting data!", "error");
      				}
      			});
      		});
      	}
	</script>
<?php
	require('includes/footer-bottom.php');
?>
