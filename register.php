<?php
// Halaman registrasi user, kayak form daftar anggota baru.
// Urus registrasi user baru dengan pilih role


require_once 'config.php'; // Include config.

$error = ''; // Variabel error.
$success = ''; // Variabel success.

// Urus submit form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kalau POST.
    $username = trim($_POST['username'] ?? ''); // Ambil username.
    $email = trim($_POST['email'] ?? ''); // Ambil email.
    $password = $_POST['password'] ?? ''; // Ambil password.
    $confirm_password = $_POST['confirm_password'] ?? ''; // Ambil confirm password.
    $role = $_POST['role'] ?? ''; // Ambil role.

    // Validasi.
    if (empty($username) || empty($email) || empty($password) || empty($role)) { // Kalau ada kosong.
        $error = 'All fields are required.'; // Error.
    } elseif ($password !== $confirm_password) { // Kalau password ga sama.
        $error = 'Passwords do not match.'; // Error.
    } elseif (strlen($password) < 6) { // Kalau password kurang dari 6.
        $error = 'Password must be at least 6 characters long.'; // Error.
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Kalau email ga valid.
        $error = 'Invalid email format.'; // Error.
    } elseif (!in_array($role, ['teacher', 'student'])) { // Kalau role ga valid.
        $error = 'Invalid role selected.'; // Error.
    } else { // Kalau valid.
        // cek username atau email udah ada atau belum.
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?"); // Prepare cek.
        $stmt->bind_param("ss", $username, $email); // Bind.
        $stmt->execute(); // Eksekusi.
        $result = $stmt->get_result(); // Result.

        if ($result->num_rows > 0) { // Kalau ada duplikat.
            $error = 'Username or email already exists.'; // Error.
        } else { // Kalau ga ada.
            // Hash password dan insert.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash.
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)"); // Prepare insert.
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role); // Bind.

            if ($stmt->execute()) { // Kalau berhasil.
                if ($role === 'student') { // Kalau student.
                    $user_id = $conn->insert_id; // Ambil user ID baru.
                    $stmt2 = $conn->prepare("INSERT INTO students (student_id, user_id, name, email) VALUES (?, ?, ?, ?)");
                    $stmt2->bind_param("iiss", $user_id, $user_id, $username, $email); // Bind.
                    $stmt2->execute(); // Eksekusi.
                    $stmt2->close(); // Tutup.
                }
                $success = 'Registration successful! You can now log in.'; // Success.
            } else { // Kalau gagal.
                $error = 'Registration failed. Please try again.'; // Error.
            }
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
    <title>Register - School System</title> <!-- Title. -->
    <link rel="stylesheet" href="style.css"> <!-- CSS. -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> <!-- Font. -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script SweetAlert. -->
</head>

<body> <!-- Body. -->
    <div class="auth-container"> <!-- Container. -->
        <div class="auth-card"> <!-- Card. -->
            <div class="auth-header"> <!-- Header. -->
                <h1>Create Account</h1> <!-- Judul. -->
                <p>Join our school management system</p> <!-- Deskripsi. -->
            </div>

            <?php if ($error): ?> <!-- Kalau error. -->
                <div class="alert alert-error" id="alert"> <!-- Alert. -->
                    <?php echo htmlspecialchars($error); ?> <!-- Tampil error. -->
                </div>
            <?php endif; ?>

            <?php if ($success): ?> <!-- Kalau success. -->
                <div class="alert alert-success" id="alert"> <!-- Alert. -->
                    <?php echo htmlspecialchars($success); ?> <!-- Tampil success. -->
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form" id="registerForm"> <!-- Form. -->
                <div class="form-group"> <!-- Group username. -->
                    <label for="username">Username</label> <!-- Label. -->
                    <input type="text" id="username" name="username" placeholder="Enter your username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required> <!-- Input. -->
                </div>

                <div class="form-group"> <!-- Group email. -->
                    <label for="email">Email</label> <!-- Label. -->
                    <input type="email" id="email" name="email" placeholder="your.email@school.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required> <!-- Input. -->
                </div>

                <div class="form-group"> <!-- Group password. -->
                    <label for="password">Password</label> <!-- Label. -->
                    <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required> <!-- Input. -->
                </div>

                <div class="form-group"> <!-- Group confirm password. -->
                    <label for="confirm_password">Confirm Password</label> <!-- Label. -->
                    <input type="password" id="confirm_password" name="confirm_password"
                        placeholder="Re-enter your password" required> <!-- Input. -->
                </div>

                <div class="form-group"> <!-- Group role. -->
                    <label for="role">Register as</label> <!-- Label. -->
                    <div class="role-selector"> <!-- Selector role. -->
                        <label class="role-option"> <!-- Option teacher. -->
                            <input type="radio" name="role" value="teacher" <?php echo (($_POST['role'] ?? '') === 'teacher') ? 'checked' : ''; ?> required> <!-- Radio. -->
                            <span class="role-card"> <!-- Card. -->
                                <span class="role-icon">👨‍🏫</span> <!-- Icon. -->
                                <span class="role-title">Teacher</span> <!-- Title. -->
                                <span class="role-desc">Manage student data</span> <!-- Desc. -->
                            </span>
                        </label>
                        <label class="role-option"> <!-- Option student. -->
                            <input type="radio" name="role" value="student" <?php echo (($_POST['role'] ?? '') === 'student') ? 'checked' : ''; ?> required> <!-- Radio. -->
                            <span class="role-card"> <!-- Card. -->
                                <span class="role-icon">👨‍🎓</span> <!-- Icon. -->
                                <span class="role-title">Student</span> <!-- Title. -->
                                <span class="role-desc">View your data</span> <!-- Desc. -->
                            </span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Account</button> <!-- Button. -->
            </form>

            <div class="auth-footer"> <!-- Footer. -->
                <p>Already have an account? <a href="login.php">Sign in</a></p> <!-- Link login. -->
            </div>
        </div>
    </div>

    <script src="script.js"></script> <!-- Script JS. -->
    <?php if ($error): ?> <!-- Kalau error, tampil SweetAlert. -->
        <script>
            Swal.fire({ // SweetAlert error.
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo addslashes($error); ?>',
                confirmButtonColor: '#3085d6'
            });
        </script>
    <?php endif; ?>

    <?php if ($success): ?> <!-- Kalau success, tampil SweetAlert dan redirect. -->
        <script>
            Swal.fire({ // SweetAlert success.
                icon: 'success',
                title: 'Success!',
                text: '<?php echo addslashes($success); ?>',
                confirmButtonColor: '#3085d6'
            }).then(() => { // Then redirect.
                window.location = 'login.php'; // Redirect ke login.
            });
        </script>
    <?php endif; ?>
</body> <!-- Tutup body. -->

</html> <!-- Tutup html. -->