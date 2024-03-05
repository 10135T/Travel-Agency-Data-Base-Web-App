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

// Verificăm dacă a fost setat ID-ul turistului
if (isset($_GET['id_turist'])) {
    $id_turist = $_GET['id_turist'];

    // Selectăm datele turistului pe care dorim să le modificăm
    $sql_select = "SELECT * FROM turisti WHERE id_turist = $id_turist";
    $result_select = $conn->query($sql_select);

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();

        // Procesăm datele actualizate atunci când formularul este trimis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Aici procesează datele actualizate și actualizează baza de date
            $nume_actualizat = $_POST['nume'];
            $prenume_actualizat = $_POST['prenume'];
            $cnp_actualizat = $_POST['cnp'];
            $dataNastere_actualizat = $_POST['dataNastere'];
            $sex_actualizat = $_POST['sex'];
            $tara_actualizat = $_POST['tara'];
            $strada_actualizat = $_POST['strada'];
            $numar_actualizat = $_POST['numar'];
            $oras_actualizat = $_POST['oras'];
            $judet_actualizat = $_POST['judet'];
            $bloc_actualizat = $_POST['bloc'];
            $scara_actualizat = $_POST['scara'];
            $etaj_actualizat = $_POST['etaj'];
            $telefon_actualizat = $_POST['telefon'];
            $email_actualizat = $_POST['email'];

            $sql_actualizare = "UPDATE turisti SET
                                nume = '$nume_actualizat',
                                prenume = '$prenume_actualizat',
                                cnp = '$cnp_actualizat',
                                dataNastere = '$dataNastere_actualizat',
                                sex = '$sex_actualizat',
                                tara = '$tara_actualizat',
                                strada = '$strada_actualizat',
                                numar = '$numar_actualizat',
                                oras = '$oras_actualizat',
                                judet = '$judet_actualizat',
                                bloc = '$bloc_actualizat',
                                scara = '$scara_actualizat',
                                etaj = '$etaj_actualizat',
                                telefon = '$telefon_actualizat',
                                email = '$email_actualizat'
                                WHERE id_turist = $id_turist";

            if ($conn->query($sql_actualizare) === TRUE) {
                // Verificăm dacă există excursii selectate pentru ștergere
                if (isset($_POST['excursie']) && is_array($_POST['excursie'])) {
                    foreach ($_POST['excursie'] as $id_excursie) {
                        // Ștergem relația din tabela de asociere
                        $sql_stergere_asociere = "DELETE FROM turisti_excursii WHERE id_turist = $id_turist AND id_excursie = $id_excursie";
                        $conn->query($sql_stergere_asociere);
                    }
                }

                // Redirecționează către pagina de căutare_turist.php după actualizare
                header("Location: cautare_turist.php");
                exit();
            } else {
                echo "Eroare la actualizarea datelor: " . $conn->error;
            }
        }
    } else {
        echo "Nu s-a găsit turistul cu ID-ul specificat.";
    }
} else {
    echo "ID-ul turistului nu este setat pentru modificare.";
}

// Selectăm excursiile asociate turistului
$sql_excursii_turist = "SELECT et.id_excursie, e.data_plecare, e.data_intoarcere, e.pret
                        FROM turisti_excursii et
                        INNER JOIN excursii e ON et.id_excursie = e.id_excursie
                        WHERE et.id_turist = $id_turist";
$excursii_result = $conn->query($sql_excursii_turist);

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
    <title>Modificare Turist</title>
</head>
<body>
    <h1>Modificare Turist</h1>
    
    <form method="post" action="">
        <!-- Aici adaugă câmpurile formularului pentru fiecare atribut al turistului -->
        Nume: <input type="text" name="nume" value="<?php echo $row['nume']; ?>"><br>
        Prenume: <input type="text" name="prenume" value="<?php echo $row['prenume']; ?>"><br>
        CNP: <input type="text" name="cnp" value="<?php echo $row['cnp']; ?>"><br>
        Data Nasterii: <input type="date" name="dataNastere" value="<?php echo $row['dataNastere']; ?>"><br>
        <label>Sex:</label>
        <div class="radio-group">
            <input type="radio" name="sex" value="M" id="sex-m" <?php echo ($row['sex'] == 'M') ? 'checked' : ''; ?> required>
            <label for="sex-m">M</label>
            
            <input type="radio" name="sex" value="F" id="sex-f" <?php echo ($row['sex'] == 'F') ? 'checked' : ''; ?> required>
            <label for="sex-f">F</label>
        </div>        
        Tara: <input type="text" name="tara" value="<?php echo $row['tara']; ?>"><br>
        Strada: <input type="text" name="strada" value="<?php echo $row['strada']; ?>"><br>
        Numar: <input type="text" name="numar" value="<?php echo $row['numar']; ?>"><br>
        Oras: <input type="text" name="oras" value="<?php echo $row['oras']; ?>"><br>
        Judet: <input type="text" name="judet" value="<?php echo $row['judet']; ?>"><br>
        Bloc: <input type="text" name="bloc" value="<?php echo $row['bloc']; ?>"><br>
        Scara: <input type="text" name="scara" value="<?php echo $row['scara']; ?>"><br>
        Etaj: <input type="text" name="etaj" value="<?php echo $row['etaj']; ?>"><br>
        Telefon: <input type="text" name="telefon" value="<?php echo $row['telefon']; ?>"><br>
        Email: <input type="text" name="email" value="<?php echo $row['email']; ?>"><br>

        <!-- Dropdown pentru excursii -->
        <?php
        if ($excursii_result->num_rows > 0) {
            echo '<label for="excursie">Excursii asociate:</label><br>';
            echo '<select name="excursie[]" multiple>';
            while ($excursie_row = $excursii_result->fetch_assoc()) {
                echo '<option value="' . $excursie_row['id_excursie'] . '">';
                echo $excursie_row['data_plecare'] . ' - ' . $excursie_row['data_intoarcere'] . ' - ' . $excursie_row['pret'] . ' lei';
                echo '</option>';
            }
            echo '</select>';
            echo '<br>';
        }
        ?>

        <input type="submit" value="Actualizează">
    </form>

</body>
</html>
