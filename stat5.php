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
        COUNT(te.id_excursie) AS numar_excursii
    FROM turisti t
    LEFT JOIN turisti_excursii te ON t.id_turist = te.id_turist
    GROUP BY t.id_turist
    HAVING numar_excursii >= 2 AND t.bloc IS NOT NULL AND t.scara IS NOT NULL AND t.etaj IS NOT NULL
";

$result = $conn->query($sql);

echo "<h2>Turiști cu cel puțin două excursii care locuiesc la bloc</h2>";

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
    echo "Nu există turiști cu cel puțin două excursii care locuiesc la bloc în baza de date.";
}

$conn->close();
?>
