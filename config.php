<?php

// Judul kecil, konstanta-konstanta buat database, biar nilai-nilai ini tetep konstan, ga ganti-ganti seenaknya kayak mood cewek.
define('DB_HOST', 'localhost'); // Define nih, buat nyimpen konstanta DB_HOST, localhost artinya server lokal, kayak rumah sendiri biar ga perlu keluar rumah buat konek.
define('DB_USER', 'root'); // Define lagi, DB_USER username database, root kayak bos besar yang punya akses penuh, tapi hati-hati ya, jangan sampe dihack orang iseng!
define('DB_PASS', ''); // Define password, DB_PASS kosong nih, kayak pintu terbuka lebar, mudah masuk tapi rawan maling data masuk seenaknya.
define('DB_NAME', 'school_system'); // DB_NAME nama database-nya, school_system kayak sistem sekolah yang rapi, biar data siswa ga kacau balau kayak kamar bocah ABG.

// membuat koneksi ke database
try { // Try block, kayak coba dulu deh, kalau berhasil oke banget, kalau error ada catch-nya biar ga panik dan nangis.
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // Bikin objek koneksi mysqli baru, pake parameter dari define tadi, kayak nyambungin kabel USB ke laptop biar data bisa flow.
    // cek koneksi // Komentar lagi, ini buat ngecek koneksi, kayak tes apakah lampu nyala atau mati setelah colok-in.
    if ($conn->connect_error) { // If statement, kalau ada error koneksi, lempar exception biar ditangani, kayak kalau motor ga mau nyala, ya jangan dipaksain ngegas terus.
        throw new Exception("Connection failed: " . $conn->connect_error); // Throw exception, kayak melempar bola ke penjaga gawang, biar error-nya ditangkap dan dijelasin dengan baik.
    }
    // Set charset to utf8mb4 for proper character support
    $conn->set_charset("utf8mb4");
} catch (Exception $e) { // Catch block, kalau ada exception dari try, tangkap sini, kayak penjaga gawang yang sigap nangkep bola biar ga kebobolan.
    die("Database connection error: " . $e->getMessage()); // Die, kayak mati total, stop eksekusi dan tampilin pesan error, biar user tau ada masalah apa dan ga bingung sendiri.
}

/**
 * Start session if not already started // Komentar blok lagi, tentang session, kayak nyiapin tas buat jalan-jalan jauh, biar data ga hilang selama aplikasi lagi jalan.
 * This ensures session is available throughout the application // Pastiin session available sepanjang aplikasi, kayak bensin yang cukup buat perjalanan panjang.
 */
if (session_status() === PHP_SESSION_NONE) { // If, cek status session, kalau belum ada (PHP_SESSION_NONE), baru mulai, kayak cek apakah pintu udah dikunci atau belum sebelum tidur.
    session_start(); // Panggil session_start, kayak tekan tombol start di mesin mobil, biar session aktif dan bisa nyimpen data user kayak simpen kenangan.
}
?> // Tutup PHP, kayak matiin mesin setelah selesai kerja, biar ga boros listrik dan bisa istirahat.
