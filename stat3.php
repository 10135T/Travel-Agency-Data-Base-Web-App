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
        e.data_plecare,
        e.data_intoarcere,
        CONCAT(g.nume, ' ', g.prenume) AS nume_ghid,
        t.nr_locuri,
        (
            SELECT COUNT(te.id_turist)
            FROM turisti_excursii te
            WHERE te.id_excursie = e.id_excursie
        ) AS numar_turisti
    FROM excursii e
    LEFT JOIN ghid g ON e.id_ghid = g.id_ghid
    LEFT JOIN transport t ON e.id_transport = t.id_transport
    ORDER BY numar_turisti DESC
    LIMIT 5
";

$result = $conn->query($sql);

echo "<h2>Excursii cu Cei Mai Mulți Turiști</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
        <th>Data Plecare</th>
        <th>Data Intoarcere</th>
        <th>Ghid</th>
        <th>Capacitate Maxima</th>
        <th>Numar Turisti</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        $locuri_disponibile = $row["nr_locuri"] - $row["numar_turisti"];

        echo "<tr>
            <td>" . $row["data_plecare"] . "</td>
            <td>" . $row["data_intoarcere"] . "</td>
            <td>" . $row["nume_ghid"] . "</td>
            <td>" . $row["nr_locuri"] . "</td>
            <td>" . $row["numar_turisti"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există excursii în baza de date.";
}

$conn->close();
?>
