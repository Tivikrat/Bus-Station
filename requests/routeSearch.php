<?php
session_start();
include("../includes/DBConnect.php");
if(isset($_GET) && isset($_GET["input"]) && strlen($_GET["input"]) > 0)
{
    $text = trim(mb_strtolower($_GET["input"], 'UTF-8'));
    $routes = mysqli_query($connection, "SELECT routes.name AS 'name' FROM routes WHERE LOWER(routes.name) LIKE LOWER('%".$text."%');");
    while($route = mysqli_fetch_assoc($routes))
    {
        echo $route['name']."\n";
    }
}
?>
