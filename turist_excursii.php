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

// Procesare formular
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificați dacă variabilele sunt setate înainte de a le utiliza
    $id_turist = isset($_POST['turist']) ? $_POST['turist'] : '';
    $excursii = isset($_POST['excursii']) ? $_POST['excursii'] : [];

    // Verificare dacă turistul există în baza de date
    $verificare_turist = "SELECT * FROM turisti WHERE id_turist = $id_turist";
    $result_turist = $conn->query($verificare_turist);

    if ($result_turist->num_rows == 0) {
        // Turistul nu există în baza de date
        $_SESSION['eroare'] = 'Eroare: Turistul nu există în baza de date!';
    } else {
        // Verificare dacă turistul deja are asociată excursia și dacă mai sunt locuri disponibile
        foreach ($excursii as $id_excursie) {
            // Verificare asociere turist - excursie
            $verificare_asociere = "SELECT * FROM turisti_excursii WHERE id_turist = $id_turist AND id_excursie = $id_excursie";
            $result_asociere = $conn->query($verificare_asociere);

            if ($result_asociere->num_rows > 0) {
                // Turistul are deja asociată această excursie
                $_SESSION['eroare'] = 'Eroare: Turistul are deja asociată excursia!';
                break; // O dată ce avem o eroare, nu mai este necesar să verificăm restul excursiilor
            }

            // Verificare locuri disponibile în excursie
            $verificare_locuri = "SELECT t.nr_locuri, COUNT(te.id_turist) AS numar_turisti
                      FROM transport t
                      LEFT JOIN excursii e ON t.id_transport = e.id_transport
                      LEFT JOIN turisti_excursii te ON e.id_excursie = te.id_excursie
                      WHERE te.id_excursie = $id_excursie
                      GROUP BY t.id_transport";


            $result_locuri = $conn->query($verificare_locuri);

            if ($result_locuri->num_rows > 0) {
                $row_locuri = $result_locuri->fetch_assoc();
                $locuri_disponibile = $row_locuri['nr_locuri'] - $row_locuri['numar_turisti'];

                if ($locuri_disponibile <= 0) {
                    $_SESSION['eroare'] = 'Eroare: Nu mai sunt locuri disponibile în excursia selectată!';
                    break;
                }
            }
        }

        // Adăugare relații turist - excursii
        if (!isset($_SESSION['eroare'])) {
            foreach ($excursii as $id_excursie) {
                $sql = "INSERT INTO turisti_excursii (id_turist, id_excursie) VALUES ('$id_turist', '$id_excursie')";
                $conn->query($sql);
            }
            $_SESSION['succes'] = 'Relațiile au fost adăugate cu succes!';
        }
    }

    // Redirecționare înapoi la pagina principală
    header('Location: turist_excursii.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adaugare_turist.css">
    <title>Asociere Turist - Excursii</title>
</head>
<body>

    <?php
    // Afișare mesaje de eroare sau succes
    if (isset($_SESSION['eroare'])) {
        echo '<div style="background-color: #f44336; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">' . $_SESSION['eroare'] . '</div>';
        unset($_SESSION['eroare']); // Elimină mesajul de eroare pentru a nu fi afișat și pe viitor
    }

    if (isset($_SESSION['succes'])) {
        echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center;">' . $_SESSION['succes'] . '</div>';
        unset($_SESSION['succes']); // Elimină mesajul de succes pentru a nu fi afișat și pe viitor
    }
    ?>

    <h1>Asociere Turist - Excursii</h1>

    <!-- Formular pentru adăugarea relațiilor turist - excursii -->
    <form action="" method="post">

        <label for="turist">Turist:</label>
        <select name="turist">
            <?php
            // Obține lista de turiști din baza de date
            $turisti_query = "SELECT id_turist, nume, prenume FROM turisti";
            $turisti_result = $conn->query($turisti_query);

            if ($turisti_result->num_rows > 0) {
                while ($turist_row = $turisti_result->fetch_assoc()) {
                    echo "<option value='" . $turist_row["id_turist"] . "'>" . $turist_row["nume"] . " " . $turist_row["prenume"] . "</option>";
                }
            }
            ?>
        </select><br>

        <label for="excursii">Excursii:</label>
        <select name="excursii[]" multiple>
            <?php
            // Obține lista de excursii din baza de date
            $excursii_query = "SELECT id_excursie, data_plecare, data_intoarcere, pret FROM excursii";
            $excursii_result = $conn->query($excursii_query);

            if ($excursii_result->num_rows > 0) {
                while ($excursie_row = $excursii_result->fetch_assoc()) {
                    echo "<option value='" . $excursie_row["id_excursie"] . "'>" . $excursie_row["data_plecare"] . " - " . $excursie_row["data_intoarcere"] . " - Pret: " . $excursie_row["pret"] . "</option>";
                }
            }
            ?>
        </select><br>

        <input type="submit" value="Asociază Turist - Excursii">
    </form>

    <h1>Interogare Baza de Date</h1>

    <!-- Formular pentru interogarea bazei de date -->
    <form action="interogare_date.php" method="post">
        <input type="submit" value="Interogare Baza de Date">
    </form>

</body>
</html>

<?php
$conn->close();
?>
