<?php
session_start();
$_SESSION["tab"]='carriersStatistics';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST) && isset($_POST['startDate']) && isset($_POST['endDate']))
{
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
}
else
{
    $endDate = date("Y-m-d");
}
?>
<script src="carrierStatistics.js"></script>
<h2 class="tabHeader">Прибутковість перевізників<?php echo ($startDate && $endDate ? " з ".$startDate." по ".$endDate : " за весь термін");?></h2>
<div class="fullVisiblePanel">
    <div class="sendForm">
        <form action="carriersStatistics.php" method="post">
            <h3>Вкажіть проміжок статистики:</h3>
            <table>
                <tr><td>Дата початку статистики:</td><td><input type="date" name="startDate" id="startDate" value="<?php echo $startDate;?>" require></td></tr>
                <tr><td>Дата кінця статистики:</td><td><input type="date" name="endDate" id="endDate" value="<?php echo $endDate;?>" require></td></tr>
            </table>
            <input type="submit" value="Показати статистику">
        </form>
    </div>
</div>
<table id="dataTable">
    <tbody id="data">
        <tr><th>Id</th><th onclick="SortStrings(1)">Назва</th><th onclick="SortNumbers(2)">Кількість маршрутів</th><th onclick="SortNumbers(2)">Кількість пунктів</th><th onclick="SortNumbers(3)">Кількість проданих білетів</th><th onclick="SortNumbers(4)">Дохід</th></tr>
        <!-- <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="StationSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="StationSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr> -->
        <?php
        if($startDate && $endDate)
        {
            $carriers = mysqli_query($connection, $query = "SELECT carriers.id AS 'id', carriers.name AS 'name', (SELECT COUNT(routes.id) FROM routes WHERE routes.carrierId = carriers.id) AS 'routes', (SELECT COUNT(destinations.id) FROM routes, destinations WHERE routes.carrierId = carriers.id AND destinations.routeId=routes.id) AS 'destinations', (SELECT COUNT(tickets.id) FROM tickets, routes WHERE tickets.routeId = routes.id AND tickets.date >= '".$startDate."' AND tickets.date <= '".$endDate."' AND routes.carrierId = carriers.id) AS 'tickets', (SELECT SUM(tickets.price) FROM tickets, routes WHERE tickets.routeId = routes.id AND tickets.date >= '".$startDate."' AND tickets.date <= '".$endDate."' AND routes.carrierId = carriers.id) AS 'revenue' FROM carriers");
        }
        else
        {
            $carriers = mysqli_query($connection, $query = "SELECT carriers.id AS 'id', carriers.name AS 'name', (SELECT COUNT(routes.id) FROM routes WHERE routes.carrierId = carriers.id) AS 'routes', (SELECT COUNT(destinations.id) FROM routes, destinations WHERE routes.carrierId = carriers.id AND destinations.routeId=routes.id) AS 'destinations', (SELECT COUNT(tickets.id) FROM tickets, routes WHERE tickets.routeId = routes.id AND routes.carrierId = carriers.id) AS 'tickets', (SELECT SUM(tickets.price) FROM tickets, routes WHERE tickets.routeId = routes.id AND routes.carrierId = carriers.id) AS 'revenue' FROM carriers");
        }
        while($carrier = mysqli_fetch_assoc($carriers))
        {
            echo "<tr><td>".join("</td><td>", $carrier)."</td></tr>";
        }
        ?>
    </tbody>
</table>