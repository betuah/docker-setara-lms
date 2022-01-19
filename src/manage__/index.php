<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");

$Dashboardclass = new Dashboard();
$dataDashboard	= $Dashboardclass->CountDashboard();
$lastSignup		= $Dashboardclass->lastRegister();
$lastPosting	= $Dashboardclass->postingan();

// print_r($lastPosting);

?>
<link rel="stylesheet" href="../assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../assets/css/separate/vendor/datatables-net.min.css">
	<div class="page-content">
		<div class="container-fluid">
			<header class="section-header">
				<div class="row">
					<div class="col-md-12">
						<div class="tbl-cell">
							<h2>Halaman Utama</h2>
							<div class="subtitle">SIAJAR LMS </div>
						</div>
						<hr style="margin: 10px 0;">
					</div>
				</div>
			</header>
			<div class="row">
	            <div class="col-md-6">
	                <div class="chart-statistic-box">

	                    <div class="chart-container">
	                        <div class="chart-container-in">
	                            <div id="chart_div"></div>
	                            <header class="chart-container-title">Pendaftar Bulan ini</header>
	                            <div class="chart-container-x">
	                                <div class="item"></div>
	                                <div class="item">tue</div>
	                                <div class="item">wed</div>
	                                <div class="item">thu</div>
	                                <div class="item">fri</div>
	                                <div class="item">sat</div>
	                                <div class="item">sun</div>
	                                <div class="item">mon</div>
	                                <div class="item"></div>
	                            </div>
	                            <div class="chart-container-y">
	                                <div class="item">300</div>
	                                <div class="item"></div>
	                                <div class="item">250</div>
	                                <div class="item"></div>
	                                <div class="item">200</div>
	                                <div class="item"></div>
	                                <div class="item">150</div>
	                                <div class="item"></div>
	                                <div class="item">100</div>
	                                <div class="item"></div>
	                                <div class="item">50</div>
	                                <div class="item"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div><!--.chart-statistic-box-->
	            </div><!--.col-->
	            <div class="col-md-6">
	                <div class="row">
	                    <div class="col-sm-6">
	                        <article class="statistic-box red">
	                            <div>
	                                <div class="number"><?=$dataDashboard['jmlGuru']?></div>
	                                <div class="caption"><div>Guru & Tutor</div></div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    <div class="col-sm-6">
	                        <article class="statistic-box purple">
	                            <div>
	                                <div class="number"><?=$dataDashboard['jmlSiswa']?></div>
	                                <div class="caption"><div>Siswa</div></div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    <div class="col-sm-6">
	                        <article class="statistic-box yellow">
	                            <div>
	                                <div class="number"><?=$dataDashboard['jmlKelas']?></div>
	                                <div class="caption"><div>Kelas</div></div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    <div class="col-sm-6">
	                        <article class="statistic-box green">
	                            <div>
	                                <div class="number"><?=$dataDashboard['jmlModul']?></div>
	                                <div class="caption"><div>Kegiatan Pembelajaran</div></div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                </div><!--.row-->
	            </div><!--.col-->
	        </div><!--.row-->

			<div class="row">
	            <div class="col-md-12 dahsboard-column">
	                <section class="box-typical box-typical-dashboard panel panel-default scrollable">
	                    <header class="box-typical-header panel-heading">
	                        <h3 class="panel-title">Pengguna Baru</h3>
	                    </header>
	                    <div class="box-typical-body panel-body" style="height: 400px">
	                        <table class="table tbl-typical">
	                            <tr>
	                                <th><div>Nama</div></th>
	                                <th align="center"><div>Sekolah/Instansi</div></th>
	                                <th align="center"><div>Status</div></th>
	                                <th align="center"><div>Tanggal</div></th>
	                            </tr>
                            <?php
                            	foreach ($lastSignup as $value) {
                            		echo '<tr>
		                            		<td>'.$value['nama'].'<br><b>('.$value['username'].')</b></td>
			                                <td align="center">'.$value['sekolah'].'</td>
			                                <td class="color-blue-grey" align="center"><span class="semibold">'.ucfirst($value['status']).'</span></td>
			                                <td align="center" width="120px">'.$value['date'].'</td>
			                            </tr>';
                            	}
                            ?>                            
	                        </table>
	                    </div><!--.box-typical-body-->
	                </section><!--.box-typical-dashboard-->

	                <section class="box-typical box-typical-dashboard panel panel-default scrollable">
	                    <header class="box-typical-header panel-heading">
	                        <h3 class="panel-title">Kiriman Terbaru</h3>
	                    </header>
	                    <div class="box-typical-body panel-body" style="height: 400px">
	                    <?php
	                    	foreach ($lastPosting['data'] as $value) {
	                    		echo '<article class="comment-item">
			                            <div class="user-card-row">
			                                <div class="tbl-row">
			                                    <div class="tbl-cell tbl-cell-photo">
			                                        <a href="#">
			                                            <img src="http://sumberbelajar.seamolec.org/Assets/foto/'.$value['user_foto'].'" alt="">
			                                        </a>
			                                    </div>
			                                    <div class="tbl-cell">
			                                        <span class="user-card-row-name"><a href="#">'.$value['user'].'</a> &nbsp;<i class="fa fa-caret-right"></i>&nbsp; <a href="../kelas.php?id='.$value['id_kelas'].'">'.$value['kelas'].'</a></span><br>
			                                        <span class="semibold small">'.selisih_waktu($value['date_created']).'</span>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="comment-item-txt">
			                                <p id="post-'.$value['id_postingan'].'" style="padding: 10px;">';
			                                if (strlen($value['isi_postingan']) > 250) {
			                                	echo substr(nl2br($value['isi_postingan']), 0, 250).' ... <a onclick="readMore(\''.$value['id_postingan'].'\')"><b><u>(baca lebih lanjut)</b></u></a>';
			                                }else{
			                                	echo nl2br($value['isi_postingan']);
			                                }
                                echo		'</p>
			                            </div>
			                        </article>';
	                    	}
	                    ?>
	                    </div><!--.box-typical-body-->
	                </section>
	            </div><!--.col-->
	        </div>
		</div>
	</div>

<?php
	require('includes/footer-top.php');
?>
	<script src="../assets/js/lib/fancybox/jquery.fancybox.pack.js"></script>
	<script>
		$(document).ready(function(){
			$('.panel').lobiPanel({
				sortable: true
			});
			$('.panel').on('dragged.lobiPanel', function(ev, lobiPanel){
				$('.dahsboard-column').matchHeight();
			});

			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var dataTable = new google.visualization.DataTable();
				dataTable.addColumn('string', 'Day');
				dataTable.addColumn('number', 'Values');
				// A column for custom tooltip content
				dataTable.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
				dataTable.addRows([
					['MON',  130, ' '],
					['TUE',  130, '130'],
					['WED',  180, '180'],
					['THU',  175, '175'],
					['FRI',  200, '200'],
					['SAT',  170, '170'],
					['SUN',  250, '250'],
					['MON',  220, '220'],
					['TUE',  220, ' ']
				]);

				var options = {
					height: 314,
					legend: 'none',
					areaOpacity: 0.18,
					axisTitlesPosition: 'out',
					hAxis: {
						title: '',
						textStyle: {
							color: '#fff',
							fontName: 'Proxima Nova',
							fontSize: 11,
							bold: true,
							italic: false
						},
						textPosition: 'out'
					},
					vAxis: {
						minValue: 0,
						textPosition: 'out',
						textStyle: {
							color: '#fff',
							fontName: 'Proxima Nova',
							fontSize: 11,
							bold: true,
							italic: false
						},
						baselineColor: '#16b4fc',
						ticks: [0,25,50,75,100,125,150,175,200,225,250,275,300,325,350],
						gridlines: {
							color: '#1ba0fc',
							count: 15
						},
					},
					lineWidth: 2,
					colors: ['#fff'],
					curveType: 'function',
					pointSize: 5,
					pointShapeType: 'circle',
					pointFillColor: '#f00',
					backgroundColor: {
						fill: '#008ffb',
						strokeWidth: 0,
					},
					chartArea:{
						left:0,
						top:0,
						width:'100%',
						height:'100%'
					},
					fontSize: 11,
					fontName: 'Proxima Nova',
					tooltip: {
						trigger: 'selection',
						isHtml: true
					}
				};

				var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
				chart.draw(dataTable, options);
			}
			$(window).resize(function(){
				drawChart();
				setTimeout(function(){
				}, 1000);
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

			$("#range-slider-1").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-2").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-3").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-4").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

		});

		function readMore(ID){
			$.ajax({
				type: 'POST',
				url: 'API/Kelas/',
				data: {"action": "readMore", "ID": ID},
				success: function(res) {
					$('#post-'+res.data.id_postingan).html(nl2br(res.data.isi_postingan));
					// console.log(res);
				},
				error: function () {
					swal("Error!", "Error Deleting data!", "error");
				}
			});
      	}

      	function nl2br (str, is_xhtml) {
		    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
		    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}
	</script>
<?php
	require('includes/footer-bottom.php');
?>
