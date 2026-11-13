<?php

session_start();

if(!isset($_SESSION['login']))
{
    header("Location:login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dziennik szkolny</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

<form action="wyloguj.php" method="post">
    <input type="submit" value="WYLOGUJ">
</form>

<br><br>

<form action="index.php" method="post">
    <select name="klasa" onchange="this.form.submit()">
        <option>Wybierz klasę</option>
        <option>1a</option>
        <option>1b</option>
        <option>2a</option>
        <option>2b</option>
    </select>
</form>

<br><br>

<?php

if(isset($_POST['klasa']))
{
    require_once "dbconnect.php";

    $connection = mysqli_connect($host,$user,$password,$dbname);

    $klasa = $_POST['klasa'];

    $query_wych = "
    SELECT *
    FROM klasa
    WHERE nazwa='$klasa'
    ";

    $result_wych = mysqli_query($connection,$query_wych);

    $wychowawca = mysqli_fetch_assoc($result_wych);

    echo "<h2>Klasa "
    .$klasa
    ." - "
    .$wychowawca['imie_wychowawcy']
    ." "
    .$wychowawca['nazwisko_wychowawcy']
    ."</h2>";

    $czy_wychowawca = false;

    if(
        $_SESSION['login'] ==
        $wychowawca['imie_wychowawcy']
    )
    {
        $czy_wychowawca = true;
    }

    $query = "
    SELECT *
    FROM uczen, klasa
    WHERE klasa.id = uczen.id_klasy
    AND klasa.nazwa = '$klasa'
    ";

    $result = mysqli_query($connection,$query);

    $liczba_wierszy = mysqli_num_rows($result);
    $suma_srednich = 0;

    echo "
    <table border='1'>
        <tr>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Średnia ocen</th>
            <th>Edytuj</th>
            <th>Kasuj</th>
        </tr>
    ";

    while($row = mysqli_fetch_assoc($result))
    {
        $suma_srednich += $row['Srednia_ocen'];

        echo "<tr>";

        echo "<td>".$row['Imie']."</td>";
        echo "<td>".$row['Nazwisko']."</td>";
        echo "<td>".$row['Srednia_ocen']."</td>";

        if($czy_wychowawca)
        {
            echo "
            <td>
                <a href='edytuj.php?id_ucznia=".$row['id']."'>
                    <button type='button'>edytuj</button>
                </a>
            </td>
            ";
        }
        else
        {
            echo "<td>-</td>";
        }

        if($czy_wychowawca)
        {
            echo "
            <td>
                <a href='kasuj.php?id_ucznia=".$row['id']."'>
                    <button type='button'>kasuj</button>
                </a>
            </td>
            ";
        }
        else
        {
            echo "<td>-</td>";
        }

        echo "</tr>";

        $id_klasy = $row['id_klasy'];
    }

    echo "</table>";

    if($liczba_wierszy > 0)
    {
        echo "<p><b>Średnia klasy: "
        . round($suma_srednich / $liczba_wierszy, 2)
        . "</b></p>";
    }

    if($czy_wychowawca)
    {
        echo "<hr>";

        echo "<h2>Dodaj ucznia</h2>";

        echo "
        <form action='dodaj_ucznia.php' method='post'>

            <input
            type='text'
            name='imie'
            placeholder='Imię'
            required><br><br>

            <input
            type='text'
            name='nazwisko'
            placeholder='Nazwisko'
            required><br><br>

            <input
            type='number'
            step='0.1'
            name='srednia_ocen'
            placeholder='Średnia ocen'
            required><br><br>

            <input
            type='hidden'
            name='id_klasy'
            value='$id_klasy'>

            <input
            type='submit'
            name='dodaj_ucznia'
            value='ZAPISZ'>

        </form>
        ";
    }

    mysqli_close($connection);
}

?>

</body>
</html>
