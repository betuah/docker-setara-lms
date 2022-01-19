<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

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
							<h2>Kegiatan Pembelajaran</h2>
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
							<th class="text-center">Mata Pelajaran</th>
							<th class="text-center">Nama Kegiatan Pembelajaran</th>
							<th class="text-center" style="width: 70px;">Tgl Buat</th>
							<th class="text-center" style="width: 100px;">Aksi</th>
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
		$(document).ready(function() {
		});
	</script>
	<script>
		var table = $('#datatable').DataTable({
			lengthChange: true,
          	order: [[ 0, "asc" ]],
          	processing: true,
          	serverSide: true,
          	language: {
        		processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        	},
          	ajax: {
            	url: "API/Modul/",
            	data: function ( d ) { d.action = "getAll", d.mapel = <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : 0;?> }
          	},
          	rowsGroup :[0],
          	columnDefs: [
                {"className": "font-weight-bold text-center", "targets": [0]},
                {
                <?php
                	if ($_SESSION['admin_status'] == 'superadmin') {
        		?>
				    render: function ( data, type, row ) {
				        return '<a onClick="view(\''+ row[3] +'\')" class="btn btn-primary btn-sm" style="margin-bottom: 5px;" title="Lihat detail kegiatan pembelajaran"><i class="fa fa-search"></i></a> '+
				        	'<a onClick="update(\''+ row[3] +'\')" class="btn btn-warning btn-sm" style="margin-bottom: 5px;" title="Ubah data kegiatan pembelajaran"><i class="fa fa-pencil"></i></a> '+
				        	'<a onClick="remove(\''+ row[3] +'\')" class="btn btn-danger btn-sm" style="margin-bottom: 5px;" title="Hapus kegiatan pembelajaran"><i class="fa fa-trash"></i></a>';
				    },
            		"targets": 3
        		<?php
                	}else{
               	?>
               		render: function ( data, type, row ) {
				        return '<a onClick="view(\''+ row[3] +'\')" class="btn btn-primary btn-sm" title="Lihat detail kegiatan pembelajaran"><i class="fa fa-search"></i></a>';
				    },
            		"targets": 3
               	<?php
                	}
            	?>
	            },
                {"className": "text-center", "targets": [2,3]},
                {
					"searchable": false,
					"orderable": false,
					"targets": 3
				},
          	],
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
      				url: 'API/Modul/',
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
