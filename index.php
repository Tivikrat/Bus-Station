<?php
session_start();
include("header.php");
?>
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
		<div>
		<h1>Головна сторінка</h1>
		
		</div>
	</body>
</html>
