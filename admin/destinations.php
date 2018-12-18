<?php
session_start();
$_SESSION["tab"]='destinations';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["id"]))
    {
        $id = $_POST["id"];
        if(isset($_POST["price"]) && isset($_POST["time"]))
        {
            $price = $_POST["price"];
            $time = $_POST["time"];
            $results = [
                "Змінено успішно!",
                "Помилка в базі даних.",
                "Ціна не від'ємна!",
                "Час не заданий!",
                "Хибний ідентифікатор пункту."];
            if($price < 0)
            {
                $result = -2;
            }
            else if(!strlen($time))
            {
                $result = -3;
            }
            else
            {
                if(!mysqli_query($connection, $query = "UPDATE destinations SET `price` = '".$price."', `time` = '".$time."' WHERE destinations.id = '".$id."';"))
                {
                    $result = -1;
                }
                else
                {
                    $result = 0;
                }
            }
        }
        else
        {
            $results = ["Видалено успішно!", "Помилка в базі даних."];
            $query = "DELETE FROM destinations WHERE destinations.id = '".$id."';";
            if(mysqli_query($connection, $query))
            {
                $result = 0;
            }
            else
            {
                $result = -1;
            }
        }
    }
    else
    {
        if(isset($_POST["routeName"]) && isset($_POST["stationName"]) && isset($_POST["price"]) && isset($_POST["time"]))
        {
            $routeName = $_POST["routeName"];
            $stationName = $_POST["stationName"];
            $price = $_POST["price"];
            $time = $_POST["time"];
            $results = [
                "Додано успішно!",
                "Помилка в базі даних.",
                "Маршрут не заданий",
                "Станція не задана!",
                "Ціна не від'ємна!",
                "Час не заданий!",
                "Хибний ідентифікатор пункту.",
                "Маршрут не знайдено!",
                "Станцію не знайдено!"];
            if(!strlen($routeName))
            {
                $result = -2;
            }
            else if(!($routeIds = mysqli_query($connection, $query = "SELECT routes.id FROM routes WHERE LOWER(routes.name)=LOWER('".$routeName."');")))
            {
                $result = -1;
            }
            else if(!($routeId = mysqli_fetch_assoc($routeIds)))
            {
                $result = -7;
            }
            else if(!strlen($stationName))
            {
                $result = -3;
            }
            else if(!($stationIds = mysqli_query($connection, $query = "SELECT stations.id FROM stations WHERE LOWER(stations.name)=LOWER('".$stationName."');")))
            {
                $result = -1;
            }
            else if(!($stationId = mysqli_fetch_assoc($stationIds)))
            {
                $result = -8;
            }
            if($price < 0)
            {
                $result = -4;
            }
            else if(!strlen($time))
            {
                $result = -5;
            }
            else if(!mysqli_query($connection, $query = "INSERT INTO destinations (routeId, stationId, price, time) VALUES ('".$routeId['id']."','".$stationId['id']."','".$price."', '".$time."');"))
            {
                $result = -1;
            }
            else
            {
                $result = 0;
            }
        }
    }
}
?>
<div class="fullPanel" id="addPanel">
    <div class="editForm">
        <form action="destinations.php" method="post" id="formEdit">
            <h4>Додавання пункту маршруту</h4>
            <table>
                <tr><td>Назва маршруту:</td><td><input type="text" class="emptyInput" name="routeName" id="addRouteName" required oninput="searchNameChanged(this, addRoutes, VerifyAddPoint, 'routeSearch')">
                    <ul class="optionsList" id="addRoutes"></ul></td></tr>
                <tr><td>Назва станції:</td><td><input type="text" class="emptyInput" name="stationName" id="addStationName" required oninput="searchNameChanged(this, addStations, VerifyAddPoint, 'stationSearch')">
                    <ul class="optionsList" id="addStations"></td></tr>
                <tr><td>Ціна з початку руху:</td><td><input type="number" name="price" step="any" min="0" required></td></tr>
                <tr><td>Час з початку руху:</td><td><input type="time" name="time" required></td></tr>
            </table>
            <button type="button" id="addButton">Додати</button><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="destinations.php" method="post" id="formEdit">
            <h4>Зміна пункту маршруту</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="inputId" readonly></td></tr>
                <tr><td>Назва маршруту:</td><td><input type="text" name="routeName" id="inputRouteName" readonly></td></tr>
                <tr><td>Назва станції:</td><td><input type="text" name="stationName" id="inputStationName" readonly></td></tr>
                <tr><td>Ціна з початку руху:</td><td><input type="number" name="price" id="inputPrice" step="any" min="0" required></td></tr>
                <tr><td>Час з початку руху:</td><td><input type="time" name="time" id="inputTime" required></td></tr>
            </table>
            <input type="submit" value="Змінити"><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="destinations.php" method="post" id="formEdit">
            <h4>Ви справді бажаєте видалити цей пункт?</h4>
            <h4>Квитки, що відповідають цьому пункту необхідно буде відшкодувати!</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="deleteId" readonly></td></tr>
                <tr><td>Назва маршруту:</td><td id="deleteRouteName"></td></tr>
                <tr><td>Назва станції:</td><td id="deleteStationName"></td></tr>
                <tr><td>Ціна з початку руху:</td><td id="deletePrice"></td></tr>
                <tr><td>Час з початку руху:</td><td id="deleteTime"></td></tr>
            </table>
            <input type="submit" value="Видалити"><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління пунктами</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'; addRouteName.focus()"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати пункт</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва маршруту</th><th onclick="SortStrings(2)">Назва станції</th><th onclick="SortNumbers(3)">Вартість з початкового пункту</th><th onclick="SortStrings(4)">Час з початку руху</th><th>Редагувати</th><th>Видалити</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="PointSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="routeNameSearch" oninput="PointSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="phoneSearch" id="stationNameSearch" oninput="PointSearch()" placeholder="Пошук"></th>
            <th><input type="number" class="searchInput" name="addressSearch" id="lowerPriceSearch" oninput="PointSearch()" placeholder="Від" min="0" max="1000000000000">
                <input type="number" class="searchInput" name="addressSearch" id="upperPriceSearch" oninput="PointSearch()" placeholder="До" min="0" max="1000000000000"></th>
            <th><input type="time" class="searchInput" name="addressSearch" id="lowerTimeSearch" oninput="PointSearch()" placeholder="Від" value="00:00">
                <input type="time" class="searchInput" name="addressSearch" id="upperTimeSearch" oninput="PointSearch()" placeholder="До" value="23:59"></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $destinations = mysqli_query($connection, "SELECT destinations.id AS 'id', routes.name AS 'routeName', stations.name AS 'stationName', destinations.price AS 'price', destinations.time AS 'time' FROM routes, stations, destinations WHERE routes.id=destinations.routeId AND stations.id=destinations.stationId ORDER BY routes.name, destinations.time;");
        while($destination = mysqli_fetch_assoc($destinations))
        {
            echo "<tr class='dataRow'><td hidden='true'>".join("</td><td>", $destination)."</td><td><button class='actionButton' onclick='EditPoint(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeletePoint(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Видалити</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>