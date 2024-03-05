<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

echo '<link rel="stylesheet" type="text/css" href="style_result.css">';

// Construiți și executați interogarea SQL pentru interogarea bazei de date
$sql = "SELECT * FROM turisti";
$result = $conn->query($sql);

echo "<h2>Lista Turisti</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
    <th>ID</th>
    <th>Nume</th>
    <th>Prenume</th>
    <th>CNP</th>
    <th>DataNasterii</th>
    <th>Sex</th>
    <th>Telefon</th>
    <th>Strada</th>
    <th>Numar</th>
    <th>Oras</th>
    <th>Judet</th>
    <th>Bloc</th>
    <th>Scara</th>
    <th>Etaj</th>
    <th>Telefon</th>
    <th>Email</th>
    </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>".$row["id_turist"]."</td>
        <td>".$row["nume"]."</td>
        <td>".$row["prenume"]."</td>
        <td>".$row["cnp"]."</td>
        <td>".$row["dataNastere"]."</td>
        <td>".$row["sex"]."</td>
        <td>".$row["tara"]."</td>
        <td>".$row["strada"]."</td>
        <td>".$row["numar"]."</td>
        <td>".$row["oras"]."</td>
        <td>".$row["judet"]."</td>
        <td>".$row["bloc"]."</td>
        <td>".$row["scara"]."</td>
        <td>".$row["etaj"]."</td>
        <td>".$row["telefon"]."</td>
        <td>".$row["email"]."</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există înregistrări în baza de date.";
}

$conn->close();
?>