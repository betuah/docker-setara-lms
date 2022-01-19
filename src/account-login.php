<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>SEAMOLEC - Learning Path</title>

	<link href="assets/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="assets/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="assets/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="assets/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="assets/img/favicon.png" rel="icon" type="image/png">
	<link href="assets/img/favicon.ico" rel="shortcut icon">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
<link rel="stylesheet" href="assets/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="assets/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form method='POST' class="sign-box" id="form-login" name="form-signin_v2" onsubmit="return false;">
					<div style="display: flex">
						<div class="sign-avatar">
	                        <img src="assets/img/dikbud.png" style="border-radius: 0px !important;" alt="">
	                    </div>
						<div class="sign-avatar">
	                        <img src="assets/img/jabar.png" style="border-radius: 0px !important;" alt="">
	                    </div>
					</div><br />
                    <header class="sign-title">SIAJAR</header>
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Email atau nama pengguna" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Kata sandi" required />
                    </div>
                    <div class="form-group">
                        <div class="checkbox float-left"></div>
                        <div class="float-right reset">
                            <a href="account-recovery.php">Lupa kata sandi anda ?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Log in</button>
                    <p class="sign-note">
						Daftar baru?<br />
						<a href="sign-up.html"><button type="button" style="display: inline-block" class="btn btn-primary">sebagai Guru</button></a>
						<a href="sign-up.html"><button type="button" style="display: inline-block" class="btn btn-warning">sebagai Siswa</button></a>
					</p>
					<hr style="margin: 10px 0;">
					<p align="center">Powered by <b><a href="http://seamolec.org">SEAMOLEC</a></b> &copy; 2017</p>
                </form>
            </div>
        </div>
    </div>


	<script src="assets/js/lib/jquery/jquery.min.js"></script>
	<script src="assets/js/lib/tether/tether.min.js"></script>
	<script src="assets/js/lib/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script>
        $(function() {
    			$('#form-login').submit(function() {
    				var fd = new FormData(this);
    				fd.append('action','login');
    				$.ajax({
          				type: 'POST',
          				url: 'url-API/auth.php',
          				data: fd,
          				contentType: false,
          				processData: false,
          				success: function(res){
        						alert(res.message);
        						location.href='index.php';
          				},
          				error: function(){
          					swal(res.response, res.message, res.icon);
          				}
          			});
    			});
          $('.page-center').matchHeight({
              target: $('html')
          });
          $(window).resize(function(){
              setTimeout(function(){
                  $('.page-center').matchHeight({ remove: true });
                  $('.page-center').matchHeight({
                      target: $('html')
                  });
              },100);
          });
        });

    </script>
</body>
</html>
