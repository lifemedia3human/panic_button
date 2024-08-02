<?php
// Sertakan file koneksi.php
include('koneksi.php');

// Inisialisasi pesan kesalahan
$error = "";

// Cek apakah cookie "User" sudah ada
if(isset($_COOKIE["User"])) {
    // Cookie ada, arahkan ke halaman panic button
    header("Location: esp_iot/index.php");
    exit(); // Pastikan untuk keluar setelah pengalihan header
}

// Menangani permintaan POST jika ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form
    $nomorRumah = mysqli_real_escape_string($koneksi, $_POST["nomor_rumah"]);
    $sandi = mysqli_real_escape_string($koneksi, $_POST["sandi"]);
    $rememberMe = isset($_POST["remember"]); // Periksa apakah checkbox "Remember Me" dicentang

    // Melakukan validasi (contoh: pastikan kedua input tidak kosong)
    if (empty($nomorRumah) || empty($sandi)) {
        $error = "Nomor rumah dan sandi harus diisi";

    } else {
        // Query untuk memeriksa kecocokan nomor rumah dan sandi di tabel user
        $query = "SELECT * FROM user WHERE norum = '$nomorRumah' AND sandi = '$sandi'";
        $result = $koneksi->query($query);

        if ($result->num_rows > 0) {
            // Login berhasil
        // Tambahkan data login ke dalam tabel logrum
        $logData = "User dengan nomor rumah $nomorRumah berhasil login";
        $queryInsert = "INSERT INTO logrum (log) VALUES ('$nomorRumah')";
        $koneksi->query($queryInsert);
        // Jika checkbox "Remember Me" dicentang, setel cookie
        if ($rememberMe) {
            $cookieName = "User";
            setcookie($cookieName, $nomorRumah, time() + (60), "/"); // Cookie berlaku selama 30 hari
        }
            // Arahkan ke halaman index.php
            header("Location: esp_iot/index.php");
            exit(); // Pastikan untuk keluar setelah pengalihan header
        } else {
            // Login gagal
            $error = "Nomor rumah atau sandi salah, ulangi lagi";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <!-- Tambahkan tautan ke file CSS Anda di sini -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('whitehouse.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .container {
            text-align: center;
            background-color: transparent;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0);
            max-width: 400px;
            width: 100%;
        }

        .container h2 {
            color: #000000;
            font-size: 40px;
            margin-bottom: 30px;
        }

        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #000000;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 10px;
        }

        .remember-me label {
            font-size: 14px;
            color: #000000;
        }

        .container input[type="text"],
        .container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #000000;
            border-radius: 50px;
            
        }

        .container input[type="text"]:focus,
        .container input[type="password"]:focus {
            outline: none;
            border-color: #000000;
        }

        .container button[type="submit"] {
            padding: 15px 30px;
            font-size: 16px;
            background-color: #ffffff;
            color: #000000;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container button[type="submit"]:hover {
            background-color: #bebdb8;
        }

        .container .error {
            color: #d9dddc;
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Masuk</h2>
        <!-- Formulir Login -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <!-- Input Nomor Rumah -->
            <input type="text" name="nomor_rumah" placeholder="Nomor Rumah">
            
            <!-- Input Sandi -->
            <input type="password" name="sandi" placeholder="Sandi">
            <!-- Remember Me -->
            <div class="remember-me">
                <input type="checkbox" id="remember-me" name="remember">
                <label for="remember-me">Remember Me</label>
            </div>
            <!-- Tombol Masuk -->
            <button type="submit">Masuk</button>
        </form>
        <!-- Pesan Kesalahan -->
        <div class="error"><?php echo $error; ?></div>   
    </div>
</body>
</html>

