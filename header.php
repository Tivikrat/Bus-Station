<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<!-- <meta http-equiv="refresh" content="10"> -->
		<title>Railway-Booker</title>
		<link rel = 'stylesheet' href = '<?php if(!isset($_SESSION['dark'])) echo "dark"; else echo "light" ?>.css' type = 'text/css'>
		<script src="index.js"></script>
	</head>
	<body>
		<header>
			<img src="logo.png" alt="Logo" width="500px">
			<div id="rightContscts" style="float: right; margin-right: 50px;">
				<br>
				<h2>Skype: uz_booking</h2>
				<br>
				<h2>Email: booking@uz.gov.ua</h2>
				<br>
				<h2>Технічна підтримка: +380 (44) 591-1988</h2>
				<br>
				<h2>Справочна служба: +380 (44) 309-7005</h2>
			</div>
			<table id="mainMenu" style="width: 100%">
				<tr>
					<td><a href="index.php">Головна</a></td>
					<td><a href="booking.php">Замовлення квитків</a></td>
					<td><a href="booking.php">Публічна інформація</a></td>
					<td><a href="booking.php">Співпраця з партнерами</a></td>
				</tr>
			</table>
		</header>
