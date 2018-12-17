<?php
session_start();
$_SESSION["tab"]='carriersStatistics';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
?>
<h2 class="tabHeader">Прибутковість перевізників</h2>
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
        $carriers = mysqli_query($connection, "SELECT carriers.id AS 'id', carriers.name AS 'name', (SELECT COUNT(routes.id) FROM routes WHERE routes.carrierId = carriers.id) AS 'routes', (SELECT COUNT(destinations.id) FROM routes, destinations WHERE routes.carrierId = carriers.id AND destinations.routeId=routes.id) AS 'destinations', (SELECT COUNT(tickets.id) FROM tickets, routes WHERE tickets.routeId = routes.id AND routes.carrierId = carriers.id) AS 'tickets', (SELECT SUM(tickets.price) FROM tickets, routes WHERE tickets.routeId = routes.id AND routes.carrierId = carriers.id) AS 'revenue' FROM carriers");
        while($carrier = mysqli_fetch_assoc($carriers))
        {
            echo "<tr><td>".join("</td><td>", $carrier)."</td></tr>";
        }
        ?>
    </tbody>
</table>