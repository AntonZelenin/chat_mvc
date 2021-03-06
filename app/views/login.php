<!DOCTYPE html>
<html lang="ru">

<head>

	<meta charset="utf-8">

	<title>Log in | Sign in</title>
	<meta name="description" content="">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- <meta property="og:image" content="path/to/image.jpg"> -->

	<link rel="shortcut icon" href="/publicimg/favicon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="/publicimg/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/publicimg/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/publicimg/favicon/apple-touch-icon-114x114.png">

	<!-- Chrome, Firefox OS and Opera -->
	<meta name="theme-color" content="#000">
	<!-- Windows Phone -->
	<meta name="msapplication-navbutton-color" content="#000">
	<!-- iOS Safari -->
	<meta name="apple-mobile-web-app-status-bar-style" content="#000">

	<style>body { opacity: 0; overflow-x: hidden; }</style>

</head>

<body class="login">

<div class="loader">
	<div class="loader-inner"></div>
</div>

<div class="login-header">
	<div class="login-header__img">
		<img src="/public/img/logo.svg" alt="Logo">
	</div>
</div>

<div class="login-main">
	<div class="login-form-wrapper">
			<div class="login-form">

				<div class="tabs clearfix">
					<span class="tab  form-btn  form-btn--sign-in">Sign In</span>
					<span class="tab  form-btn  form-btn--sign-up">Sign Up</span>
				</div>

				<div class="tab-content form__fields-wrapper">

					<div class="tab-item  sign-in__content">
						<form action="/public/login/enter" method="post">
							<input type="email" id="email" placeholder="Email" required>
							<input type="password" id="password" placeholder="Password" required>
							<input type="submit" value="Submit" class="form-submit-btn">
						</form>
					</div>

					<div class="tab-item  sign-up__content clearfix">
						<form action="/public/login/register" method="post" onsubmit="return checkForm();">
							<input type="text" id="first-name" placeholder="First Name" name="first-name" class="first-name" required>
							<input type="text" id="last-name" placeholder="Last Name" name="last-name" class="last-name" required>
							<input type="email" id="reg-email"  placeholder="Email" name="email" required>
							<input type="password" id="reg-password"  placeholder="Password" name="password" required>
							<input type="password" id="reg-password-confirm" placeholder="Confirm Password" required>
							<input type="submit" value="Submit" class="form-submit-btn">
						</form>
					</div>

				</div>
			</div>
	</div>
</div>


	<link rel="stylesheet" href="/public/css/style.min.css">
	<script src="/public/js/scripts.min.js"></script>
	<script src="/public/js/common.js"></script>

</body>
</html>
