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

// Verificăm dacă s-a transmis ID-ul turistului pentru ștergere
if (isset($_GET['id_turist'])) {
    $id_turist = $_GET['id_turist'];

    // Ștergem turistul cu ID-ul corespunzător
    $sql = "DELETE FROM turisti WHERE id_turist = $id_turist";

    if ($conn->query($sql) === TRUE) {
        // Redirect către pagina de căutare_turist.php
        header("Location: cautare_turist.php");
        exit();
    } else {
        echo "Eroare la ștergerea turistului: " . $conn->error;
    }
} else {
    echo "ID-ul turistului nu este setat pentru ștergere.";
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
    </style>
    <title>Șterge Turist</title>
</head>
<body>

    <?php
    // ... (restul codului pentru afișarea tabelului)
    ?>

</body>
</html>
