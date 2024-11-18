<?php
include 'koneksi.php'; // Koneksi database
include 'playfair_enkripsi.php'; // Fungsi Playfair Cipher

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $key = "KEYWORD"; // Kunci untuk enkripsi

    $encryptedPassword = playfairEncrypt($password, $key);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$encryptedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil!";
        header('Location: login.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registrasi</title>
    
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
    <form method="POST" class="rounded-lg flex flex-col justify-center items-center w-5/6 sm:w-96 p-2 sm:p-5 ">
        <input type="text" name="username" placeholder="Username" required class="mb-3 p-2 w-full border rounded">
        <input type="password" name="password" placeholder="Password" required class="mb-4 p-2 w-full border rounded">
        <button type="submit" class="bg-blue-500 text-white rounded mb-4 p-1">Registrasi</button>
    </form>
</body>
</html>
