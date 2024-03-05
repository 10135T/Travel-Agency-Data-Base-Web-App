<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agentie";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adaugare_turist.css">
    <title>Adaugare Turist</title>
</head>
<body>

    <h1>Adăugare Turist</h1>

    <!-- Formular pentru adăugarea de turisti -->
    <form action="adaugare_date_turist.php" method="post">
        <label for="nume">Nume:</label>
        <input type="text" name="nume" required><br>

        <label for="prenume">Prenume:</label>
        <input type="text" name="prenume" required><br>

        <label for="cnp">CNP:</label>
        <input type="text" name="cnp" required><br>

        <label for="dataNastere">Data Nasterii:</label>
        <input type="date" name="dataNastere" required><br>

        <label>Sex:</label>
        <div class="radio-group">
            <input type="radio" name="sex" value="M" id="sex-m" required>
            <label for="sex-m">M</label>
            
            <input type="radio" name="sex" value="F" id="sex-f" required>
            <label for="sex-f">F</label>
        </div>

        <label for="tara">Tara:</label>
        <input type="text" name="tara" required><br>

        <label for="strada">Strada:</label>
        <input type="text" name="strada" required><br>

        <label for="numar">Numar:</label>
        <input type="text" name="numar" required><br>

        <label for="oras">Oras:</label>
        <input type="text" name="oras" required><br>

        <label for="judet">Judet:</label>
        <input type="text" name="judet" required><br>

        <label for="bloc">Bloc:</label>
        <input type="text" name="bloc"><br>

        <label for="scara">Scara:</label>
        <input type="text" name="scara"><br>

        <label for="etaj">Etaj:</label>
        <input type="text" name="etaj"><br>

        <label for="telefon">Telefon:</label>
        <input type="text" name="telefon" required><br>

        <label for="email">Email:</label>
        <input type="text" name="email" required><br>

        <!-- Alte câmpuri pentru datele turistului -->

        <!-- Meniu dropdown pentru excursii -->
        
        <input type="submit" value="Adauga Turist">
    </form>

    <h1>Interogare Baza de Date</h1>

    <!-- Formular pentru interogarea bazei de date -->
    <form action="interogare_date.php" method="post">
        <input type="submit" value="Interogare Baza de Date">
    </form>

</body>
</html>';

$conn->close();
?>
