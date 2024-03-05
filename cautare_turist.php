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

// Funcție pentru generarea linkurilor de modificare și ștergere
function generateActions($id_turist) {
    return '<a href="modifica_turist.php?id_turist=' . $id_turist . '" style="color:#333;">Modifică</a> | <a href="sterge_turist.php?id_turist=' . $id_turist . '" style="color:#333;">Șterge</a>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cnp_cautat = $_POST['cnp_turist'];

    $sql = "SELECT * FROM turisti WHERE cnp LIKE '%$cnp_cautat%'
            OR nume LIKE '%$cnp_cautat%'
            OR prenume LIKE '%$cnp_cautat%'
            OR telefon LIKE '%$cnp_cautat%'
            OR dataNastere LIKE '%$cnp_cautat'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Afiseaza rezultatele sub forma de tabel
        echo '<table>';
        echo '<tr><th>Nume</th><th>Prenume</th><th>CNP</th><th>Data Nasterii</th><th>Sex</th><th>Tara</th><th>Strada</th><th>Numar</th><th>Oras</th><th>Judet</th><th>Bloc</th><th>Scara</th><th>Etaj</th><th>Telefon</th><th>Email</th><th>Acțiuni</th></tr>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['nume'] . '</td>';
            echo '<td>' . $row['prenume'] . '</td>';
            echo '<td>' . $row['cnp'] . '</td>';
            echo '<td>' . $row['dataNastere'] . '</td>';
            echo '<td>' . $row['sex'] . '</td>';
            echo '<td>' . $row['tara'] . '</td>';
            echo '<td>' . $row['strada'] . '</td>';
            echo '<td>' . $row['numar'] . '</td>';
            echo '<td>' . $row['oras'] . '</td>';
            echo '<td>' . $row['judet'] . '</td>';
            echo '<td>' . $row['bloc'] . '</td>';
            echo '<td>' . $row['scara'] . '</td>';
            echo '<td>' . $row['etaj'] . '</td>';
            echo '<td>' . $row['telefon'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . generateActions($row['id_turist']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<script>alert("Niciun turist găsit cu datele furnizate.");</script>';
    }
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
            color: #ffffff;
            margin: 0; 
        }

        form, table {
            width: 80%;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            background-color: #fff; 
            margin-top: 250px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
            color: #333;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #e6e6e6;
        }

        form {
            position: absolute;
            top: 60px;
        }

        h1 {
            position: absolute;
            top: 10px;
        }
        .back {
            color: #fff; 
            position: absolute;
            top: 5px;
        }        
    </style>
    <title>Cautare si Modificare Turist</title>
</head>
<body>
    <h1>Cautare si Modificare Turist</h1>
    
    <form method="post" action="">
        <input type="text" name="cnp_turist" placeholder="Cauta dupa CNP, Nume, Prenume, Telefon sau Data Nasterii">
        <input type="submit" value="Cauta">
    </form>

    <a href="cautare_date.html" class="back">Revenire la Cautare Date</a>

    <?php
    // ... codul PHP pentru manipularea bazei de date și afișarea rezultatelor
    ?>

</body>
</html>
