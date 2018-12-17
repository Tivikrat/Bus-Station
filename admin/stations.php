<?php
session_start();
$_SESSION["tab"]='stations';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["id"]))
    {
        $id = $_POST["id"];
        if(isset($_POST["name"]))
        {
            $name = $_POST["name"];
            $discount = $_POST["discount"];
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "Назву станції не задано!"];
            if(!strlen($name))
            {
                $result = -2;
            }
            else
            {
                $query = "UPDATE stations SET name = '".$name."' WHERE stations.id = '".$id."';";
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
            $query = "DELETE FROM stations WHERE stations.id = '".$id."';";
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
        if(isset($_POST["name"]))
        {
            $name = $_POST["name"];
            $discount = $_POST["discount"];
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "Назву станції не задано!"];
            if(!strlen($name))
            {
                $result = -2;
            }
            else if(mysqli_query($connection, "INSERT INTO stations (id, name) VALUES (NULL,'".$name."');"))
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
        <form action="stations.php" method="post" id="formEdit">
            <h4>Додання станції</h4>
            <table>
                <tr><td>Назва станції:</td><td><input type="text" name="name" required></td></tr>
            </table>
            <input type="submit" value="Додати"><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="stations.php" method="post" id="formEdit">
            <h4>Зміна станції</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="inputId" readonly></td></tr>
                <tr><td>Назва пільги:</td><td><input type="text" name="name" id="inputName" required></td></tr>
            </table>
            <input type="submit" value="Змінити"><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="stations.php" method="post" id="formEdit">
            <h4>Ви справді бажаєте видалити цю станцію?</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="deleteId" readonly></td></tr>
                <tr><td>Назва пільги:</td><td id="deleteName"></td></tr>
            </table>
            <input type="submit" value="Видалити"><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління станціями</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати станцію</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва</th><th>Редагувати</th><th>Видалити</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="PrivilegeSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="PrivilegeSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $stations = mysqli_query($connection, "SELECT * FROM stations");
        while($station = mysqli_fetch_assoc($stations))
        {
            echo "<tr class='dataRow'><td hidden='true'>".$station["id"]."</td><td>".$station["name"]."</td><td><button class='actionButton' onclick='EditStation(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeleteStation(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Видалити</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>