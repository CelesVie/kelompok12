<?php
// Halaman login user
// Urus autentikasi user dan bikin session, kayak cek ID dan kasih akses.

require_once 'config.php'; // Include config, kayak panggil config buat koneksi.

$error = ''; // Variabel error, kayak kotak komplain.

// Redirect kalau udah login, kayak bilang "udah masuk kok".
if (isset($_SESSION['user_id'])) { // Cek apakah session user_id ada.
    header('Location: dashboard.php'); // Redirect ke dashboard.
    exit; // Stop.
}

// Urus submit form, kayak proses check-in.
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kalau method POST.
    $username = trim($_POST['username'] ?? ''); // Ambil username, trim spasi.
    $password = $_POST['password'] ?? ''; // Ambil password.

    // Validasi input, kayak cek tiket.
    if (empty($username) || empty($password)) { // Kalau kosong.
        $error = 'Please enter both username and password.'; // Pesan error.
    } else { // Kalau ada.
        // Query cari user, kayak cari nama di buku tamu.
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?"); // Prepare select.
        $stmt->bind_param("ss", $username, $username); // Bind username dua kali (username atau email).
        $stmt->execute(); // Eksekusi.
        $result = $stmt->get_result(); // Ambil result.

        if ($result->num_rows === 1) { // Kalau ada satu user.
            $user = $result->fetch_assoc(); // Fetch data user.

            // Verifikasi password
            if (password_verify($password, $user['password'])) { // Kalau password bener.
                // Set session variables // Set variabel session, kayak isi kartu akses.
                $_SESSION['user_id'] = $user['id']; // Set user ID.
                $_SESSION['username'] = $user['username']; // Set username.
                $_SESSION['role'] = $user['role']; // Set role.
                $_SESSION['logged_in'] = true; // Set logged in.

                // Redirect ke dashboard
                header('Location: dashboard.php'); // Redirect.
                exit; // Stop.
            } else { // Kalau password salah.
                $error = 'Invalid username or password.'; // Pesan error.
            }
        } else { // Kalau ga ada user.
            $error = 'Invalid username or password.'; // Pesan error.
        }
        $stmt->close(); // Tutup statement.
    }
}
?>
<!DOCTYPE html> <!-- DOCTYPE. -->
<html lang="en"> <!-- Html. -->
<head> <!-- Head. -->
    <meta charset="UTF-8"> <!-- Charset. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport. -->
    <title>Login - School System</title> <!-- Title. -->
    <link rel="stylesheet" href="style.css"> <!-- CSS. -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> <!-- Font. -->
</head>
<body> <!-- Body. -->
    <div class="auth-container"> <!-- Container auth. -->
        <div class="auth-card"> <!-- Card auth. -->
            <div class="auth-header"> <!-- Header auth. -->
                <h1>Welcome Back</h1> <!-- Judul. -->
                <p>Sign in to continue to your account</p> <!-- Deskripsi. -->
            </div>

            <?php if ($error): ?> <!-- Kalau ada error. -->
                <div class="alert alert-error" id="alert"> <!-- Alert error. -->
                    <?php echo htmlspecialchars($error); ?> <!-- Tampil error. -->
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form" id="loginForm"> <!-- Form login. -->
                <div class="form-group"> <!-- Group username. -->
                    <label for="username">Username or Email</label> <!-- Label. -->
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username or email"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required
                        autofocus
                    > <!-- Input username. -->
                </div>

                <div class="form-group"> <!-- Group password. -->
                    <label for="password">Password</label> <!-- Label. -->
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    > <!-- Input password. -->
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button> <!-- Button submit. -->
            </form>

            <div class="auth-footer"> <!-- Footer auth. -->
                <p>Don't have an account? <a href="register.php">Create one</a></p> <!-- Link register. -->
            </div>
        </div>
    </div>

    <script src="script.js"></script> <!-- Script. -->
</body> 
</html> 
