<?php
session_start();
include("../includes/DBConnect.php");
?>
<?php
if(isset($_POST) && isset($_POST["login"]) && isset($_POST["password"]))
{
    $results = [
        "Успішна авторизація!",
        "Помилка в базі даних",
        "Логін не задано",
        "Пароль не задано"
    ];
    $login = $_POST["login"];
    $password = $_POST["password"];
    if (!strlen($login)) {
        $result = -2;
    }
    else if(!strlen($password))
    {
        $result = -3;
    }
    else if(!($id = mysqli_query($connection, $query = "SELECT cashiers.id AS 'id', cashiers.name AS 'name', cashiers.stationId AS 'stationId' FROM cashiers WHERE cashiers.login='".$login."' AND cashiers.password='".$password."'")))
    {
        $result = -1;
    }
    else
    {
        $id = mysqli_fetch_assoc($id);
        $_SESSION['user'] = 'cashier';
        $_SESSION["id"] = $id['id'];
        $_SESSION["name"] = $id['name'];
        $_SESSION["stationId"] = $id['stationId'];
        echo '<meta http-equiv="refresh" content="0; url=/cashier/plan.php"/></head></html>';
        exit;
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
                <h1>Вхід для персоналу</h1>
                <form action="login.php" method="post" class="loginForm">
                    <?php
                    if($result > 0)
                    {
                        echo '<p class="warning">Логін або пароль було введено хибно!</p>';
                    }
                    ?>
                    <div class="loginTable">
                        <table>
                            <tr><td>Логін: </td><td><input type="text" name="login" id="inputLogin" placeholder="Ваший логін" required></td></tr>
                            <tr><td>Пароль: </td><td><input type="password" name="password" id="inputPassword" placeholder="Ваший пароль" required></td></tr>
                        </table>
                        <input type="submit" value="Вхід">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>