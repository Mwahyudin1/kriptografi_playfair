<?php
include 'koneksi.php'; // Koneksi database
include 'playfair_enkripsi.php'; // Fungsi Playfair Cipher

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $key = "KEYWORD";

    // Ambil password terenkripsi dari database
    $sql = "SELECT password FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $encryptedPasswordFromDb = $row['password'];

        // Enkripsi password input dan bandingkan
        $encryptedPasswordInput = playfairEncrypt($password, $key);

        if ($encryptedPasswordInput == $encryptedPasswordFromDb) {
            echo "Login berhasil!";
            header("Location: home.php"); // Redirect ke halaman home
        } else {
            echo "Username atau password salah!";
        }
    } else {
        echo "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Login</title>
        
        <style>
            body {
                background-image: url('images/bg-login.png');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            form {
                background: rgb(138, 135, 188, 27%);
            }
        </style>
    </head>
    <body class="flex items-center justify-center h-screen">
        <form class="rounded-lg flex flex-col justify-center items-center w-5/6 sm:w-96 p-2 sm:p-5 " method="POST">
            <input type="text" name="username" placeholder="Username" required class="mb-3 p-2 w-full border rounded">
            <input type="password" name="password" placeholder="Password" required class="mb-4 p-2 w-full border rounded">
            <button type="submit" class="bg-blue-500 text-white rounded mb-4 p-1">Login</button>
            <p class="text-gray-100 sm:text-lg">Klik <a href="register.php" class="text-blue-500 underline">regis</a> untuk membuat akun.</p>
        </form>
    </body>
</html>
