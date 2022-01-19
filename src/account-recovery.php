<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Lupa Kata Sandi - seTARA daring | Dit.PMPK</title>

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
	<link rel="stylesheet" href="assets/css/lib/bootstrap-sweetalert/sweetalert.css">
	<script src="assets/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
</head>
<body>
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form name="" class="sign-box reset-password-box" method="post" onsubmit="">
                    <!--<div class="sign-avatar">
                        <img src="assets/img/avatar-sign.png" alt="">
                    </div>-->
                    <header class="sign-title">Atur ulang Kata Sandi</header>
                    <div class="form-group">
                        <input type="text" id="isian" name="name" class="form-control" placeholder="Email atau nama pengguna" required/>
                        <input type="hidden" name="action" value="forgot" class="form-control"/>
                    </div>
					<button type="submit" class="btn btn-rounded">Atur ulang</button>
					atau
					<a href="./" class="btn btn-rounded btn-warning">Kembali</a>
                </form>
            </div>
        </div>
    </div><!--.page-center-->

<script src="assets/js/lib/jquery/jquery.min.js"></script>
<script src="assets/js/lib/tether/tether.min.js"></script>
<script src="assets/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="assets/js/plugins.js"></script>
    <script type="text/javascript" src="assets/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script>
        $(function() {
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

		$('form').submit(function(e) {
        $('button[type=submit]').prop("disabled", true);
			e.preventDefault();
			var submit = true;

			$.ajax({
				type: 'POST',
				url: 'url-API/Pengguna/',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(res){
					swal({
						title: res.response,
						text:  res.message,
						type:  res.icon
					});

					$('button[type=submit]').prop("disabled", false);

					if (res.icon == 'succes') {
						$('#isian').val('');
					}
					
				},
				error: function(){
					swal(res.response, res.message, res.icon);
					submit = false;
				}
			});

			return false;
		});
    </script>
<script src="assets/js/app.js"></script>
</body>
</html>
