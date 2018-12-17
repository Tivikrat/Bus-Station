<!doctype html>
<html>
	<head>
<?php
if(!isset($_SESSION) || !isset($_SESSION["user"]) || $_SESSION["user"] != 'cashier')
{
    echo '<meta http-equiv="refresh" content="0; url=/cashier/login.php"/></head></html>';
    exit;
}
?>
		<meta charset="UTF-8">
		<!-- <meta http-equiv="refresh" content="10"> -->
		<title>Bus station personnel</title>
		<link rel = 'stylesheet' href = 'main.css' type = 'text/css'>
		<script src="cashier.js"></script>
	</head>
	<body>
        <h1 class="windowName">Термінал касира</h1>
        <ul class="menu">
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='plan' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/cashier/plan.php">Розклад руху</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='buy' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/cashier/buy.php">Покупка білетів</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='return' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/cashier/return.php">Повернення білетів</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='path' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/cashier/path.php">Транспортна накладна</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='exit' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/cashier/exit.php">Вихід</a>
            </li>
        </ul>
