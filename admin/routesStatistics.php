<?php
session_start();
$_SESSION["tab"]='routesStatistics';
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
<script src="routesStatistics.js"></script>
<h2 class="tabHeader">Прибутковість маршрутів<?php echo ($startDate && $endDate ? " з ".$startDate." по ".$endDate : " за весь термін");?></h2>
<div class="fullVisiblePanel">
    <div class="sendForm">
        <form action="routesStatistics.php" method="post">
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
        <tr><th>Id</th><th onclick="SortStrings(1)">Назва</th><th onclick="SortNumbers(2)">Кількість пунктів</th><th onclick="SortNumbers(3)">Кількість проданих білетів</th><th onclick="SortNumbers(4)">Дохід</th></tr>
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
            $routes = mysqli_query($connection, "SELECT routes.id AS 'id', routes.name AS 'name', (SELECT COUNT(destinations.id) FROM destinations WHERE destinations.routeId = routes.id) AS 'stations', (SELECT COUNT(tickets.id) FROM tickets WHERE tickets.routeId = routes.id AND tickets.date >= '".$startDate."' AND tickets.date <= '".$endDate."') AS 'tickets', (SELECT SUM(tickets.price) FROM tickets WHERE tickets.routeId = routes.id AND tickets.date >= '".$startDate."' AND tickets.date <= '".$endDate."') AS 'revenue' FROM routes, destinations, tickets WHERE destinations.routeId = routes.id AND tickets.routeId = routes.id AND tickets.date >= '".$startDate."' AND tickets.date <= '".$endDate."' GROUP BY routes.id, routes.name, 'revenue'");
        }
        else
        {
            $routes = mysqli_query($connection, "SELECT routes.id AS 'id', routes.name AS 'name', (SELECT COUNT(destinations.id) FROM destinations WHERE destinations.routeId = routes.id) AS 'stations', (SELECT COUNT(tickets.id) FROM tickets WHERE tickets.routeId = routes.id) AS 'tickets', (SELECT SUM(tickets.price) FROM tickets WHERE tickets.routeId = routes.id) AS 'revenue' FROM routes, destinations, tickets WHERE destinations.routeId = routes.id AND tickets.routeId = routes.id GROUP BY routes.id, routes.name, 'revenue'");
        }
        
        while($route = mysqli_fetch_assoc($routes))
        {
            echo "<tr><td>".join("</td><td>", $route)."</td></tr>";
        }
        ?>
    </tbody>
</table>