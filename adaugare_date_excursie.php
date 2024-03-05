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
    $pret = isset($_POST['pret']) ? $_POST['pret'] : '';
    $data_plecare = isset($_POST['data_plecare']) ? $_POST['data_plecare'] : '';
    $data_intoarcere = isset($_POST['data_intoarcere']) ? $_POST['data_intoarcere'] : '';
    $id_transport = isset($_POST['transport']) ? $_POST['transport'] : '';
    $id_ghid = isset($_POST['ghid']) ? $_POST['ghid'] : '';

    // Verificare dacă id_transport și id_ghid există în tabelele corespunzătoare
    $verificare_transport = "SELECT * FROM transport WHERE id_transport = $id_transport";
    $result_transport = $conn->query($verificare_transport);

    $verificare_ghid = "SELECT * FROM ghid WHERE id_ghid = $id_ghid";
    $result_ghid = $conn->query($verificare_ghid);

    if ($result_transport->num_rows == 0 || $result_ghid->num_rows == 0) {
        // Id_transport sau id_ghid nu există în tabelele corespunzătoare
        die('<div style="background-color: #f44336; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">Eroare: Id_transport sau id_ghid nu există în tabelele corespunzătoare!</div>');
    }

    // Construiește și executați interogarea SQL pentru adăugarea unei excursii
    $sql = "INSERT INTO excursii (data_plecare, data_intoarcere, pret, id_transport, id_ghid) 
            VALUES ('$data_plecare','$data_intoarcere','$pret','$id_transport','$id_ghid')";

    if ($conn->query($sql) === TRUE) {
        // Obține ultimul ID introdus în excursii
        $id_excursie = $conn->insert_id;

        // Verifică dacă au fost selectate destinații
        if (isset($_POST['destinatii']) && is_array($_POST['destinatii']) && count($_POST['destinatii']) > 0) {
            foreach ($_POST['destinatii'] as $id_destinatie) {
                // Adaugă asocierea între excursie și destinații în tabela de asociere
                $sql_destinatii = "INSERT INTO destinatii_excursie (id_excursie, id_destinatie) 
                                   VALUES ('$id_excursie','$id_destinatie')";
                $conn->query($sql_destinatii);
            }
        }

        echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">Înregistrarea a fost adăugată cu succes!</div>';
    } else {
        echo "Eroare: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
