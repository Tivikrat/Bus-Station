<?php
session_start();
$_SESSION["tab"]='plan';
include("header.php");
include("../includes/DBConnect.php");
if(isset($_GET) && isset($_GET['date']))
{
    $date = $_GET['date'];
}
else
{
    $date = date("Y-m-d");
}
?>
<script src="plan.js"></script>
<h2 class="tabHeader">Маршрути на <?php echo $date; ?></h2>
<div class="fullPanel">
    <div class="sendForm">
        <form action="plan.php" method="get">
            Вибрати день:
            <input type="date" name="date" id="inputDate" value="<?php echo $date;?>" require>
            <input type="submit" value="Показати маршрути">
        </form>
    </div>
</div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва рейсу</th><th onclick="SortStrings(2)">Назва перевізника</th><th onclick="SortStrings(3)">Час відправлення</th><th onclick="SortStrings(3)">Час прибуття</th><th onclick="SortStrings(3)">Тривалість</th><th onclick="SortStrings(3)">Накладна</th></tr>
        <!-- <tr>
            <th hidden='true'><input type="text" name="idSearch" id="idSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="routeNameSearch" id="routeNameSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="carrierNameSearch" id="carrierNameSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="time" class="searchInput" name="lowerTimeSearch" id="lowerTimeSearch" oninput="RouteSearch()" placeholder="Від" value="00:00"><input type="time" class="searchInput" name="upperTimeSearch" id="upperTimeSearch" oninput="RouteSearch()" placeholder="До" value="23:59"></th>
            <th><input type="number" class="searchInput" name="lowerPlacesSearch" id="lowerPlacesSearch" oninput="RouteSearch()" placeholder="Від"><input type="number" class="searchInput" name="upperPlacesSearch" id="upperPlacesSearch" oninput="RouteSearch()" placeholder="До"></th>
        </tr> -->
        <?php
        $routes = mysqli_query($connection, $query = "SELECT routes.id AS 'id', routes.name AS 'routeName', carriers.name AS 'carrierName', routes.time AS 'departureTime', ADDTIME(routes.time, MAX(destinations.time)) as 'arrivalTime', MAX(destinations.time) AS 'duration' FROM routes, destinations, carriers WHERE carriers.id = routes.carrierId AND destinations.routeId = routes.id AND ((routes.Monday*2=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Tuesday*3=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Wednesday*4=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Thursday*5=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Friday*6=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Saturday*7=(SELECT DAYOFWEEK('".$date."'))) OR (routes.Sunday=(SELECT DAYOFWEEK('".$date."')))) GROUP BY routes.name;");
        while($route = mysqli_fetch_assoc($routes))
        {
            echo "<tr><td hidden='true'>".$route["id"]."</td><td>".$route["routeName"]."</td><td>".$route["carrierName"]."</td><td>".$route["departureTime"]."</td><td>".$route["arrivalTime"]."</td><td>".$route["duration"]."</td><td><button class='actionButton' onclick='Form(this)'>Сформувати</button></td></tr>";
        }
        ?>
    </tbody>
</table>