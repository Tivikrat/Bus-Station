<?php
session_start();
$_SESSION["tab"]='routesStatistics';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
?>
<h2 class="tabHeader">Прибутковість маршрутів</h2>
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
        $routes = mysqli_query($connection, "SELECT routes.id AS 'id', routes.name AS 'name', (SELECT COUNT(destinations.id) FROM destinations WHERE destinations.routeId = routes.id) AS 'stations', (SELECT COUNT(tickets.id) FROM tickets WHERE tickets.routeId = routes.id) AS 'tickets', (SELECT SUM(tickets.price) FROM tickets WHERE tickets.routeId = routes.id) AS 'revenue' FROM routes, destinations, tickets WHERE destinations.routeId = routes.id AND tickets.routeId = routes.id GROUP BY routes.id, routes.name, 'revenue'");
        while($route = mysqli_fetch_assoc($routes))
        {
            echo "<tr><td>".join("</td><td>", $route)."</td></tr>";
        }
        ?>
    </tbody>
</table>