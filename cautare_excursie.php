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
    <title>Cautare si Modificare Excursie</title>
</head>
<body>
    <h1>Cautare si Modificare Excursie</h1>
    
    <form method="post" action="">
        <input type="text" name="cautare" placeholder="Cauta dupa Plecare, Intoarcere, Pret, Nume Ghid">
        <input type="submit" value="Cauta">
    </form>

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
    function generateActions($id_excursie) {
        return '<a href="modifica_excursie.php?id_excursie=' . $id_excursie . '" style="color:#333;">Modifică</a> | <a href="sterge_excursie.php?id_excursie=' . $id_excursie . '" style="color:#333;">Șterge</a>';
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cautare = $_POST['cautare'];

    if (!empty($cautare)) {
        $sql = "SELECT e.id_excursie, e.data_plecare, e.data_intoarcere, e.pret, e.id_ghid,
                    CONCAT(g.nume, ' ', g.prenume) AS nume_ghid,
                    t.nr_locuri AS transport_locuri, t.pret AS transport_pret,
                    GROUP_CONCAT(d.destinatie SEPARATOR ', ') AS destinatii
                FROM excursii e
                LEFT JOIN ghid g ON e.id_ghid = g.id_ghid
                LEFT JOIN transport t ON e.id_transport = t.id_transport
                LEFT JOIN destinatii_excursie de ON e.id_excursie = de.id_excursie
                LEFT JOIN destinatii d ON de.id_destinatie = d.id_destinatie
                WHERE e.pret LIKE '%$cautare%'
                OR e.data_plecare LIKE '%$cautare%'
                OR e.data_intoarcere LIKE '%$cautare%'
                OR g.nume LIKE '%$cautare%'
                OR t.pret LIKE '%$cautare%'
                OR g.prenume LIKE '%$cautare%'
                GROUP BY e.id_excursie, e.data_plecare, e.data_intoarcere, e.pret, e.id_ghid, t.nr_locuri, t.pret";  

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Afiseaza rezultatele sub forma de tabel
            echo '<table>';
            echo '<tr><th>Data Plecare</th><th>Data Intoarcere</th><th>Pret</th><th>Ghid</th><th>Locuri Transport</th><th>Pret Transport</th><th>Destinații</th><th>Acțiuni</th></tr>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['data_plecare'] . '</td>';
                echo '<td>' . $row['data_intoarcere'] . '</td>';
                echo '<td>' . $row['pret'] . '</td>';
                echo '<td>' . $row['nume_ghid'] . '</td>';
                echo '<td>' . $row['transport_locuri'] . '</td>';
                echo '<td>' . $row['transport_pret'] . '</td>';
                
                // Formateaza destinatiile intr-o lista neordonata
                echo '<td><ul>';
                $destinatii = explode(', ', $row['destinatii']);
                foreach ($destinatii as $destinatie) {
                    echo '<li>' . $destinatie . '</li>';
                }
                echo '</ul></td>';
                
                echo '<td>' . generateActions($row['id_excursie']) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<script>alert("Nicio excursie găsită cu datele furnizate.");</script>';
        }
    } else {
        echo '<script>alert("Introduceți date pentru a căuta excursii.");</script>';
    }
    $conn->close(); // închide conexiunea
    
}
?>

    <a href="cautare_date.html" class="back">Revenire la Cautare Date</a>

</body>
</html>
