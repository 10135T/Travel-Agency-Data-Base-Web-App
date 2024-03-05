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
$sql = "SELECT t.id_turist, t.nume, t.prenume, t.cnp, t.dataNastere, t.sex, t.telefon, t.strada, t.numar, t.oras, t.judet, t.bloc, t.scara, t.etaj, t.email, GROUP_CONCAT(e.id_excursie ORDER BY e.id_excursie) AS excursii_ids, GROUP_CONCAT(CONCAT(e.data_plecare, ' - ', e.data_intoarcere) ORDER BY e.id_excursie SEPARATOR '<br>') AS excursii_date
        FROM turisti t
        LEFT JOIN turisti_excursii te ON t.id_turist = te.id_turist
        LEFT JOIN excursii e ON te.id_excursie = e.id_excursie
        GROUP BY t.id_turist
        ORDER BY t.id_turist";

$result = $conn->query($sql);

echo "<h2>Lista Turisti</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
    <th>Nume</th>
    <th>Prenume</th>
    <th>CNP</th>
    <th>Data Nasterii</th>
    <th>Sex</th>
    <th>Telefon</th>
    <th>Strada</th>
    <th>Numar</th>
    <th>Oras</th>
    <th>Judet</th>
    <th>Bloc</th>
    <th>Scara</th>
    <th>Etaj</th>
    <th>Email</th>
    <th>Excursii Participate (Date)</th>
    </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["nume"] . "</td>
        <td>" . $row["prenume"] . "</td>
        <td>" . $row["cnp"] . "</td>
        <td>" . $row["dataNastere"] . "</td>
        <td>" . $row["sex"] . "</td>
        <td>" . $row["telefon"] . "</td>
        <td>" . $row["strada"] . "</td>
        <td>" . $row["numar"] . "</td>
        <td>" . $row["oras"] . "</td>
        <td>" . $row["judet"] . "</td>
        <td>" . $row["bloc"] . "</td>
        <td>" . $row["scara"] . "</td>
        <td>" . $row["etaj"] . "</td>
        <td>" . $row["email"] . "</td>
        <td>" . $row["excursii_date"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există înregistrări în baza de date.";
}

$conn->close();
?>
