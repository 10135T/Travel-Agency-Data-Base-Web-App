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
        e.id_excursie,
        e.data_plecare,
        e.data_intoarcere,
        t.nr_locuri,
        COUNT(te.id_turist) AS numar_turisti,
        (SELECT COUNT(te2.id_turist) FROM turisti_excursii te2 WHERE te2.id_excursie = e.id_excursie) / t.nr_locuri * 100 AS grad_ocupare
    FROM excursii e
    LEFT JOIN transport t ON e.id_transport = t.id_transport
    LEFT JOIN turisti_excursii te ON e.id_excursie = te.id_excursie
    GROUP BY e.id_excursie, e.data_plecare, e.data_intoarcere, t.nr_locuri
    HAVING grad_ocupare >= 90
    ORDER BY grad_ocupare DESC
";

$result = $conn->query($sql);

echo "<h2>Excursii cu Grad de Ocupare Peste 90%</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
        <th>Data Plecare</th>
        <th>Data Intoarcere</th>
        <th>Numar Turisti</th>
        <th>Locuri Disponibile</th>
        <th>Grad Ocupare (%)</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["data_plecare"] . "</td>
            <td>" . $row["data_intoarcere"] . "</td>
            <td>" . $row["numar_turisti"] . "</td>
            <td>" . $row["nr_locuri"] . "</td>
            <td>" . number_format($row["grad_ocupare"], 2) . "%</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există excursii cu grad de ocupare peste 90% în baza de date.";
}

$conn->close();
?>
