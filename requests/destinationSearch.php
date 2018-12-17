<?php
session_start();
include("../includes/DBConnect.php");
if(isset($_GET) && isset($_GET["input"]) && strlen($_GET["input"]) > 0)
{
    $text = trim(mb_strtolower($_GET["input"], 'UTF-8'));
    $stations = mysqli_query($connection, "SELECT stations.name AS 'name' FROM stations WHERE LOWER(stations.name) LIKE LOWER('%".$text."%');");
    while($station = mysqli_fetch_assoc($stations))
    {
        echo $station['name']."\n";
    }
}
?>
