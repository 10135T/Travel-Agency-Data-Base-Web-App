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

$sql = "
    SELECT
        t.nume AS nume_turist,
        t.prenume AS prenume_turist,
        (
            SELECT COUNT(te.id_excursie)
            FROM turisti_excursii te
            WHERE te.id_turist = t.id_turist
        ) AS numar_excursii
    FROM turisti t
    WHERE (
        SELECT COUNT(te.id_excursie)
        FROM turisti_excursii te
        WHERE te.id_turist = t.id_turist
    ) >= 2
";

$result = $conn->query($sql);

echo "<h2>Turiști cu cel puțin două excursii</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
        <th>Nume Turist</th>
        <th>Prenume Turist</th>
        <th>Numar Excursii</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["nume_turist"] . "</td>
            <td>" . $row["prenume_turist"] . "</td>
            <td>" . $row["numar_excursii"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există turiști cu cel puțin două excursii în baza de date.";
}

$conn->close();
?>
