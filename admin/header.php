<!doctype html>
<html>
	<head>
<?php
if(!isset($_SESSION) || !isset($_SESSION["user"]) || $_SESSION["user"] != 'Admin')
{
    echo '<meta http-equiv="refresh" content="0; url=/admin/login.php"/></head></html>';
    exit;
}
?>
		<meta charset="UTF-8">
		<!-- <meta http-equiv="refresh" content="10"> -->
		<title>Bus station administration</title>
		<link rel = 'stylesheet' href = 'main.css' type = 'text/css'>
		<script src="admin.js"></script>
	</head>
	<body>
        <h1 class="windowName">Панель управління автостанцією</h1>
        <ul class="menu">
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='carriers' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/carriers.php">Перевізники</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='cashiers' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/cashiers.php">Касири</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='destinations' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/destinations.php">Пункти</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='privileges' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/privileges.php">Пільги</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='routes' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/routes.php">Маршрути</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='stations' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/stations.php">Станції</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='routesStatistics' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/routesStatistics.php">Прибутковість маршрутів</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='carriersStatistics' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/carriersStatistics.php">Прибутковість перевізників</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='stationsStatistics' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/stationsStatistics.php">Використання станцій</a>
            </li>
            <li class="menuElement">
                <a class="<?php echo ($_SESSION['tab']=='exit' ? 'menuItem selectedMenuItem' : 'menuItem')?>" href="/admin/exit.php">Вихід</a>
            </li>
        </ul>
