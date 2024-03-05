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

// Obține id_excursie din parametrul din URL
$id_excursie = isset($_GET['id_excursie']) ? $_GET['id_excursie'] : die('ID excursie lipsă.');

// Construiește și execută interogarea SQL pentru a obține lista de turiști dintr-o anumită excursie
$sql = "SELECT t.nume, t.prenume
        FROM turisti t
        INNER JOIN turisti_excursii te ON t.id_turist = te.id_turist
        WHERE te.id_excursie = $id_excursie";
$result = $conn->query($sql);

echo "<h2>Lista Turiști din Excursie</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
        <th>Nume</th>
        <th>Prenume</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["nume"] . "</td>
            <td>" . $row["prenume"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există înregistrări în baza de date pentru turiști în această excursie.";
}

$conn->close();
?>
