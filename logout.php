<?php
/**
 * Logout Handler // Handler logout, kayak petugas yang antar keluar.
 *
 * Destroys user session and redirects to login page // Hancurkan session dan redirect ke login, kayak reset ulang.
 */

session_start(); // Mulai session, kayak nyalain mesin dulu.

 // Unset all session variables // Hapus semua variabel session, kayak bersihin meja.
$_SESSION = array(); // Set session jadi array kosong.

// Destroy the session cookie // Hancurkan cookie session, kayak buang kunci lama.
if (isset($_COOKIE[session_name()])) { // Kalau ada cookie session.
    setcookie(session_name(), '', time() - 3600, '/'); // Set cookie expired.
}

// Destroy the session // Hancurkan session, kayak matiin mesin.
session_destroy(); // Destroy.

// Redirect to login page // Redirect ke login, kayak balik ke awal.
header('Location: login.php'); // Redirect.
exit; // Stop.
?>
