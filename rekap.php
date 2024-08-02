<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data rekap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: none; /* Menghilangkan garis pada sel tabel */
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #e3e3e3;
        }

        .rekap-container {
            margin-top: 20px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ddd;
            border: 1px solid #999;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            margin: 0 auto;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #ccc;
        }

        .online {
            color: green;
            font-weight: bold;
        }

        .offline {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Data Rekap Rumah Warga</h1>

    <?php
    require_once('koneksi.php');

    // Handling POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomorRumah = mysqli_real_escape_string($koneksi, $_POST["nomor_rumah"]);

        // Validation
        if (!empty($nomorRumah)) {
            $sqlInsert = "INSERT INTO logrum (log) VALUES ('$nomorRumah')";
            $koneksi->query($sqlInsert);
        }
    }

    // Function to determine user status
    function getStatus($timestamp)
    {
        $currentTime = time();
        $logTime = strtotime($timestamp);

        return ($currentTime - $logTime < 10) ? 'Online' : 'Offline';
    }

    // Displaying data recap
    $sqlRekapData = "SELECT * FROM logrum ORDER BY waktu DESC";
    $resultRekapData = $koneksi->query($sqlRekapData);

    if ($resultRekapData && $resultRekapData->num_rows > 0) {
        echo "<table>";
        echo "<tr>
            <th>Waktu</th>
            <th>Nomor Rumah</th>
            <th>Status Login</th>
            </tr>";

        while ($rowLatestData = $resultRekapData->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowLatestData["waktu"] . "</td>";
            echo "<td>" . $rowLatestData["log"] . "</td>";

            $status = getStatus($rowLatestData["waktu"]);
            $statusClass = strtolower($status);

            echo "<td class='$statusClass'>$status</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Tidak ada data log.";
    }

    $koneksi->close();
    ?>

    <div class="button-container">
        <a href="monitor.php" class="back-button">Kembali ke Monitor</a>
    </div>
</body>

<script>
      setInterval(function() {
        location.reload();
    }, 1000);
</script>

</html>
