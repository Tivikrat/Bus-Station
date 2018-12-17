<?php
session_start();
include("header.php");
include("../includes/DBConnect.php");
if(isset($_POST) && isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['n']))
{
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];
    $n = $_POST['n'];
    $query = "SELECT (d2.price - d1.price";
    for ($i=2; $i < $n + 1; $i++) { 
        $query .= "+ d".($i * 2).".price - d".($i * 2 - 1).".price";
    }
    $query .= ")";
    for ($i=1; $i < $n + 1; $i++) { 
        $query .= ", s".$i.".name AS 's".$i."', r".$i.".name AS 'r".$i."'";
    }
    $query .= ", s".($n + 1).".name AS 's.".($n + 1)."'FROM stations s".($n + 1);
    for ($i=1; $i < $n + 1; $i++) { 
        $query .= ", stations s".$i;
    }
    for ($i=1; $i < $n + 1; $i++) { 
        $query .= ", routes r".$i;
    }
    for ($i=1; $i < $n * 2 + 1; $i++) { 
        $query .= ", destinations d".$i;
    }
    $query .= " WHERE d1.stationId = (SELECT stations.id FROM stations WHERE stations.name = '".$p1."') AND d".($n * 2).".stationId = (SELECT stations.id FROM stations WHERE stations.name = '".$p2."')";
    $query .= " AND d1.routeId = d2.routeId AND d1.time < d2.time";
    for ($i=2; $i < $n * 2; $i+=2) { 
        $query .= " AND d".$i.".stationId = d".($i + 1).".stationId AND d".($i + 1).".routeId = d".($i + 2).".routeId AND d".($i + 1).".time < d".($i + 2).".time";
    }
    $query .= " AND s1.id = d1.stationId";
    for ($i=2; $i < $n + 2; $i++) { 
        $query .= " AND s".$i.".id = d".($i * 2 - 2).".stationId";
    }
    for ($i=1; $i < $n + 1; $i++) { 
        $query .= " AND r".$i.".id = d".($i * 2 - 1).".routeId";
    }
    for ($i=1; $i < $n; $i++) { 
        $query .= " AND r".$i.".id <> r".($i + 1).".id";
    }
}
?>
<h2 class="tabHeader">Маршрути на <?php echo $date; ?></h2>
<div class="fullPanel">
    <div class="sendForm">
        <form action="path.php" method="post">
            Виберіть маршрут і кількість пересадок:
            <table>
                <tr><td>Пункт відправлення:</td><td><input type="text" name="p1" id="p1Input" oninput="searchNameChanged(this, p1List, VerifyAddPoint, 'destinationSearch')" required>
                    <ul class="optionsList" id="p1List"></ul></td></tr>
                <tr><td>Пункт прибуття:</td><td><input type="text" name="p2" id="p2Input" oninput="searchNameChanged(this, p2List, VerifyAddPoint, 'destinationSearch')" required>
                    <ul class="optionsList" id="p2List"></ul></td></tr>
                <tr><td>Кількість пересадок:</td><td><input type="number" name="n" id="nInput" min="2" required></td></tr>
            </table>
            <button type="button" id="addButton">Показати маршрути"</button>
        </form>
    </div>
</div>
<table id="dataTable">
    <tbody id="data">
        <?php
        if($query)
        {
            echo "<tr><th>Вартість</th><th>Пункт</th>";
            for ($i=0; $i < $n; $i++) { 
                echo "<th>Маршрут</th><th>Пункт</th>";
            }
            echo "</tr>";
            $paths = mysqli_query($connection, $query);
            while($path = mysqli_fetch_assoc($paths))
            {
                echo "<tr><td>".join("</td><td>", $path)."</td></tr>";
            }
        }
        ?>
    </tbody>
</table>