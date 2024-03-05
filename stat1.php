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

// Construiește și execută interogarea SQL complexă pentru a obține lista de excursii cu locuri disponibile
$sql = "SELECT e.id_excursie, e.data_plecare, e.data_intoarcere, e.pret, g.nume AS nume_ghid, t.nr_locuri,
               COUNT(te.id_turist) AS numar_turisti,
               (COUNT(te.id_turist) / t.nr_locuri) * 100 AS grad_ocupare
        FROM excursii e
        LEFT JOIN ghid g ON e.id_ghid = g.id_ghid
        LEFT JOIN transport t ON e.id_transport = t.id_transport
        LEFT JOIN turisti_excursii te ON e.id_excursie = te.id_excursie
        WHERE e.id_excursie NOT IN (
            SELECT te2.id_excursie
            FROM turisti_excursii te2
            GROUP BY te2.id_excursie
            HAVING COUNT(te2.id_turist) >= t.nr_locuri
        )
        GROUP BY e.id_excursie, e.data_plecare, e.data_intoarcere, e.pret, g.nume, t.nr_locuri";

$result = $conn->query($sql);

echo "<h2>Excursii cu Locuri Disponibile</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
        <th>Data Plecare</th>
        <th>Data Intoarcere</th>
        <th>Pret</th>
        <th>Ghid</th>
        <th>Nr Locuri Disponibile</th>
        <th>Grad Ocupare (%)</th>
        <th>Destinatii</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        $locuri_disponibile = $row["nr_locuri"] - $row["numar_turisti"];

        echo "<tr>
            <td>" . $row["data_plecare"] . "</td>
            <td>" . $row["data_intoarcere"] . "</td>
            <td>" . $row["pret"] . "</td>
            <td>" . $row["nume_ghid"] . "</td>
            <td>" . ($locuri_disponibile > 0 ? "<a href='lista_turisti.php?id_excursie=" . $row["id_excursie"] . "&locuri_disponibile=" . $locuri_disponibile . "' style='color: #333;'>" . $locuri_disponibile . "</a>" : 0) . "</td>
            <td>" . number_format($row["grad_ocupare"], 2) . "%</td>
            <td>";

        // Construiește și execută interogarea SQL pentru a obține destinațiile asociate excursiei curente
        $dest_query = "SELECT destinatie FROM destinatii
                       INNER JOIN destinatii_excursie ON destinatii.id_destinatie = destinatii_excursie.id_destinatie
                       WHERE destinatii_excursie.id_excursie = " . $row["id_excursie"];
        $dest_result = $conn->query($dest_query);

        if ($dest_result->num_rows > 0) {
            while ($dest_row = $dest_result->fetch_assoc()) {
                echo $dest_row["destinatie"] . "<br>";
            }
        } else {
            echo "Nu există destinații asociate.";
        }

        echo "</td></tr>";
    }

    echo "</table>";
} else {
    echo "Nu există excursii cu locuri disponibile în baza de date.";
}

$conn->close();
?>
