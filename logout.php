<?php
// Handler logout, kayak petugas yang antar keluar.
// Hancurkan session dan redirect ke login, kayak reset ulang.

session_start(); // Mulai session, kaya nyalain mesin dulu.

// Hapus semua variabel session
$_SESSION = array(); // Set session jadi array kosong.

// Hancurkan cookie session, kayak buang kunci lama.
if (isset($_COOKIE[session_name()])) { // Kalau ada cookie session.
    setcookie(session_name(), '', time() - 3600, '/'); // Set cookie expired.
}

// Hancurkan session, kayak matiin mesin.
session_destroy(); // Destroy.

// Redirect ke login, kayak balik ke awal.
header('Location: login.php'); // Redirect.
exit; // Stop.
?>
