<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Funcția pentru a genera meniul drop-down cu destinații, transport și ghid
function generateDropdowns($conn) {
    echo '<label for="destinatii">Destinații:</label>
    <select name="destinatii[]" multiple>';

    $destinatii_query = "SELECT * FROM destinatii";
    $result = $conn->query($destinatii_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id_destinatie"] . "'>" . $row["destinatie"] . "</option>";
        }
    }

    echo '</select><br>';

    echo '<label for="transport">Transport:</label>
    <select name="transport">';

    $transport_query = "SELECT * FROM transport";
    $result = $conn->query($transport_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id_transport"] . "'>" . $row["nr_locuri"] . " locuri</option>";
        }
    }

    echo '</select><br>';

    echo '<label for="ghid">Ghid:</label>
    <select name="ghid">';

    $ghid_query = "SELECT * FROM ghid";
    $result = $conn->query($ghid_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id_ghid"] . "'>" . $row["nume"] . " " . $row["prenume"] . "</option>";
        }
    }

    echo '</select><br>';
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adaugare_turist.css">
    <title>Adaugare excursie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $("select").select2({
                theme: "bootstrap4"
            });
        });
    </script>
</head>
<body>

    <h1>Adăugare Excursie</h1>

    <!-- Formular pentru adăugare -->
    <form action="adaugare_date_excursie.php" method="post">

        <label for="data_plecare">Data Plecare:</label>
        <input type="date" name="data_plecare" required><br>

        <label for="data_intoarcere">Data Întoarcere:</label>
        <input type="date" name="data_intoarcere" required><br>

        <label for="pret">Preț:</label>
        <input type="text" name="pret" pattern="-?[0-9]+(\.[0-9]+)?" title="Introduceți numere reale" required><br>';

// Adăugați următoarele linii pentru a afișa meniul drop-down cu destinații, transport și ghid
generateDropdowns($conn);

echo '<input type="submit" value="Adauga Excursie">
    </form>

    <h1>Interogare Baza de Date</h1>

    <!-- Formular pentru interogarea bazei de date -->
    <form action="interogare_date_excursii.php" method="post">
        <input type="submit" value="Interogare Baza de Date">
    </form>

</body>
</html>';

$conn->close();
?>
