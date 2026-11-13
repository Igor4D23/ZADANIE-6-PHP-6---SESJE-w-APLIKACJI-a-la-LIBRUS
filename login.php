<?php
session_start();

if(isset($_POST['zaloguj']))
{
    require_once "dbconnect.php";

    $connection = mysqli_connect($host,$user,$password,$dbname);

    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $query = "
    SELECT *
    FROM uczen
    WHERE Imie='$login'
    AND Nazwisko='$haslo'
    ";

    $result = mysqli_query($connection,$query);

    if(mysqli_num_rows($result)>0)
    {
        $_SESSION['login'] = $login;
        $_SESSION['haslo'] = $haslo;

        header("Location:index.php");
        exit();
    }
    else
    {
        $blad = "ZŁE DANE LOGOWANIA";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Logowanie</title>
<link rel="stylesheet" href="main.css">
</head>
<body>

<h2>Logowanie</h2>

<form method="post">

<input type="text"
name="login"
placeholder="LOGIN"
required><br><br>

<input type="text"
name="haslo"
placeholder="HASŁO"
required><br><br>

<input type="submit"
name="zaloguj"
value="ZALOGUJ">

</form>

<?php
if(isset($blad))
{
    echo "<p style='color:red'>$blad</p>";
}
?>

</body>
</html>