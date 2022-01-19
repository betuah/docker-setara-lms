<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

$kelasClass = New Kelas();
$userClass	= New User();

// $data		= $kelasClass->getInfoKelas('5979e17e865eacda4c8b456b');
// print_r($_SESSION);

?>
<link rel="stylesheet" href="../assets/css/lib/bootstrap-select/bootstrap-select.css"/>
<link rel="stylesheet" href="../assets/css/separate/vendor/select2.css">
<link rel="stylesheet" href="../assets/css/lib/bootstrap-select/ajax-bootstrap-select.css"/>
<link rel="stylesheet" href="../assets/css/separate/pages/activity.min.css">
<link rel="stylesheet" href="../assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../assets/css/separate/vendor/datatables-net.min.css">
	<div class="page-content">
		<div class="container-fluid">
			<header class="section-header">
				<div class="row">
					<div class="col-md-9">
						<div class="tbl-cell">
							<h2>Laporan Aktivitas Perorangan</h2>
							<div class="subtitle">seTARA Daring </div>
						</div>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block">
					<h5 class="with-border">Cari Pengguna berdasarkan</h5>
					<form method="post" id="form-filter" onsubmit="return false;">
					<div class="row">
						<div class="col-md-5">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Satuan Pendidikan</label>
								<?php
									if ($_SESSION['admin_status'] == 'superadmin') {
										echo '<select name="ID" id="sekolah" class="with-ajax form-control" data-live-search="true" required></select>';
									}elseif ($_SESSION['admin_status'] == 'pengawas' || $_SESSION['admin_status'] == 'kepsek') {
										echo '<select name="ID" class="form-control" data-live-search="true" required>';
											echo "<option value=''>-- Pilih Salah Satu --</option>";
											$dataUser = $userClass->GetData($_SESSION['admin_id']);
											if (!empty($dataUser['mengawasi'])){
												$dataSekolah = $kelasClass->userSekolah($dataUser['mengawasi']);
												foreach ($dataSekolah as $value) {
													echo "<option value='$value[id]'>$value[nama] ($value[npsn])</option>";
												}
											}
										echo '</select>';
									}
								?>
							</fieldset>
						</div>
						<div class="col-md-3">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Pilih Peran</label>
								<select name="peran" id="listperan" class="form-control">
									<option value="0">Semua</option>
									<option value="1">Administrator Kelas</option>
									<option value="2">Guru Mata Pelajaran</option>
									<option value="3">Tutor</option>
									<option value="4">Siswa / Anggota</option>
								</select>
							</fieldset>
						</div>
						
						<!-- <div class="col-md-2">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Bulan</label>
								<select name="bulan" id="bulan" class="form-control" required>
									<option value="01">Januari</option>
									<option value="02">Februari</option>
									<option value="03">Maret</option>
									<option value="04">April</option>
									<option value="05">Mei</option>
									<option value="06">Juni</option>
									<option value="07">Juli</option>
									<option value="08">Agustus</option>
									<option value="09">September</option>
									<option value="10">Oktober</option>
									<option value="11">November</option>
									<option value="12">Desember</option>
								</select>
							</fieldset>
						</div>
						<div class="col-md-2">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Tahun</label>
								<select name="tahun" id="tahun" class="form-control" required>
									<?php
										//for($i=date('Y'); $i>=2017; $i--){
											//echo '<option value="'.$i.'">'.$i.'</option>';
										//}
									?>
								</select>
							</fieldset>
						</div> -->
						<!-- <div class="col-md-4">
							<fieldset class="form-group">
								<label for="ajax-select semibold form-label" class="form-control-label">Pilih Nama Pengguna</label>
								<select name="listpengguna" id="listpengguna" class="form-control select2" required>
									<option value="">-- Pilih Sekolah --</option>
								</select>
							</fieldset>
						</div> -->
					</div>
					
					<input type="submit" class="btn btn-primary" name="" value="Cari" />
					</form>
					<div id="filterdata" style="display: none;">
						<hr>
						<table id="datatable" class="order-column display table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="text-center">Nama Pengguna (username)</th>
									<!-- <th class="text-center">Peran</th> -->
									<th class="text-center" style="width: 75px;">Aksi</th>
								</tr>
							</thead>
							<tbody id="isi">
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div><!--.container-fluid-->
	</div><!--.page-content-->
<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="../assets/js/lib/bootstrap-select/ajax-bootstrap-select.min.js"></script>
	<script>
	var otable;
	var dataTab;
		// ----> AJAX Bootstrap Select
		var optionAllSekolah = {
	        ajax          : {
	            url     : '../includes/option-sekolah.php',
	            type    : 'POST',
	            dataType: 'json',
	            // Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
	            // automatically replace it with the value of the search query.
	            data    : {
	                q: '{{{q}}}',
	                t: 'select'
	            }
	        },
	        locale        : {
	            emptyTitle: '-- Pilih Salah Satu --'
	        },
	        log           : 3,
	        preprocessData: function (data) {
	            var i, l = data.length, array = [];
	            if (l) {
	                for (i = 0; i < l; i++) {
	                    array.push($.extend(true, data[i], {
	                        text : data[i].text,
	                        value: data[i].id,
	                        data : {
	                            subtext: '( NPSN : '+data[i].npsn+' )'
	                        }
	                    }));
	                }
	            }
	            // You must always return a valid array when processing data. The
	            // data argument passed is a clone and cannot be modified directly.
	            return array;
	        }
	    };

	    $('#sekolah').selectpicker().filter('.with-ajax').ajaxSelectPicker(optionAllSekolah);
	// 	$('select').trigger('change');
	//    // ----> END AJAX Bootstrap Select

		oTable = $('#datatable').DataTable({
            "bProcessing": true,
            // "bServerSide": true,
            "aaData": dataTab,
            //important  -- headers of the json
            "aoColumns": [{ "mDataProp": "name" }, { "mDataProp": "link" }],
            // "sPaginationType": "full_numbers",
            "aaSorting": [[0, "asc"]],
            // "bJQueryUI": true,
            "columnDefs": [ {
			      	"targets": 1,
			      	"searchable": false,
			    	"orderable": false,
			    	"className": "text-center"
			} ]
        });


	$('#form-filter').submit(function() {
		var fd = new FormData(this);
		fd.append('action','getbySchool');
		$.ajax({
				type: 'POST',
				url: 'API/Kelas/',
				data: fd,
				contentType: false,
				processData: false,
				success: function(res){

			        //when the instance of datatable exists, only pass the data :D
			        // oTable.fnClearTable();
			        // oTable.fnAddData(dataTab);
			        // oTable.draw(dataTab);
					// var isi, peran;
					// $.each(res.data.list,function(index, value){
					// 	// if (value.role == "1") {
					// 	// 	peran = 'Administrator Kelas';
					// 	// }else if(value.role == "2"){
					// 	// 	peran = 'Guru Mata Pelajaran';
					// 	// }else if(value.role == "3"){
					// 	// 	peran = 'Tutor';
					// 	// }else{
					// 	// 	peran = 'Siswa / Anggota';
					// 	// }

					//     isi += '<tr>'+
					//     			'<td>'+value.nama+' ('+value.user+') </td>'+
					//     			// '<td>'+ peran +'</td>'+
					//     			'<td>'+ value.id +'</td>'+
					//     		'</tr>';
					// });
					if (res.data.jumlah > 0) {
						dataTab = res.data.list;
						$('#filterdata').show();
						oTable.clear().draw();
					   	oTable.rows.add(dataTab); // Add new data
					   	oTable.columns.adjust().draw(); // Redraw the DataTable
					   	$('[data-toggle="tooltip"]').tooltip();
				   	}else{
				   		$('#filterdata').show();
				   		oTable.clear().draw();
						$('[data-toggle="tooltip"]').tooltip();
				   	}


		//				swal({
	    //                 title: res.response,
	    //                 text: res.message,
	    //                 type: res.icon
	    //             }, function() {
	    //                 if (res.icon != 'error') {
	    //                     if(res.page == 'manage'){
	    //                         window.location = './manage';
	    //                     }else{
	    //                         window.location = './';
	    //                     }
	    //                 }    
     	//            	});
				},
				error: function(){
                // swal({
                //     title: res.response,
                //     text: res.message,
                //     type: res.icon
                // }, function() {
                //      window.location = './';
                // });
				}
			});
	});






	//	$('select').on('change', function() {
	//		alert( "Handler for .change() called." );
	// });

	// $( "#sekolah" ).change(function() {
	// 	if ($(this).val()) {
	// 		// alert('Handler for .change() called.');
	// 		$.ajax({
  	//     				type: 'POST',
  	//     				url: 'API/Kelas/',
  	//     				data: {"action": "getbySchool", "ID": $(this).val(), "peran":$('#listperan').val()},
  	//     				success: function(res) {
  	//     					// console.log(res.data.listSeluruh.length);
  	//     					var isi = '<option value="">-- Pilih Salah Satu --</option>';
  	//     					// for (var i = 0; i < res.data.jumlah; i++) {
  	//     					// 	isi += '<option value="'+ res.data.listSeluruh[i].id+'">'+res.data.listSeluruh[i].nama+'</option>';
  	//     					// }
  	//     					if (res.data.jumlah) {
	//      					$.each(res.data.list,function(index, value){
		// 					    isi += '<option value="'+value.id+'">'+value.nama+' ('+value.user+') </option>';
		// 					});
		// 				}

  	//     					$('#listpengguna').html(isi);


  	//     					// var isi = '<option value="">-- Pilih Salah Satu --</option>';
  	//     					// for (var j = 0; j < res.data.data.length; j++) {
	//      				// 	for (var i = 0; i < res.data.data[j].list_member.length; i++) {
	//      				// 		isi += '<option value="'+res.data.data[j].list_member[i]+'">'+res.data.data[j].list_member[i]+'</option>';
	//      				// 	}
	//      				// }

  	//     					// $('#listpengguna').html(isi);
 	//     				},
  	//     				error: function () {
  	//     					swal("Error!", "Error fetching data!", "error");
  	//     				}
  	//     			});
		// 	}
		// });

		// $( "#listperan" ).change(function() {
		// 	if ($('#sekolah').val()) {
		// 		// alert('Handler for .change() called.');
		// 		$.ajax({
  	//     				type: 'POST',
  	//     				url: 'API/Kelas/',
  	//     				data: {"action": "getbySchool", "ID": $('#sekolah').val(), "peran":$(this).val()},
  	//     				success: function(res) {
  	//     					// console.log(res.data.listSeluruh.length);
  	//     					var isi = '<option value="">-- Pilih Salah Satu --</option>';
  	//     					// for (var i = 0; i < res.data.jumlah; i++) {
  	//     					// 	isi += '<option value="'+ res.data.listSeluruh[i].id+'">'+res.data.listSeluruh[i].nama+'</option>';
  	//     					// }
  	//     					if (res.data.jumlah) {
	//      					$.each(res.data.list,function(index, value){
		// 					    isi += '<option value="'+value.id+'">'+value.nama+' ('+value.user+') </option>';
		// 					});
		// 				}

  	//     					$('#listpengguna').html(isi);


  	//     					// var isi = '<option value="">-- Pilih Salah Satu --</option>';
  	//     					// for (var j = 0; j < res.data.data.length; j++) {
	//      				// 	for (var i = 0; i < res.data.data[j].list_member.length; i++) {
	//      				// 		isi += '<option value="'+res.data.data[j].list_member[i]+'">'+res.data.data[j].list_member[i]+'</option>';
	//      				// 	}
	//      				// }

  	//     					// $('#listpengguna').html(isi);
  	//     				},
  	//     				error: function () {
  	//     					swal("Error!", "Error fetching data!", "error");
  	//     				}
  	//     			});
		// 	}else{
		// 		alert('Pilih Sekolah terlebih dahulu!');
		// 	}
		// });

		// $(function() {
		// 	var table = $('#datatable').DataTable({
		// 		lengthChange: true,
  	//             	order: [[ 0, "asc" ]],
  	//             	processing: true,
  	//             	serverSide: true,
  	//             	ajax: {
  	//               	url: "API/Kelas/",
  	//               	data: function ( d ) { d.action = "getAll", d.sekolah = <?=isset($_GET['id']) ? '"'.$_GET['id'].'"' : 0;?> }
  	//             	},
  	//             	rowsGroup: [0],
  	//             	columnDefs: [
  	//             		{

	//               		render: function ( data, type, row ) {
		// 			         return '<a href="../kelas.php?id='+row[4]+'" target="_blank" title="Lihat halaman kelas">'+row[1]+'</a>';
		// 			    },
  	//               		"targets": 1
		//             },
	//                {
  	//               	<?php
	//                	if ($_SESSION['admin_status'] == 'admin') {
  	//           		?>
		// 			    render: function ( data, type, row ) {
		// 			        return '<a onClick="view(\''+ row[4] +'\')" class="btn btn-info btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat detail kelas"><i class="fa fa-search"></i></a> '+
		// 			        	'<a href="mata_pelajaran.php?id='+row[4]+'" class="btn btn-success btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Mata Pelajaran" target="_blank"><i class="fa fa-book"></i></a> '+
		// 			        	'<a onClick="update(\''+ row[4] +'\')" class="btn btn-warning btn-sm" style="margin: 0 2px 5px 2px;" title="Ubah data kelas"><i class="fa fa-pencil"></i></a> '+
		// 			        	'<a onClick="remove(\''+ row[4] +'\')" class="btn btn-danger btn-sm" style="margin: 0 2px 5px 2px;" title="Hapus kelas"><i class="fa fa-trash"></i></a>';
		// 			    },
  	//               		"targets": 4
  	//           		<?php
	//                	}else{
	//               	?>
	//               		render: function ( data, type, row ) {
		// 			        return '<a onClick="view(\''+ row[4] +'\')" class="btn btn-primary btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat detail kelas"><i class="fa fa-search"></i></a>'+
		// 			        	'<a href="mata_pelajaran.php?id='+row[4]+'" class="btn btn-warning btn-sm" style="margin: 0 2px 5px 2px;" title="Lihat Mata Pelajaran" target="_blank"><i class="fa fa-book"></i></a>';
		// 			    },
  	//               		"targets": 4
	//               	<?php
	//                	}
  	//               	?>
		//             },
	//                {"className": "text-center", "targets": [0,1,2,3,4]},
	//                {
  	//   					"searchable": false,					
  	//   					"orderable": false,
  	//   					"targets": 4
  	//   				},
  	//             	]
		// 	});

		// 	// setTimeout(function() {
		// 	// 	table.draw();
		// 	// }, 50);

		// });

		// function remove(ID){
  	//     		swal({
  	//     		  title: "Apakah anda Yakin?",
  	//     		  text: "Data akan terhapus secara permanen!",
  	//     		  type: "error",
  	//     		  showCancelButton: true,
  	//     			confirmButtonClass: "btn-danger",
  	//     			confirmButtonText: "Yes, delete it!",
  	//     		  closeOnConfirm: false,
  	//     		  showLoaderOnConfirm: true
  	//     		}, function () {
  	//     			$.ajax({
  	//     				type: 'POST',
  	//     				url: 'API/Kelas/',
  	//     				data: {"act": "remv", "ID": ID},
  	//     				success: function(res) {
  	//     					swal(res.response, res.message, res.icon);
  	//               		table.ajax.reload();
  	//     				},
  	//     				error: function () {
  	//     					swal("Error!", "Error Deleting data!", "error");
  	//     				}
  	//     			});
  	//     		});
  	//     	}
	</script>
<?php
	require('includes/footer-bottom.php');
?>
