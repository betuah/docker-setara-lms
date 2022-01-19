<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

// print_r($lastPosting);

if (!isset($_GET['id']) || empty($_GET['id'])) {
	header('Location: aktifitas.php');
}

?>
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
							<h2>Aktifitas Kelas</h2>
							<div class="subtitle">seTARA Daring </div>
						</div>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block table-responsive">
					<h5 class="with-border">Lihat detail</h5>
					<table id="datatable" class="order-column display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th class="text-center" style="width: 120px;">Nama Pengguna</th>
							<th class="text-center">Kelas</th>
							<th class="text-center">Aktifitas</th>
							<th class="text-center" style="width: 100px;">Tanggal</th>
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
	<script type="text/javascript" src="../assets/js/lib/bootstrap-select/ajax-bootstrap-select.min.js"></script>
	<script>
		var oTable;
		var dataTab;
		oTable = $('#datatable').DataTable({
            // "bServerSide": true,
            "bProcessing": true,
            "aaData": dataTab,
            //important  -- headers of the json
            "aoColumns": [{ "mDataProp": "name" }, { "mDataProp": "kelas" }, { "mDataProp": "link" }, { "mDataProp": "tanggal" }],
            "aaSorting": [[2, "desc"]],
            "rowsGroup" :[0,1],
            "columnDefs": [ {
			      	"targets": 3,
			      	"searchable": false,
			    	"orderable": false,
			    	"className": "text-center"
			} ]

        });

		$(document).ready(function() {
			loading();
        	$.ajax({
				type: 'POST',
				url: 'API/Kelas/',
				data: {action : "getdetailActivity", id : <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : "0";?>, kelas : <?=isset($_GET['kelas']) ? '"'.$_GET['kelas'].'"' : "0";?> },
				success: function(res){
					dataTab = res.data.data;
					oTable.clear().draw();
				   	oTable.rows.add(dataTab); // Add new data
				   	oTable.columns.adjust().draw(); // Redraw the DataTable
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
