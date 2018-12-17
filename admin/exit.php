<?php
unset($_SESSION['user']);
unset($_SESSION["id"]);
unset($_SESSION["name"]);
echo '<meta http-equiv="refresh" content="0; url=/admin/login.php"/></head></html>';
?>