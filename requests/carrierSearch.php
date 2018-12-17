<?php
session_start();
include("../includes/DBConnect.php");
if(isset($_GET) && isset($_GET["input"]) && strlen($_GET["input"]) > 0)
{
    $text = trim(mb_strtolower($_GET["input"], 'UTF-8'));
    $carriers = mysqli_query($connection, "SELECT carriers.name AS 'name' FROM carriers WHERE LOWER(carriers.name) LIKE LOWER('%".$text."%');");
    while($carrier = mysqli_fetch_assoc($carriers))
    {
        echo $carrier['name']."\n";
    }
}
?>
