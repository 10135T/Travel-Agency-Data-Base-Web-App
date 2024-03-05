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
$sql = "SELECT * FROM destinatii";
$result = $conn->query($sql);

echo "<h2>Lista Destinatii</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
    <th>Destinatie</th>
    </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>".$row["destinatie"]."</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Nu există înregistrări în baza de date.";
}

$conn->close();
?>