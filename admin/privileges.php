<?php
session_start();
$_SESSION["tab"]='privileges';
include("header.php");
include("../includes/DBConnect.php");
$result=1;
if(isset($_POST))
{ 
    if(isset($_POST["id"]))
    {
        $id = $_POST["id"];
        if(isset($_POST["name"]) && isset($_POST["discount"]))
        {
            $name = $_POST["name"];
            $discount = $_POST["discount"];
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "Назву знижки не задано!",
                "Величина знижки неправильна, має бути між 0% і 100%."];
            if(!strlen($name))
            {
                $result = -2;
            }
            else if(0 > $discount || $discount > 100)
            {
                $result = -3;
            }
            else
            {
                $query = "UPDATE privileges SET name = '".$name."', discount  = '".($discount / 100)."' WHERE privileges.id = '".$id."';";
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
            $query = "DELETE FROM privileges WHERE privileges.id = '".$id."';";
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
        if(isset($_POST["name"]) && isset($_POST["discount"]))
        {
            $name = $_POST["name"];
            $discount = $_POST["discount"];
            $results = [
                "Зміну виконано успішно!",
                "Помилка в базі даних.",
                "Назву знижки не задано!",
                "Величина знижки неправильна, має бути між 0% і 100%."];
            if(!strlen($name))
            {
                $result = -2;
            }
            else if(0 > $discount || $discount > 100)
            {
                $result = -3;
            }
            else if(mysqli_query($connection, "INSERT INTO privileges (id, name, discount) VALUES (NULL,'".$name."','".($discount / 100)."');"))
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
        <form action="privileges.php" method="post" id="formEdit">
            <h4>Встановлення пільги</h4>
            <table>
                <tr><td>Назва пільги:</td><td><input type="text" name="name" required></td></tr>
                <tr><td>Величина пільги:</td><td><input type="number" name="discount" step="any" min="0" max="100" required></td></tr>
            </table>
            <input type="submit" value="Встановити"><button type="button" class="actionButton" onclick="addPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="editPanel">
    <div class="editForm">
        <form action="privileges.php" method="post" id="formEdit">
            <h4>Зміна пільги</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="inputId" readonly></td></tr>
                <tr><td>Назва пільги:</td><td><input type="text" name="name" id="inputName" required></td></tr>
                <tr><td>Величина пільги:</td><td><input type="number" name="discount" id="inputDiscount" step="any" min="0" max="100" required></td></tr>
            </table>
            <input type="submit" value="Змінити"><button type="button" class="actionButton" onclick="editPanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<div class="fullPanel" id="deletePanel">
    <div class="editForm">
        <form action="privileges.php" method="post" id="formEdit">
            <h4>Ви справді бажаєте видалити цю пільгу?</h4>
            <table>
                <tr><td>Id:</td><td><input type="text" name="id" id="deleteId" readonly></td></tr>
                <tr><td>Назва пільги:</td><td id="deleteName"></td></tr>
                <tr><td>Величина пільги:</td><td id="deleteDiscount"></td></tr>
            </table>
            <input type="submit" value="Видалити"><button type="button" class="actionButton" onclick="deletePanel.style.display='none'">Скасувати</button>
        </form>
    </div>
</div>
<h2 class="tabHeader">Управління пільгами</h2>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?>
 </h3>
 <div class="toolbar">
    <button class="action" onclick="addPanel.style.display='flex'"><img class='bigbuttonImage' src='add.png' alt='X '><div class='buttonText'>Додати пільгу</div></button>
 </div>
<table id="dataTable">
    <tbody id="data">
        <tr><th hidden='true'></th><th onclick="SortStrings(1)">Назва</th><th onclick="SortNumbers(2)">Величина, %</th><th>Редагувати</th><th>Видалити</th></tr>
        <tr>
            <th hidden='true'><input type="text" name="nameSearch" id="idSearch" oninput="PrivilegeSearch()" placeholder="Пошук"></th>
            <th><input type="text" class="searchInput" name="nameSearch" id="nameSearch" oninput="PrivilegeSearch()" placeholder="Пошук"></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        $privileges = mysqli_query($connection, "SELECT * FROM privileges");
        while($privilege = mysqli_fetch_assoc($privileges))
        {
            echo "<tr class='dataRow'><td hidden='true'>".$privilege["id"]."</td><td>".$privilege["name"]."</td><td>".($privilege["discount"] * 100)."</td><td><button class='actionButton' onclick='EditPrivilege(this)'><img class='buttonImage' src='edit.png' alt='X '><div class='buttonText'>Редагувати</div></button></td><td><button class='actionButton' onclick='DeletePrivilege(this)'><img class='buttonImage' src='delete.png' alt='X '><div class='buttonText'>Видалити</div></button></td></tr>";
        }
        ?>
    </tbody>
</table>