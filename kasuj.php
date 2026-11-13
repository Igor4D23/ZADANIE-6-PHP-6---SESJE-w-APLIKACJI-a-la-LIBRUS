<?php

if(isset($_GET['id_ucznia']))
{
    $id = $_GET['id_ucznia'];

    require_once "dbconnect.php";

    $connection = mysqli_connect($host,$user,$password,$dbname);

    $query = "DELETE FROM uczen WHERE id='$id'";

    mysqli_query($connection,$query);

    mysqli_close($connection);
}

header("Location:index.php");

?>