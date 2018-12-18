<?php
session_start();
include("../includes/DBConnect.php");
$_SESSION["tab"]='buy';
?>
<script src="buy.js"></script>
<?php
if(isset($_POST))
{
    $result = 1;
    if(isset($_POST['departureDate']) && isset($_POST['routeName'])
        && isset($_POST['departureName']) && isset($_POST['arrivalName'])
        && isset($_POST['privilegeName']))
    {
        $routeName = $_POST['routeName'];
        $departureDate = $_POST['departureDate'];
        $departurePoint = $_POST['departureName'];
        $arrivalPoint = $_POST['arrivalName'];
        $baggage = ($_POST['baggage'] ? 1 : 0);
        $privilege = $_POST['privilegeName'];
        $results = [
            "Здійснено успішно!",
            "Помилка в базі даних!",
            "Вказаний маршрут не знаєдено!",
            "Кількість вільних місць менша, ніж вимагається.!",
            "Пільгу не знайдено!",
            "Невизначена помилка!"
        ];
        if(!($routeIds = mysqli_query($connection, $query = "SELECT routes.id, routes.time FROM routes WHERE LOWER(routes.name)=LOWER('".$routeName."');")))
        {
            $result = -1;
        }
        else if(!($route = mysqli_fetch_assoc($routeIds)) || !($routeId = $route['id']) || !($routeTime = $route['time']))
        {
            $result = -2;
        }
        else if(!($days = mysqli_query($connection, $query = "SELECT DAYOFWEEK('".$departureDate."') AS 'day'")))
        {
            $result = -1;
        }
        else if(!($day = mysqli_fetch_assoc($days)['day']))
        {
            $result = -1;
        }
        else if(!(mysqli_query($connection, $query = "SELECT routes.name AS 'routeName' FROM routes WHERE routes.id = '".$routeId."' AND ((routes.Monday*2=('".$day."')) OR (routes.Tuesday*3=('".$day."')) OR (routes.Wednesday*4=('".$day."')) OR (routes.Thursday*5=('".$day."')) OR (routes.Friday*6=('".$day."')) OR (routes.Saturday*7=('".$day."')) OR (routes.Sunday=('".$day."')))")))
        {
            $result = -1;
        }
        else if(!($departureIds = mysqli_query($connection, $query = "SELECT destinations.id FROM destinations WHERE destinations.routeId = '".$routeId."' AND destinations.stationId = (SELECT stations.id FROM stations WHERE stations.name = '".$departurePoint."');")))
        {
            $result = -1;
        }
        else if(!($departureId = mysqli_fetch_assoc($departureIds)['id']))
        {
            $result = -5;
        }
        else if(!($arrivalIds = mysqli_query($connection, $query = "SELECT destinations.id FROM destinations WHERE destinations.routeId = '".$routeId."' AND destinations.stationId = (SELECT stations.id FROM stations WHERE stations.name = '".$arrivalPoint."');")))
        {
            $result = -1;
        }
        else if(!($arrivalId = mysqli_fetch_assoc($arrivalIds)['id']))
        {
            $result = -5;
        }
        else if(!($privilegeIds = mysqli_query($connection, $query = "SELECT privileges.id FROM privileges WHERE LOWER(privileges.name)=LOWER('".$privilege."');")))
        {
            $result = -1;
        }
        else if(!($privilegeId = mysqli_fetch_assoc($privilegeIds)['id']))
        {
            $result = -4;
        }
        else if(!($positions = mysqli_query($connection, $query = "SELECT ((SELECT destinations.time FROM destinations WHERE destinations.id = '".$departureId."') < (SELECT destinations.time FROM destinations WHERE destinations.id = '.$arrivalId.')) AS 'dir'")))
        {
            $result = -1;
        }
        else if(!($position = mysqli_fetch_assoc($positions)['dir']))
        {
            $result = -5;
        }
        if($position === 0)
        {
            $temp = $departureId;
            $departureId = $arrivalId;
            $arrivalId = $temp;
        }
        if(!($prices = mysqli_query($connection, $query = "SELECT (((SELECT destinations.price FROM destinations WHERE destinations.id = '".$arrivalId."') - (SELECT destinations.price FROM destinations WHERE destinations.id = '".$departureId."')) * (1 - (SELECT privileges.discount FROM privileges WHERE privileges.id = '".$privilegeId."'))) AS price;")))
        {
            $result = -1;
        }
        else if(!($price = mysqli_fetch_assoc($prices)['price']))
        {
            $result = -5;
        }
        else
        {
            if(!($allPlaces = mysqli_query($connection, $query = "SELECT routes.places FROM routes WHERE routes.id='".$routeId."';")))
            {
                $result = -1;
            }
            else if(!($allPlaces = mysqli_fetch_assoc($allPlaces)['places']))
            {
                $result = -1;
            }
            else if(!($busyPlaces = mysqli_query($connection, $query = "SELECT tickets.place FROM tickets WHERE tickets.routeId='".$routeId."' AND tickets.date='".$departureDate."' AND (SELECT destinations.time FROM destinations WHERE destinations.id = tickets.departureDestinationID) >= (SELECT destinations.time FROM destinations WHERE destinations.id ='".$departureId."') AND (SELECT destinations.time FROM destinations WHERE destinations.id = tickets.arrivalDestinationID) <= (SELECT destinations.time FROM destinations WHERE destinations.id = '".$arrivalId."')")))
            {
                $result = -1;
            }
            else if(($C = mysqli_num_rows($busyPlaces)) < $places)
            {
                $result = -3;
            }
            $rowCount = mysqli_num_rows($busyPlaces);
            for ($z=0; $z < $rowCount; $z++) { 
                $busyplace = mysqli_fetch_assoc($busyPlaces)['place'];
                $P[$busyplace] = 1;
            }
            $i=1;
            for (; $i <= $allPlaces; $i++) {
                if($P[$i] != 1)
                {
                    break;
                }
            }
            if(!($j = mysqli_query($connection, $query = "INSERT INTO `tickets` (`routeId`, `cashierId`, `privilegeId`, `departureDestinationID`, `arrivalDestinationID`, `date`, `place`, `baggage`, `purchaseDateTime`, `price`, `state`) VALUES ('".$routeId."', '".$_SESSION["id"]."', '".$privilegeId."', '".$departureId."', '".$arrivalId."', '".$departureDate."', '".$i."', '".$baggage."', 'NOW()', '".$price."', '0')")))
            {
                $result = -1;
            }
            else
            {
                $result = 0;
                if(!($tickets = mysqli_query($connection, $query = "SELECT * FROM tickets WHERE tickets.routeId = '".$routeId."' AND tickets.date = '".$departureDate."' AND tickets.departureDestinationID = '".$departureId."' AND tickets.arrivalDestinationID = '".$arrivalId."' AND tickets.place = '".$i."';")))
                {
                    $result = -1;
                }
                else if(!($ticket = mysqli_fetch_assoc($tickets)))
                {
                    $result = -5;
                }
                else if(!($DateTimes = mysqli_query($connection, $query = "SELECT ADDTIME((SELECT ADDTIME((SELECT CONVERT('".$departureDate."', DATETIME)), (SELECT routes.time FROM routes WHERE routes.id = '".$routeId."'))), (SELECT destinations.time FROM destinations WHERE destinations.id = '".$departureId."')) AS 'time';")))
                {
                    $result = -1;
                }
                else if(!($DateTime = mysqli_fetch_assoc($DateTimes)['time']))
                {
                    $result = -1;
                }
                else
                {
                    $dt = explode(" ", $DateTime);
                    $ks = $price * 0.02;
                    echo "<meta charset='UTF-8'><div class='ticket'>
                        <h4>New Life Era: Transportation</h4>
                        <h5>Автостанція</h5>
                        <p>Чек №".$ticket['id']."</p>
                        <p>Касир: ".$_SESSION["name"]."</p>
                        <h4>Квиток</h4>
                        <p>Рейс № ".$routeId."</p>
                        <h4>".$routeName."</h4>
                        <h4>З ".$departurePoint."</h4>
                        <h4> До ".$arrivalPoint."</h4>
                        <h4>Дата: ".$dt[0]."</h4>
                        <h4>Час: ".$dt[1]."</h4>
                        <h4>Місце ".$i."</h4>
                        <p>Страхова плата: ".$ks."</p>
                        <p>Касовий збір: ".($price * 0.08)."</p>
                        <p>Тариф без НДС ".($price * 0.8)."</p>
                        <h3>Сума ".($price)." ГРН</h3>
                        <hr>
                        <p>Страхова сума: 6000 грн.</p>
                        <p>ПАТ 'Страх і користь'</p>
                        <p>м. Київ, вул. Електрик, 54</p>
                        <hr>
                        <p>Перевізник: Автолюкс</p>
                        <p>м. Житомир, пл. Т. Шевченка, 40</p>
                    </div>";
                    exit();
                }
            }
        }
    }
}
include("header.php");
?>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
<div class="fullPanel">
    <div class="sendForm">
    <h2>Покупка білетів</h2>
    <form action="buy.php" method="post">
    <table>
        <tr><td>Дата відправлення:</td><td><input type="date" name="departureDate" id="inputDate" value="<?php echo date("Y-m-d"); ?>" required></td></tr>
        <tr><td>Пункт відправлення:</td>
            <td><input type="text" name="departureName" id="addDeparture" oninput="searchNameChanged(this, addDepartures, VerifyAddTicket, 'destinationSearch')"
            value="<?php $query = "SELECT stations.name FROM stations WHERE stations.id = '".$_SESSION['stationId']."';";
            echo mysqli_fetch_assoc(mysqli_query($connection, $query))['name'];
             ?>" required>
                <ul class="optionsList" id="addDepartures" required></ul></td></tr>
        <tr><td>Пункт прибуття:</td>
            <td><input type="text" name="arrivalName" id="addArrival" oninput="searchNameChanged(this, addArrivals, VerifyAddTicket, 'destinationSearch')" required>
                <ul class="optionsList" id="addArrivals" required></ul></td></tr>
        <tr><td>Назва рейсу:</td><td><input type="text" class="emptyInput" name="routeName" id="addRouteName" oninput="searchNameChanged(this, addRoutes, VerifyAddTicket, 'routeSearch')" class="emptyInput" required>
            <ul class="optionsList" id="addRoutes"></ul></td></tr>
        <tr><td>Назва пільги:</td><td><input type="text" class="emptyInput" id="addPrivilegeName" name="privilegeName" oninput="searchNameChanged(this, addPrivileges, VerifyAddTicket, 'privilegeSearch')" required>
            <ul class="optionsList" id="addPrivileges"></ul></td></tr>
        <tr><td>Багаж:</td><td><input type="checkbox" name="baggage"></td></tr>
    </table>
    <button type="button" id="addButton">Купити</button><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
    </form>
    </div>
</div>
</body>
</html>
