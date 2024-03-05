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

// Construiește și execută interogarea SQL pentru interogarea bazei de date pentru excursii
$sql = "SELECT e.id_excursie, e.data_plecare, e.data_intoarcere, e.pret, e.id_ghid,
               CONCAT(g.nume, ' ', g.prenume) AS nume_ghid,
               t.nr_locuri, t.pret AS pret_transport
        FROM excursii e
        LEFT JOIN ghid g ON e.id_ghid = g.id_ghid
        LEFT JOIN transport t ON e.id_transport = t.id_transport";
$result = $conn->query($sql);

echo "<h2>Lista Excursii</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
    <th>Data Plecare</th>
    <th>Data Intoarcere</th>
    <th>Pret</th>
    <th>Ghid</th>
    <th>Nr Locuri</th>
    <th>Pret Transport</th>
    <th>Destinatii</th>
    </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["data_plecare"] . "</td>
        <td>" . $row["data_intoarcere"] . "</td>
        <td>" . $row["pret"] . "</td>
        <td>" . $row["nume_ghid"] . "</td>
        <td>" . $row["nr_locuri"] . "</td>
        <td>" . $row["pret_transport"] . "</td>
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
    echo "Nu există înregistrări în baza de date pentru excursii.";
}

$conn->close();
?>
