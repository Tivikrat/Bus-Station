<?php

$connection = mysqli_connect('127.0.0.1', 'root', '', 'busstation');
if($connection === false)
{
    echo "Відмовлено в доступі до бази даних!";
    exit();
}
?>