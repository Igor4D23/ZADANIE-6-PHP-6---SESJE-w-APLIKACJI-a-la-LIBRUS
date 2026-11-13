<?php

require_once "dbconnect.php";

$connection = mysqli_connect($host,$user,$password,$dbname);

if(isset($_GET['id_ucznia']))
{
    $id = $_GET['id_ucznia'];

    $query = "SELECT * FROM uczen WHERE id=$id";

    $result = mysqli_query($connection,$query);

    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edycja</title>
</head>
<body>

<form action="edytuj.php" method="post">

    Imię:<br>
    <input type="text" name="imie"
    value="<?php echo $row['Imie']; ?>"><br>

    Nazwisko:<br>
    <input type="text" name="nazwisko"
    value="<?php echo $row['Nazwisko']; ?>"><br>

    Średnia:<br>
    <input type="number" step="0.1"
    name="srednia_ocen"
    value="<?php echo $row['Srednia_ocen']; ?>"><br>

    <input type="hidden"
    name="id_ucznia"
    value="<?php echo $row['id']; ?>">

    <input type="submit"
    name="zapisz_edycja"
    value="Zapisz">

</form>

</body>
</html>

<?php
}

if(isset($_POST['zapisz_edycja']))
{
    $id = $_POST['id_ucznia'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $srednia_ocen = $_POST['srednia_ocen'];

    $query = "
    UPDATE uczen
    SET
        Imie='$imie',
        Nazwisko='$nazwisko',
        Srednia_ocen='$srednia_ocen'
    WHERE id='$id'
    ";

    mysqli_query($connection,$query);

    header("Location:index.php");
}

mysqli_close($connection);

?>