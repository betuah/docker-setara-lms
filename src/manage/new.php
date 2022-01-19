<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

// print_r($lastPosting);

if (!isset($_GET['id']) || empty($_GET['id'])) {
	header('Location: aktifitas.php');
}

?>
<link rel="stylesheet" href="../assets/css/separate/vendor/bootstrap-daterangepicker.min.css">
<link rel="stylesheet" href="../assets/css/lib/bootstrap-select/bootstrap-select.css"/>
<link rel="stylesheet" href="../assets/css/lib/bootstrap-select/ajax-bootstrap-select.css"/>
<link rel="stylesheet" href="../assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../assets/css/separate/vendor/datatables-net.min.css">
	<div class="page-content">
		<div class="container-fluid">
			<header class="section-header">
				<div class="row">
					<div class="col-md-9">
						<div class="tbl-cell">
							<h2>Aktifikas Kelas</h2>
							<div class="subtitle">SIAJAR LMS </div>
						</div>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block table-responsive">
					<h5 class="with-border">Lihat detail</h5>
					<form method="post" id="form-filter" onsubmit="return false;">
					<div class="row">
						<div class="col-md-4">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Jangka Waktu</label>
								<input id="daterange2" name="waktu" type="text" class="form-control">
								<input name="user" type="hidden" value="<?=$_GET['id']?>">
							</fieldset>
						</div>
					</div>
					
					<input type="submit" class="btn btn-primary" name="" value="Cari" />
					</form>

					<div id="filterdata" style="display: none;">
						<hr>
						<table id="datatable" class="order-column display table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
							<tr>
								<th class="text-center" style="width: 200px;">Nama Pengguna</th>
								<th class="text-center">Kelas</th>
								<th class="text-center" style="width: 200px;">Durasi</th>
								<th class="text-center" style="width: 50px;">Rincian</th>
							</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="../assets/js/lib/moment/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="../assets/js/lib/bootstrap-select/ajax-bootstrap-select.min.js"></script>
	<script type="text/javascript" src="../assets/js/lib/daterangepicker/daterangepicker.js"></script>
	<script>
		$('#daterange2').daterangepicker();

		var oTable;
		var dataTab;
		oTable = $('#datatable').DataTable({
            // "bServerSide": true,
            "bProcessing": true,
            "aaData": dataTab,
            //important  -- headers of the json
            "aoColumns": [{ "mDataProp": "nama" }, { "mDataProp": "kelas" }, { "mDataProp": "durasi" }, { "mDataProp": "link" }],
            "aaSorting": [[1, "desc"]],
            "rowsGroup" :[0],
            "columnDefs": [ {
			      	"targets": [2,3],
			      	"searchable": false,
			    	"orderable": false,
			    	"className": "text-center"
			} ]

        });


		$(document).ready(function() {
			$('#form-filter').submit(function() {
				loading();

				var fd = new FormData(this);
				fd.append('action','getActivity');
	        	$.ajax({
					type: 'POST',
					url: 'API/Kelas/',
					data: fd,
					contentType: false,
					processData: false,
					// data: {action : "getActivity", kelas : <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : "0";?> },
					success: function(res){
						dataTab = res.data.data;
						oTable.clear().draw();
					   	oTable.rows.add(dataTab); // Add new data
					   	oTable.columns.adjust().draw(); // Redraw the DataTable
					   	$('#filterdata').show();
					   	loaded();
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
		});

		// ----> AJAX Bootstrap Select
		// var options = {
	 //        ajax          : {
	 //            url     : '../includes/option-sekolah.php',
	 //            type    : 'POST',
	 //            dataType: 'json',
	 //            // Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
	 //            // automatically replace it with the value of the search query.
	 //            data    : {
	 //                q: '{{{q}}}',
	 //                t: 'select'
	 //            }
	 //        },
	 //        locale        : {
	 //            emptyTitle: 'Pilih Asal Sekolah'
	 //        },
	 //        log           : 3,
	 //        preprocessData: function (data) {
	 //            var i, l = data.length, array = [];
	 //            if (l) {
	 //                for (i = 0; i < l; i++) {
	 //                    array.push($.extend(true, data[i], {
	 //                        text : data[i].text,
	 //                        value: data[i].id,
	 //                        data : {
	 //                            subtext: '( NPSN : '+data[i].npsn+' )'
	 //                        }
	 //                    }));
	 //                }
	 //            }
	 //            // You must always return a valid array when processing data. The
	 //            // data argument passed is a clone and cannot be modified directly.
	 //            return array;
	 //        }
	 //    };

	 //    $('.selectpicker').selectpicker().filter('.with-ajax').ajaxSelectPicker(options);
	 //    $('select').trigger('change');
	    // ----> END AJAX Bootstrap Select

	 //    function filterColumn ( i ) {
		//     $('#datatable').DataTable().column( i ).search(
		//         $('#col'+i+'_filter').val()
		//     ).draw();
		// }
		 
		// $(document).ready(function() {
		// 	var table = $('#datatable').DataTable({
		// 		lengthChange: true,
  //             	order: [[ 2, "asc" ]],
  //             	processing: true,
  //             	serverSide: true,
  //             	ajax: {
  //               	url: "API/Kelas/",
  //               	data: function ( d ) { d.action = "getActivity", d.kelas = <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : 0;?> }
  //             	},
  //             	rowsGroup :[0],
  //             	columnDefs: [
	 //                {"className": "font-weight-bold text-center", "targets": [0]},
	 //                {"className": "text-wrap", "targets": [1]},
	 //                {"className": "text-center", "targets": [2]},
	 //                { "name": "id_user",   "targets": 0 },
		// 		    { "name": "halaman",  "targets": 1 },
		// 		    { "name": "date_created", "targets": 2 }
  //             	],
		// 	});

		//     $('input.column_filter').on( 'change', function () {
		//         filterColumn( $(this).attr('data-column') );
		//     } );
		// } );
	</script>
<?php
	require('includes/footer-bottom.php');
?>
