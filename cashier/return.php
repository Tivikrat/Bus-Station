<?php
session_start();
$_SESSION["tab"]='return';
include("header.php");
include("../includes/DBConnect.php");
$result = 1;
if(isset($_POST) && isset($_POST['id']))
{
    $id = $_POST['id'];
    $results = ["Підлягає поверненню!", "Помилка в базі даних.", "Не підлягає поверненню!"];
    if(mysqli_num_rows(mysqli_query($connection, $query = "SELECT tickets.id FROM tickets WHERE tickets.id = '".$id."'")) == 0)
    {
        $result = -2;
    }
    else if(mysqli_query($connection, $query = "DELETE FROM tickets WHERE tickets.id = '".$id."';"))
    {
        $result = 0;
    }
    else
    {
        $result = -1;
    }
}
?>
<script src="return.js"></script>
<h3 class=<?php echo ($result > 0 ? '"hiddenMessage">' : ($result == 0 ? '"successMessage">'.$results[$result] : '"errorMessage">'.$results[-$result]));
 if($result == -1) echo "\n".$query;?></h3>
<h2 class="tabHeader">Повернення білетів</h2>
<div class="fullPanel">
    <div class="sendForm">
        <form action="return.php" method="post">
            Номер білету:
            <input type="number" name="id" id="inputId" value="<?php echo $_POST['id']; ?>" required>
            <input type="submit" value="Повернути">
        </form>
    </div>
</div>