<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Preiați datele din formular
$nume = $_POST['nume'];
$prenume = $_POST['prenume'];
$cnp = $_POST['cnp'];
$dataNastere = $_POST['dataNastere'];
$sex = $_POST['sex'];
$tara = $_POST['tara'];
$strada = $_POST['strada'];
$numar = $_POST['numar'];
$oras = $_POST['oras'];
$judet = $_POST['judet'];
$bloc = $_POST['bloc'];
$scara = $_POST['scara'];
$strada = $_POST['strada'];
$etaj = $_POST['etaj'];
$telefon = $_POST['telefon'];
$email = $_POST['email'];

// Construiți și executați interogarea SQL pentru adăugarea unui turist
$sql = "INSERT INTO turisti (nume, prenume, CNP, sex, dataNastere, tara, strada, numar, oras, judet, bloc, scara, etaj, telefon, email) 
        VALUES ('$nume', '$prenume', '$cnp', '$sex', '$dataNastere', '$tara', '$strada', '$numar', '$oras', '$judet', '$bloc', '$scara', '$etaj', '$telefon', '$email')";

if ($conn->query($sql) === TRUE) {
    echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">Înregistrarea a fost adăugată cu succes!</div>';
} else {
    echo "Eroare: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>