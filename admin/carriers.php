<?php
session_start();
$_SESSION["tab"]='carriers';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["id"]))
    {
        $id = $_POST["id"];
        if(isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["address"]))
        {
            $name = $_POST["name"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $results = ["Зміну виконано успішно!", "Помилка в базі даних при зміні.", "Назву перевізника не задано!", "Телефон не задано!", "Адреса не задана!"];
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
                $query = "UPDATE carriers SET name = '".$name."', phone = '".$phone."', address = '".$address."' WHERE carriers.id = '".$id."';";
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
            $results = ["Видалено успішно!", "Помилка в базі даних при зміні."];
            $query = "DELETE FROM carriers WHERE carriers.id = '".$id."';";
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
            $results = ["Реєстрацію виконано успішно!", "Помилка в базі даних при реєстрації.", "Назву перевізника не задано!", "Телефон не задано!", "Адреса не задана!"];
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
            else if(mysqli_query($connection, "INSERT INTO carriers (id, name, phone, address) VALUES (NULL,'".$name."','".$phone."','".$address."');"))
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
        <form action="carriers.php" method="post" id="formEdit">
            <h4>Реєстрація перевізника</h4>
            <table>
                <tr><td>Назва:</td><td><input type="text" name="name" id="addName" required></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" required></td></tr>
                <tr><td>Адреса:</td><td><input type="text" name="address" required></td></tr>
            </table>
            <input type="submit" value="Зареєструвати"><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="carriers.php" method="post" id="formEdit">
            <h4>Зміна персональної інформації перевізника</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="inputId" readonly></td></tr>
                <tr><td>Назва:</td><td><input type="text" name="name" id="inputName" required></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" id="inputPhone" required></td></tr>
                <tr><td>Адреса:</td><td><input type="text" name="address" id="inputAddress" required></td></tr>
            </table>
            <input type="submit" value="Змінити"><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="carriers.php" method="post" id="formEdit">
            <h4>Ви справді бажаєте відмовитися цього перевізника?</h4>
            <h4>Всі маршрути перевізника будуть видалені. Куплені за ними квитки необхідно буде відшкодувати пасажирам!</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="deleteId" readonly></td></tr>
                <tr><td>Назва:</td><td id="deleteName"></td></tr>
                <tr><td>Телефон:</td><td id="deletePhone"></td></tr>
                <tr><td>Адреса:</td><td id="deleteAddress"></td></tr>
            </table>
            <input type="submit" value="Відмовитися"><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління перевізниками</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'; addName.focus()"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати перевізника</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва</th><th onclick="SortStrings(2)">Телефон</th><th onclick="SortStrings(3)">Адреса</th><th>Редагувати</th><th>Відмовитися</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="PersonSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="PersonSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="phoneSearch" id="phoneSearch" oninput="PersonSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="addressSearch" id="addressSearch" oninput="PersonSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $carriers = mysqli_query($connection, "SELECT * FROM carriers");
        while($carrier = mysqli_fetch_assoc($carriers))
        {
            echo "<tr class='dataRow'><td hidden='true'>".$carrier["id"]."</td><td>".$carrier["name"]."</td><td>".$carrier["phone"]."</td><td>".$carrier["address"]."</td><td><button class='actionButton' onclick='EditPerson(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeletePerson(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Відмовитися</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>