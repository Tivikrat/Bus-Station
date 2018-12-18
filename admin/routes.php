<?php
session_start();
$_SESSION["tab"]='routes';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["routeName"]) && isset($_POST["time"]) && isset($_POST["carrierName"]) && isset($_POST["places"]))
    {
        if(!strlen($routeName = $_POST["routeName"]))
        {
            $result = -2;
        }
        else if(!strlen($time = $_POST["time"]))
        {
            $result = -3;
        }
        else if(!strlen($carrierName = $_POST["carrierName"]))
        {
            $result = -4;
        }
        else if(!($carrierIds = mysqli_query($connection, $query = "SELECT carriers.id FROM carriers WHERE LOWER(carriers.name)=LOWER('".$carrierName."')")))
        {
            $result = -1;
        }
        else if(!($carrierId = mysqli_fetch_assoc($carrierIds)))
        {
            $result = -5;
        }
        else if(($places = $_POST["places"]) < 0)
        {
            $result = -6;
        }
        else
        {
            $D1 = isset($_POST["D1"]);
            $D2 = isset($_POST["D2"]);
            $D3 = isset($_POST["D3"]);
            $D4 = isset($_POST["D4"]);
            $D5 = isset($_POST["D5"]);
            $D6 = isset($_POST["D6"]);
            $D7 = isset($_POST["D7"]);
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "Назву маршруту не задано!",
                "Час відправлення не вказаний!",
                "Назву перевізника не вказано",
                "Перевізника не знайдено!",
                "Кількість місць не має бути від'ємною!",
                "Невірний ідентифікатор!"];
            if(isset($_POST["id"]))
            {
                $id = $_POST["id"];
                if($id <= 0)
                {
                    $result = -7;
                }
                else
                {
                    if(mysqli_query($connection, $query = "UPDATE `routes` SET `name` = '".$routeName."', `time` = '".$time."', `carrierId` = '".$carrierId['id']."', `places` = '".$places."', `Monday` = '".$D1."', `Tuesday` = '".$D2."', `Wednesday` = '".$D3."', `Thursday` = '".$D4."', `Friday` = '".$D5."', `Saturday` = '".$D6."', `Sunday` = '".$D7."' WHERE `routes`.`id` = ".$id.";"))
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
                if(mysqli_query($connection, $query = "INSERT INTO routes (id, name, time, carrierId, places, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday) VALUES (NULL, '".$routeName."', '".$time."', '".$carrierId['id']."', '".$places."', '".$D1."', '".$D2."', '".$D3."', '".$D4."', '".$D5."', '".$D6."', '".$D7."');"))
                {
                    $result = 0;
                }
                else
                {
                    $result = -1;
                }
            }
        }

    }
    else
    {
        if(isset($_POST["id"]))
        {
            $id = $_POST["id"];
            $results = ["Видалено успішно!", "Помилка в базі даних.", "Невірний ідентифікатор!"];
            if($id <= 0)
            {
                $result = -2;
            }
            else if(mysqli_query($connection, $query = "DELETE FROM routes WHERE routes.id = '".$id."';"))
            {
                $result = 0;
            }
            else
            {
                $result = -1;
            }
        }
    }
}
?>
<div class="fullPanel" id="addPanel">
    <div class="editForm">
        <form action="routes.php" method="post" id="formEdit">
            <h4>Додання маршруту</h4>
            <table>
                <tr><td>Назва рейсу:</td><td><input type="text" name="routeName" class="emptyInput" id="addName" oninput="searchNameChanged(this, addRoutes, VerifyAddRoute, 'routeSearch')" required>
                    <ul class="optionsList" id="addRoutes"></td></tr>
                <tr><td>Назва перевізника:</td><td><input type="text" class="emptyInput" id="addCarrierName" name="carrierName" oninput="searchNameChanged(this, addCarriers, VerifyAddRoute, 'carrierSearch')" required>
                    <ul class="optionsList" id="addCarriers"></ul></td></tr>
                <tr><td>Час відправлення:</td><td><input type="time" name="time" required></td></tr>
                <tr><td>Кількість місць:</td><td><input type="number" name="places" value="0" min="0" required></td></tr>
                <tr><td>Понеділок:</td><td><input type="checkbox" name="D1"></td></tr>
                <tr><td>Вівторок:</td><td><input type="checkbox" name="D2"></td></tr>
                <tr><td>Середа:</td><td><input type="checkbox" name="D3"></td></tr>
                <tr><td>Четвер:</td><td><input type="checkbox" name="D4"></td></tr>
                <tr><td>П'ятниця:</td><td><input type="checkbox" name="D5"></td></tr>
                <tr><td>Субота:</td><td><input type="checkbox" name="D6"></td></tr>
                <tr><td>Неділя:</td><td><input type="checkbox" name="D7"></td></tr>
            </table>
            <!-- <table>
                <tr><th>Назва станції</th><th>Дата й час прибуття</th></tr>
                <tr><td><input type="text" name="point1" id="point1" placeholder="Назва станції"></td><td><input type="date" name="day1" id="day1"><input type="time" name="time1" id="time1"></td></tr>
                <tr><td><input type="text" name="point2" id="point2" placeholder="Назва станції"></td><td><input type="date" name="day2" id="day2"><input type="time" name="time2" id="time2"></td></tr>
                <tr><td colspan="2"><button type="button" class="actionButton" onclick="AddAddPoint(this)">Додати пункт призначення</button></td></tr>
            </table> -->
            <button type="button" id="addButton">Додати маршрут</button><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="routes.php" method="post" id="formEdit">
            <h4>Редагування маршруту</h4>
            <table>
                <tr><td>Id:</td><td><input type="number" name="id" id="editId" readonly></td></tr>
                <tr><td>Назва рейсу:</td><td><input type="text" name="routeName" class="emptyInput" id="editRouteName" oninput="searchNameChanged(this, editRoutes, VerifyEditRoute, 'routeSearch')" required>
                    <ul class="optionsList" id="editRoutes"></ul></td></tr>
                <tr><td>Назва перевізника:</td><td><input type="text" class="emptyInput" name="carrierName" id="editCarrierName" oninput="searchNameChanged(this, editCarriers, VerifyEditRoute, 'carrierSearch')" required>
                    <ul class="optionsList" id="editCarriers"></ul></td></tr>
                <tr><td>Час відправлення:</td><td><input type="time" name="time" id="editTime" min="0"></td></tr>
                <tr><td>Кількість місць:</td><td><input type="number" name="places" id="editPlaces" min="0"></td></tr>
                <tr><td>Понеділок:</td><td><input type="checkbox" name="D1" id="editD1"></td></tr>
                <tr><td>Вівторок:</td><td><input type="checkbox" name="D2" id="editD2"></td></tr>
                <tr><td>Середа:</td><td><input type="checkbox" name="D3" id="editD3"></td></tr>
                <tr><td>Четвер:</td><td><input type="checkbox" name="D4" id="editD4"></td></tr>
                <tr><td>П'ятниця:</td><td><input type="checkbox" name="D5" id="editD5"></td></tr>
                <tr><td>Субота:</td><td><input type="checkbox" name="D6" id="editD6"></td></tr>
                <tr><td>Неділя:</td><td><input type="checkbox" name="D7" id="editD7"></td></tr>
            </table>
            <!-- <table>
                <tr><th>Назва станції</th><th>Дата й час прибуття</th></tr>
                <tr><td><input type="text" name="point1" id="point1" placeholder="Назва станції"></td><td><input type="date" name="day1" id="day1"><input type="time" name="time1" id="time1"></td></tr>
                <tr><td><input type="text" name="point2" id="point2" placeholder="Назва станції"></td><td><input type="date" name="day2" id="day2"><input type="time" name="time2" id="time2"></td></tr>
                <tr><td colspan="2"><button type="button" class="actionButton" onclick="AddAddPoint(this)">Додати пункт призначення</button></td></tr>
            </table> -->
            <button type="submit" id="editButton">Редагувати маршрут</button><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="routes.php" method="post" id="formEdit">
        <h4>Ви справді бажаєте видалити цей маршрут? Куплені пасажирами квитки необхідно буде відшкодувати!</h4>
            <table>
                <tr><td>Id:</td><td><input type="number" name="id" id="deleteId" readonly></td></tr>
                <tr><td>Назва рейсу:</td><td id="deleteRouteName"></td></tr>
                <tr><td>Назва перевізника:</td><td id="deleteCarrierName"></td></tr>
                <tr><td>Час відправлення:</td><td id="deleteTime"></td></tr>
                <tr><td>Кількість місць:</td><td id="deletePlaces"></td></tr>
                <tr><td>Понеділок:</td><td><input type="checkbox" name="D1" id="deleteD1" disabled></td></tr>
                <tr><td>Вівторок:</td><td><input type="checkbox" name="D2" id="deleteD2" disabled></td></tr>
                <tr><td>Середа:</td><td><input type="checkbox" name="D3" id="deleteD3" disabled></td></tr>
                <tr><td>Четвер:</td><td><input type="checkbox" name="D4" id="deleteD4" disabled></td></tr>
                <tr><td>П'ятниця:</td><td><input type="checkbox" name="D5" id="deleteD5" disabled></td></tr>
                <tr><td>Субота:</td><td><input type="checkbox" name="D6" id="deleteD6" disabled></td></tr>
                <tr><td>Неділя:</td><td><input type="checkbox" name="D7" id="deleteD7" disabled></td></tr>
            </table>
            <!-- <table>
                <tr><th>Назва станції</th><th>Дата й час прибуття</th></tr>
                <tr><td><input type="text" name="point1" id="point1" placeholder="Назва станції"></td><td><input type="date" name="day1" id="day1"><input type="time" name="time1" id="time1"></td></tr>
                <tr><td><input type="text" name="point2" id="point2" placeholder="Назва станції"></td><td><input type="date" name="day2" id="day2"><input type="time" name="time2" id="time2"></td></tr>
                <tr><td colspan="2"><button type="button" class="actionButton" onclick="AddAddPoint(this)">Додати пункт призначення</button></td></tr>
            </table> -->
            <button type="submit" id="deleteButton">Видалити маршрут</button><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління маршрутами</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'; addName.focus();"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати маршрут</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва рейсу</th><th onclick="SortStrings(2)">Назва перевізника</th><th onclick="SortStrings(3)">Час відправлення</th><th onclick="SortNumbers(4)">Кількість місць</th><th onclick="SortStrings(5)">Пн</th><th onclick="SortStrings(6)">Вт</th><th onclick="SortStrings(7)">Ср</th><th onclick="SortStrings(8)">Чт</th><th onclick="SortStrings(9)">Пт</th><th onclick="SortStrings(9)">Сб</th><th onclick="SortStrings(10)">Нд</th><th>Редагувати</th><th>Видалити</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="idSearch" id="idSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="routeNameSearch" id="routeNameSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="carrierNameSearch" id="carrierNameSearch" oninput="RouteSearch()" placeholder="Пошук"></th>
            <th><input type="time" class="searchInput" name="lowerTimeSearch" id="lowerTimeSearch" oninput="RouteSearch()" placeholder="Від" value="00:00"><input type="time" class="searchInput" name="upperTimeSearch" id="upperTimeSearch" oninput="RouteSearch()" placeholder="До" value="23:59"></th>
            <th><input type="number" class="searchInput" name="lowerPlacesSearch" id="lowerPlacesSearch" oninput="RouteSearch()" placeholder="Від"><input type="number" class="searchInput" name="upperPlacesSearch" id="upperPlacesSearch" oninput="RouteSearch()" placeholder="До"></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на понеділок</div><input type="checkbox" name="day" id="D1Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на вівторок</div><input type="checkbox" name="day" id="D2Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на середу</div><input type="checkbox" name="day" id="D3Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на четвер</div><input type="checkbox" name="day" id="D4Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на п'ятницю</div><input type="checkbox" name="day" id="D5Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на суботу</div><input type="checkbox" name="day" id="D6Search" oninput="RouteSearch()"></div></th>
            <th><div class="tooltip"><div class="tooltiptext">Показати маршрути на неділю</div><input type="checkbox" name="day" id="D7Search" oninput="RouteSearch()"></div></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $routes = mysqli_query($connection, "SELECT routes.id AS 'id', routes.name AS 'routeName', routes.time AS 'time', carriers.name AS 'carrierName', routes.places AS 'places', routes.Monday AS 'D1', routes.Tuesday AS 'D2', routes.Wednesday AS 'D3', routes.Thursday AS 'D4', routes.Friday AS 'D5', routes.Saturday AS 'D6', routes.Sunday AS 'D7' FROM routes, carriers WHERE carrierId=carriers.id;");
        while($route = mysqli_fetch_assoc($routes))
        {
            echo "<tr class='dataRow'><td hidden='true'>".$route["id"]."</td><td>".$route["routeName"]."</td><td>".$route["carrierName"]."</td><td>".$route["time"]."</td><td>".$route["places"]."</td><td class='boxTd'><input type='checkbox' ".($route["D1"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D2"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D3"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D4"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D5"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D6"] ? "checked" : "")." onclick='return false;'></td><td class='boxTd'><input type='checkbox' ".($route["D7"] ? "checked" : "")." onclick='return false;'></td><td><button class='actionButton' onclick='EditRoute(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeleteRoute(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Видалити</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>
