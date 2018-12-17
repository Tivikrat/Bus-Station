<?php
session_start();
include("../includes/DBConnect.php");
if(isset($_GET) && isset($_GET["input"]) && strlen($_GET["input"]) > 0)
{
    $text = trim(mb_strtolower($_GET["input"], 'UTF-8'));
    $privileges = mysqli_query($connection, "SELECT privileges.name AS 'name' FROM privileges WHERE LOWER(privileges.name) LIKE LOWER('%".$text."%');");
    while($privilege = mysqli_fetch_assoc($privileges))
    {
        echo $privilege['name']."\n";
    }
}
?>
