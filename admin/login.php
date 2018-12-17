<?php
session_start();
?>
<!doctype html>
<html>
	<head>
<?php
if(isset($_POST) && isset($_POST["login"]) && isset($_POST["password"]))
{
    if($_POST["login"] == 'login' && $_POST["password"] == 'password')
    {
        $_SESSION["user"] = "Admin";
        echo '<meta http-equiv="refresh" content="0; url=/admin/routes.php"/></head></html>';
        exit;
    }
    else
    {
        $error = 1;
    }
}
?>
		<meta charset="UTF-8">
		<!-- <meta http-equiv="refresh" content="10"> -->
		<title>Railway-Booker</title>
		<link rel = 'stylesheet' href = 'login.css' type = 'text/css'>
        <script src="login.js"></script>
	</head>
	<body>
        <div class="fullPanel" id="deletePanel">
            <div class="editForm">
                <h1>Вхід для адміністратора</h1>
                <form action="login.php" method="post" class="loginForm">
                    <?php
                        if($error == 1)
                        {
                            echo '<p class="warning">Логін або пароль було введено хибно!</p>';
                        }
                    ?>
                    <div class="loginTable">
                        <table>
                            <tr><td>Логін: </td><td><input type="text" name="login" id="inputLogin" oninput="Check(this)" placeholder="Ваший логін" required></td></tr>
                            <tr><td>Пароль: </td><td><input type="password" name="password" id="inputPassword" oninput="Check(this)" placeholder="Ваший пароль" required></td></tr>
                        </table>
                        <input type="submit" class="buttonLogin" value="Увійти">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>