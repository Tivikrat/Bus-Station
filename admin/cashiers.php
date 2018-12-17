<?php
session_start();
$_SESSION["tab"]='cashiers';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["id"]))
    {
        $id = $_POST["id"];
        if(isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["address"]) && isset($_POST["login"]) && isset($_POST["password"]))
        {
            $name = $_POST["name"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $login = $_POST["login"];
            $password = $_POST["password"];
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "ПІБ касира не задано!",
                "Телефон не задано!",
                "Адреса не задана!"];
            if(!strlen($name))
            {
                $result = -2;
            }
            else if(!strlen($phone))
            {
                $result = -3;
            }
            else if(!strlen($address))
            {
                $result = -4;
            }
            else
            {
                $query = "UPDATE cashiers SET name = '".$name."', phone = '".$phone."', address = '".$address."', login = '".$login."', password = '".$password."' WHERE cashiers.id = '".$id."';";
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
            $results = ["Видалено успішно!", "Помилка в базі даних."];
            $query = "DELETE FROM cashiers WHERE cashiers.id = '".$id."';";
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
        if(isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["address"]))
        {
            $name = $_POST["name"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $login = $_POST["login"];
            $password = $_POST["password"];
            $results = [
                "Реєстрацію виконано успішно!",
                "Помилка в базі даних.",
                "ПІБ касира не задано!",
                "Телефон не задано!",
                "Адреса не задана!"];
            if(!strlen($name))
            {
                $result = -2;
            }
            else if(!strlen($phone))
            {
                $result = -3;
            }
            else if(!strlen($address))
            {
                $result = -4;
            }
            else if(mysqli_query($connection, "INSERT INTO cashiers (id, name, phone, address, login, password) VALUES (NULL,'".$name."','".$phone."','".$address."','".$login."','".$password."');"))
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
        <form action="cashiers.php" method="post" id="formEdit">
            <h4>Реєстрація касира</h4>
            <table>
                <tr><td>ПІБ:</td><td><input type="text" name="name" required></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" required></td></tr>
                <tr><td>Адреса:</td><td><input type="text" name="address" required></td></tr>
                <tr><td>Логін:</td><td><input type="text" name="login"></td></tr>
                <tr><td>Пароль:</td><td><input type="text" name="password"></td></tr>
            </table>
            <input type="submit" value="Зареєструвати"><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="cashiers.php" method="post" id="formEdit">
            <h4>Зміна персональної інформації касира</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="inputId" readonly></td></tr>
                <tr><td>ПІб:</td><td><input type="text" name="name" id="inputName" required></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" id="inputPhone" required></td></tr>
                <tr><td>Адреса:</td><td><input type="text" name="address" id="inputAddress" required></td></tr>
                <tr><td>Логін:</td><td><input type="text" name="login" id="inputLogin"></td></tr>
                <tr><td>Пароль:</td><td><input type="text" name="password" id="inputPassword"></td></tr>
            </table>
            <input type="submit" value="Змінити"><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="cashiers.php" method="post" id="formEdit">
            <h4>Ви справді бажаєте звільнити цього касира?</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="deleteId" readonly></td></tr>
                <tr><td>ПІБ:</td><td id="deleteName"></td></tr>
                <tr><td>Телефон:</td><td id="deletePhone"></td></tr>
                <tr><td>Адреса:</td><td id="deleteAddress"></td></tr>
                <tr><td>Логін:</td><td id="deleteLogin"></td></tr>
                <tr><td>Пароль:</td><td id="deletePassword"></td></tr>
            </table>
            <input type="submit" value="Звільнити"><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління касирами</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати касира</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва</th><th onclick="SortStrings(2)">Телефон</th><th onclick="SortStrings(3)">Адреса</th><th onclick="SortStrings(4)">Місце касира</th><th onclick="SortStrings(5)">Кошти</th><th onclick="SortStrings(6)">Логін</th><th onclick="SortStrings(7)">Пароль</th><th>Редагувати</th><th>Звільнити</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="phoneSearch" id="phoneSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="addressSearch" id="addressSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="positionSearch" id="positionSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="number" class="searchInput" name="lowerMoneySearch" id="lowerMoneySearch" oninput="CashiersSearch()" placeholder="Від">
                <input type="number" class="searchInput" name="upperMoneySearch" id="upperMoneySearch" oninput="CashiersSearch()" placeholder="До"></th>
            <th><input type="text" class="searchInput" name="loginSearch" id="loginSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="passwordSearch" id="passwordSearch" oninput="CashiersSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $cashiers = mysqli_query($connection, "SELECT cashiers.id, cashiers.name, cashiers.phone, cashiers.address, stations.name AS 'position', cashiers.money, cashiers.login, cashiers.password FROM cashiers, stations WHERE stations.id = cashiers.stationId ");
        while($cashier = mysqli_fetch_assoc($cashiers))
        {
            echo "<tr class='dataRow'><td hidden='true'>".join("</td><td>", $cashier)."</td><td><button class='actionButton' onclick='EditPerson(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeletePerson(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Звільнити</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>