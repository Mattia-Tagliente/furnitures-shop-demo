<?php
session_start();

include('config.php');

$conn = mysqli_connect($host, $user, $password, $database);

if (false === $conn){
    exit("Errore: impossibile stabilire una connessione: ".mysqli_connect_error());
}

echo "Connesso: ".mysqli_get_host_info($conn)."<br>";
?>