<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Verificăm dacă a fost setat ID-ul excursiei
if (isset($_GET['id_excursie'])) {
    $id_excursie = $_GET['id_excursie'];

    // Selectăm datele excursiei pe care dorim să le modificăm
    $sql_select = "SELECT e.*, g.nume AS ghid_nume, g.prenume AS ghid_prenume,
                            t.nr_locuri AS transport_locuri, t.pret AS transport_pret,
                            t.id_transport AS id_transport
                   FROM excursii e
                   LEFT JOIN ghid g ON e.id_ghid = g.id_ghid
                   LEFT JOIN transport t ON e.id_transport = t.id_transport
                   WHERE e.id_excursie = $id_excursie";
    $result_select = $conn->query($sql_select);

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();

        // Procesăm datele actualizate atunci când formularul este trimis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Aici procesează datele actualizate și actualizează baza de date
            $data_plecare_actualizat = $_POST['data_plecare'];
            $data_intoarcere_actualizat = $_POST['data_intoarcere'];
            $pret_actualizat = $_POST['pret'];
            $ghid_actualizat = $_POST['ghid'];
            $nr_locuri_transport_actualizat = $_POST['nr_locuri_transport'];
            $pret_transport_actualizat = $_POST['pret_transport'];
            $id_transport_actualizat = $_POST['id_transport'];
            $destinatii_actualizate = isset($_POST['destinatii']) ? $_POST['destinatii'] : [];

            // Actualizează informațiile în tabela excursii
            $sql_actualizare_excursie = "UPDATE excursii SET
                                        data_plecare = '$data_plecare_actualizat',
                                        data_intoarcere = '$data_intoarcere_actualizat',
                                        pret = '$pret_actualizat',
                                        id_ghid = '$ghid_actualizat',
                                        id_transport = '$id_transport_actualizat'
                                        WHERE id_excursie = $id_excursie";

            if ($conn->query($sql_actualizare_excursie) === TRUE) {
                // Șterge destinațiile existente pentru excursie
                $sql_stergere_destinatii = "DELETE FROM destinatii_excursie WHERE id_excursie = $id_excursie";
                $conn->query($sql_stergere_destinatii);

                // Adaugă noile destinații pentru excursie
                foreach ($destinatii_actualizate as $id_destinatie) {
                    $sql_adaugare_destinatie = "INSERT INTO destinatii_excursie (id_excursie, id_destinatie)
                                                VALUES ('$id_excursie', '$id_destinatie')";
                    $conn->query($sql_adaugare_destinatie);
                }

                // Redirecționează către pagina de interogare a excursiilor după actualizare
                header("Location: cautare_excursie.php");
                exit();
            } else {
                echo "Eroare la actualizarea datelor: " . $conn->error;
            }
        }
    } else {
        echo "Nu s-a găsit excursia cu ID-ul specificat.";
    }
} else {
    echo "ID-ul excursiei nu este setat pentru modificare.";
}

// Obțineți toți ghizii disponibili pentru dropdown
$ghid_query = "SELECT * FROM ghid";
$ghid_result = $conn->query($ghid_query);

// Obțineți toate tipurile de transport pentru dropdown
$transport_query = "SELECT * FROM transport";
$transport_result = $conn->query($transport_query);

// Obțineți toate destinatiile pentru dropdown
$destinatii_query = "SELECT * FROM destinatii";
$destinatii_result = $conn->query($destinatii_query);

// Obțineți destinatiile asociate excursiei curente
$destinatii_selectate_query = "SELECT id_destinatie FROM destinatii_excursie WHERE id_excursie = $id_excursie";
$destinatii_selectate_result = $conn->query($destinatii_selectate_query);
$destinatii_selectate = [];
while ($destinatie_selectata = $destinatii_selectate_result->fetch_assoc()) {
    $destinatii_selectate[] = $destinatie_selectata['id_destinatie'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adaugare_turist.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
        }

        form {
            width: 80%;
            margin-top: 20px;
        }
    </style>
    <title>Modificare Excursie</title>
</head>
<body>
    <h1>Modificare Excursie</h1>
    
    <form method="post" action="">
        <!-- Aici adaugă câmpurile formularului pentru fiecare atribut al excursiei -->
        Data Plecare: <input type="date" name="data_plecare" value="<?php echo $row['data_plecare']; ?>"><br>
        Data Intoarcere: <input type="date" name="data_intoarcere" value="<?php echo $row['data_intoarcere']; ?>"><br>
        Pret: <input type="text" name="pret" value="<?php echo $row['pret']; ?>"><br>
        
        <!-- Câmpuri pentru informațiile din tabela ghid -->
        Ghid:
        <br>
        <select name="ghid">
            <?php
            if ($ghid_result->num_rows > 0) {
                while ($ghid_row = $ghid_result->fetch_assoc()) {
                    $selected = ($ghid_row['id_ghid'] == $row['id_ghid']) ? 'selected' : '';
                    echo "<option value='" . $ghid_row['id_ghid'] . "' $selected>" . $ghid_row['nume'] . ' ' . $ghid_row['prenume'] . "</option>";
                }
            }
            ?>
        </select><br>

        <!-- Câmpuri pentru informațiile din tabela transport -->
        <br>
        Tip Transport:
        <br>
        <select name="id_transport">
            <?php
            if ($transport_result->num_rows > 0) {
                while ($transport_row = $transport_result->fetch_assoc()) {
                    $selected = ($transport_row['id_transport'] == $row['id_transport']) ? 'selected' : '';
                    echo "<option value='" . $transport_row['id_transport'] . "' $selected>" . $transport_row['nr_locuri'] . ' locuri, ' . $transport_row['pret'] . " lei</option>";
                }
            }
            ?>
        </select><br>

        <!-- Câmpuri pentru dropdown-ul destinatiilor -->
        <br>
        Destinatii:
        <br>
        <select name="destinatii[]" multiple>
            <?php
            if ($destinatii_result->num_rows > 0) {
                while ($destinatie_row = $destinatii_result->fetch_assoc()) {
                    $selected = (in_array($destinatie_row['id_destinatie'], $destinatii_selectate)) ? 'selected' : '';
                    echo "<option value='" . $destinatie_row['id_destinatie'] . "' $selected>" . $destinatie_row['destinatie'] . "</option>";
                }
            }
            ?>
        </select><br>

        <br>
        <input type="submit" value="Actualizează">
    </form>

</body>
</html>
