<?php
session_start();
$_SESSION["tab"]='stationsStatistics';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
?>
<h2 class="tabHeader">Статистика використання станцій</h2>
<table id="dataTable">
    <tbody id="data">
        <tr><th>Id</th><th onclick="SortStrings(1)">Назва</th><th onclick="SortNumbers(2)">Кількість перевізників</th><th onclick="SortNumbers(3)">Кількість маршрутів</th><th onclick="SortNumbers(4)">Кількість відправлень</th><th onclick="SortNumbers(5)">Вивезено на суму</th><th onclick="SortNumbers(6)">Кількість доставлень</th><th onclick="SortNumbers(7)">Привезено на суму</th></tr>
        <!-- <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="StationSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="StationSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr> -->
        <?php
        $stations = mysqli_query($connection, "SELECT stations.id, stations.name, (SELECT COUNT(carriers.id) FROM carriers, destinations, routes WHERE carriers.id = routes.carrierId AND routes.id = destinations.routeId AND destinations.stationId = stations.id) AS 'carriers', (SELECT COUNT(routes.id) FROM routes, destinations WHERE routes.id = destinations.routeId AND destinations.stationId = stations.id) AS 'routes', (SELECT COUNT(tickets.id) FROM tickets, destinations WHERE tickets.departureDestinationID = destinations.id AND destinations.stationId = stations.id) AS 'departures', (SELECT SUM(tickets.price) FROM tickets, destinations WHERE tickets.departureDestinationID = destinations.id AND destinations.stationId = stations.id) AS 'export', (SELECT COUNT(tickets.id) FROM tickets, destinations WHERE tickets.arrivalDestinationID = destinations.id AND destinations.stationId = stations.id) AS 'arrivals', (SELECT SUM(tickets.price) FROM tickets, destinations WHERE tickets.arrivalDestinationID = destinations.id AND destinations.stationId = stations.id) AS 'import' FROM stations");
        while($station = mysqli_fetch_assoc($stations))
        {
            echo "<tr><td>".join("</td><td>", $station)."</td></tr>";
        }
        ?>
    </tbody>
</table>