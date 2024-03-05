<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Verificați dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificați dacă variabilele sunt setate înainte de a le utiliza
    $destinatie = isset($_POST['destinatie']) ? $_POST['destinatie'] : '';

    // Construiți și executați interogarea SQL pentru adăugarea unei excursii
    $sql = "INSERT INTO destinatii (destinatie) 
            VALUES ('$destinatie')";

    if ($conn->query($sql) === TRUE) {
    	echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">Înregistrarea a fost adăugată cu succes!</div>';
    } else {
        echo "Eroare: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
