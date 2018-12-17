<?php
session_start();
include("../includes/DBConnect.php");
if(!isset($_GET) || !isset($_GET['id']))
{
    echo '<meta http-equiv="refresh" content="0; url=/cashier/plan.php"/></head></html>';
    exit;
}
$id = $_GET['id'];
if(!($routeNames = mysqli_query($connection, $query = "SELECT routes.name FROM routes WHERE routes.id = '".$id."'")))
{
    echo "Невірний ідентифікатор!";
    echo $query;
}
else
{
    $routeName = mysqli_fetch_assoc($routeNames)['name'];
}
echo "<meta charset='UTF-8'><link rel = 'stylesheet' href = 'table.css' type = 'text/css'><div class='ticket'>
    <h4>New Life Era: Transportation</h4>
    <h5>Автостанція</h5>
    <p>Рейс № ".$id."</p>
    <h4>".$routeName."</h4>
    <p>Перевізник: Автолюкс</p>
    <p>м. Житомир, пл. Т. Шевченка, 40</p>
</div>";

?>
<table id="dataTable">
    <tbody id="data">
        <tr><th onclick="SortStrings(1)">Назва станції</th><th onclick="SortStrings(2)">Час з початку руху</th></tr>
        <!-- <tr>
            <th><input type="text" class="searchInput" name="phoneSearch" id="stationNameSearch" oninput="PointSearch()" placeholder="Пошук"></th>
            <th><input type="number" class="searchInput" name="addressSearch" id="lowerPriceSearch" oninput="PointSearch()" placeholder="Пошук" min="0" max="1000000000000">
                <input type="number" class="searchInput" name="addressSearch" id="upperPriceSearch" oninput="PointSearch()" placeholder="Пошук" min="0" max="1000000000000"></th>
            <th><input type="time" class="searchInput" name="addressSearch" id="lowerTimeSearch" oninput="PointSearch()" placeholder="Пошук" value="00:00">
                <input type="time" class="searchInput" name="addressSearch" id="upperTimeSearch" oninput="PointSearch()" placeholder="Пошук" value="23:59"></th>
            <th></th>
            <th></th>
        </tr> -->
        <?php
        $destinations = mysqli_query($connection, $query = "SELECT stations.name AS 'stationName', ADDTIME(routes.time, destinations.time) FROM routes, stations, destinations WHERE destinations.routeId='".$id."' AND stations.id=destinations.stationId GROUP BY destinations.time;");
        while($destination = mysqli_fetch_assoc($destinations))
        {
            echo "<tr><td>".join("</td><td>", $destination)."</td></tr>";
        }
        ?>
    </tbody>
</table>