<?php
// Fungsi untuk mengganti angka dengan huruf yang sesuai sebelum enkripsi
function replaceDigits($input) {
    $mapping = [
        '0' => 'X', '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D',
        '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I'
    ];

    $output = '';
    for ($i = 0; $i < strlen($input); $i++) {
        $char = $input[$i];
        if (ctype_digit($char)) { // Jika karakter adalah angka
            $output .= $mapping[$char];
        } else {
            $output .= $char;
        }
    }
    return $output;
}

// Fungsi untuk membuat matriks Playfair dari kunci yang diberikan
function generatePlayfairMatrix($key) {
    $key = strtoupper($key);
    $matrix = [];
    $alphabet = "ABCDEFGHIKLMNOPQRSTUVWXYZ"; // Tidak termasuk 'J'
    $key = str_replace('J', 'I', $key); // Ganti 'J' dengan 'I'

    $key = implode(array_unique(str_split($key))); // Hapus karakter berulang dalam kunci
    $alphabet = str_replace(str_split($key), '', $alphabet); // Hapus karakter kunci dari alfabet
    $key .= $alphabet;

    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            $matrix[$i][$j] = $key[$i * 5 + $j];
        }
    }

    return $matrix;
}

// Fungsi untuk menemukan posisi karakter dalam matriks
function findPosition($matrix, $char) {
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            if ($matrix[$i][$j] == $char) {
                return [$i, $j];
            }
        }
    }
    return null; // Kembalikan null jika karakter tidak ditemukan
}

// Fungsi untuk mengenkripsi menggunakan Playfair Cipher
function playfairEncrypt($plaintext, $key) {
    $matrix = generatePlayfairMatrix($key);
    $ciphertext = '';

    // Ubah angka menjadi huruf sebelum enkripsi
    $plaintext = replaceDigits($plaintext);

    $plaintext = strtoupper($plaintext);
    $plaintext = str_replace('J', 'I', $plaintext);

    // Menangani pasangan huruf
    $pairs = [];
    for ($i = 0; $i < strlen($plaintext); $i += 2) {
        $first = $plaintext[$i];
        $second = ($i + 1 < strlen($plaintext)) ? $plaintext[$i + 1] : 'X';

        // Jika kedua huruf sama, tambahkan 'X' sebagai pengganti kedua
        if ($first == $second) {
            $second = 'X';
            $i--;
        }

        $pairs[] = $first . $second;
    }

    foreach ($pairs as $pair) {
        $first = $pair[0];
        $second = $pair[1];
        $firstPos = findPosition($matrix, $first);
        $secondPos = findPosition($matrix, $second);

        // Periksa apakah posisi ditemukan untuk keduanya
        if ($firstPos && $secondPos) {
            if ($firstPos[0] == $secondPos[0]) {
                // Jika dalam baris yang sama
                $ciphertext .= $matrix[$firstPos[0]][($firstPos[1] + 1) % 5];
                $ciphertext .= $matrix[$secondPos[0]][($secondPos[1] + 1) % 5];
            } elseif ($firstPos[1] == $secondPos[1]) {
                // Jika dalam kolom yang sama
                $ciphertext .= $matrix[($firstPos[0] + 1) % 5][$firstPos[1]];
                $ciphertext .= $matrix[($secondPos[0] + 1) % 5][$secondPos[1]];
            } else {
                // Jika berada di baris dan kolom berbeda
                $ciphertext .= $matrix[$firstPos[0]][$secondPos[1]];
                $ciphertext .= $matrix[$secondPos[0]][$firstPos[1]];
            }
        } else {
            // Menangani kasus di mana posisi tidak ditemukan
            echo "Warning: karakter '$first' atau '$second' tidak valid dan diabaikan dalam enkripsi.<br>";
        }
    }

    return $ciphertext;
}
?>
