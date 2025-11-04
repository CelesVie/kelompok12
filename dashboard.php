<?php
// Halaman dashboard, kayak rumah utama setelah login.
// Halaman utama setelah login, tampilkan konten beda berdasarkan role user, kayak menu berbeda buat VIP dan biasa.
// Guru: Bisa atur semua siswa, kayak bos yang kontrol semua.
// Siswa: Cuma bisa lihat data sendiri, kayak baca rapor pribadi.

require_once 'config.php'; // Include config, kayak panggil backup.

 // Cek apakah user udah login, kalau belum ya redirect ke login.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { // Kondisi cek login.
    header('Location: login.php'); // Redirect ke login.
    exit; // Stop eksekusi.
}

$user_id = $_SESSION['user_id']; // Ambil user ID dari session, kayak nomor identitas.
$username = $_SESSION['username']; // Ambil username.
$role = $_SESSION['role']; // Ambil role.

// Ambil data siswa berdasarkan role, kayak pilih menu sesuai selera.
if ($role === 'student') { // Kalau role student.
    // Siswa cuma lihat data sendiri.
    $stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?"); // Prepare query select.
    $stmt->bind_param("i", $user_id); // Bind user ID.
    $stmt->execute(); // Eksekusi.
    $result = $stmt->get_result(); // Ambil result.
    $student_data = $result->fetch_assoc(); // Fetch data.
    $stmt->close(); // Tutup statement.
} else { // Kalau bukan student (teacher).
    // Guru lihat semua siswa.
    $result = $conn->query("SELECT * FROM students ORDER BY created_at DESC"); // Query semua siswa.
    $all_students = $result->fetch_all(MYSQLI_ASSOC); // Fetch semua.
}
?>
<!DOCTYPE html> <!-- DOCTYPE HTML. -->
<html lang="en"> <!-- Tag html. -->
<head> <!-- Head section. -->
    <meta charset="UTF-8"> <!-- Charset. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport. -->
    <title>Dashboard - School System</title> <!-- Title. -->
    <link rel="stylesheet" href="style.css"> <!-- Link CSS. -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> <!-- Link font. -->
</head>
<body> <!-- Body. -->
    <!-- Navigation Bar -->
    <nav class="navbar"> <!-- Tag nav. -->
        <div class="nav-container"> <!-- Container nav. -->
            <div class="nav-brand"> <!-- Brand. -->
                <span class="brand-icon">📚</span> <!-- Icon. -->
                <span class="brand-text">School System</span> <!-- Text. -->
            </div>
            <div class="nav-menu"> <!-- Menu. -->
                <span class="nav-user"> <!-- User info. -->
                    <span class="user-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></span> <!-- Avatar. -->
                    <span class="user-name"><?php echo htmlspecialchars($username); ?></span> <!-- Nama. -->
                    <span class="user-role"><?php echo ucfirst($role); ?></span> <!-- Role. -->
                </span>
                <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a> <!-- Link logout. -->
            </div>
        </div>
    </nav>

    <!-- Main Content --> 
    <div class="main-container"> <!-- Container utama. -->
        <div class="content-wrapper"> <!-- Wrapper. -->
            <div class="page-header"> <!-- Header halaman. -->
                <h1>Dashboard</h1> <!-- Judul. -->
                <p>Welcome back, <?php echo htmlspecialchars($username); ?>!</p> <!-- Salam. -->
            </div>

            <?php if ($role === 'teacher'): ?> <!-- Kondisi kalau teacher. -->
                <!-- View guru: Atur siswa. -->
                <div class="dashboard-section"> <!-- Section dashboard. -->
                    <div class="section-header"> <!-- Header section. -->
                        <h2>Student Management</h2> <!-- Judul section. -->
                        <a href="crud_siswa.php" class="btn btn-primary">Manage Students</a> <!-- Link manage. -->
                    </div>

                    <div class="stats-grid"> <!-- Grid statistik. -->
                        <div class="stat-card"> <!-- Card total students. -->
                            <div class="stat-icon">👥</div> <!-- Icon. -->
                            <div class="stat-content"> <!-- Konten. -->
                                <div class="stat-value"><?php echo count($all_students); ?></div> <!-- Value. -->
                                <div class="stat-label">Total Students</div> <!-- Label. -->
                            </div>
                        </div>
                        <div class="stat-card"> <!-- Card enrolled. -->
                            <div class="stat-icon">✅</div> <!-- Icon. -->
                            <div class="stat-content"> <!-- Konten. -->
                                <div class="stat-value"><?php echo count(array_filter($all_students, fn($s) => !empty($s['grade']))); ?></div> <!-- Value. -->
                                <div class="stat-label">Enrolled Students</div> <!-- Label. -->
                            </div>
                        </div>
                        <div class="stat-card"> <!-- Card grades. -->
                            <div class="stat-icon">📊</div> <!-- Icon. -->
                            <div class="stat-content"> <!-- Konten. -->
                                <div class="stat-value"><?php echo count(array_unique(array_column($all_students, 'grade'))); ?></div> <!-- Value. -->
                                <div class="stat-label">Total Grades</div> <!-- Label. -->
                            </div>
                        </div>
                    </div>

                    <?php if (count($all_students) > 0): ?> <!-- Kalau ada siswa. -->
                        <div class="table-container"> <!-- Container tabel. -->
                            <table class="data-table"> <!-- Tabel. -->
                                <thead> <!-- Header. -->
                                    <tr> <!-- Baris. -->
                                        <th>Student ID</th> <!-- Kolom. -->
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Grade</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody> <!-- Body. -->
                                    <?php foreach ($all_students as $student): ?> <!-- Loop siswa. -->
                                        <tr> <!-- Baris data. -->
                                            <td><span class="badge"><?php echo htmlspecialchars($student['student_id']); ?></span></td> <!-- TD ID. -->
                                            <td><?php echo htmlspecialchars($student['name']); ?></td> <!-- TD nama. -->
                                            <td><?php echo htmlspecialchars($student['email']); ?></td> <!-- TD email. -->
                                            <td><?php echo htmlspecialchars($student['grade'] ?? '-'); ?></td> <!-- TD grade. -->
                                            <td><?php echo htmlspecialchars($student['phone'] ?? '-'); ?></td> <!-- TD phone. -->
                                            <td> <!-- TD actions. -->
                                                <a href="crud_siswa.php?action=edit&id=<?php echo $student['id']; ?>" class="btn-icon" title="Edit">✏️</a> <!-- Link edit. -->
                                                <a href="crud_siswa.php?action=view&id=<?php echo $student['id']; ?>" class="btn-icon" title="View">👁️</a> <!-- Link view. -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?> <!-- Tutup loop. -->
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?> <!-- Kalau ga ada siswa. -->
                        <div class="empty-state"> <!-- Empty state. -->
                            <div class="empty-icon">📝</div> <!-- Icon. -->
                            <h3>No Students Yet</h3> <!-- Judul. -->
                            <p>Start by adding your first student to the system.</p> <!-- Deskripsi. -->
                            <a href="crud_siswa.php?action=create" class="btn btn-primary">Add Student</a> <!-- Link. -->
                        </div>
                    <?php endif; ?> <!-- Tutup kondisi. -->
                </div>

            <?php else: ?> <!-- Kondisi student. -->
                <!-- View siswa: Data pribadi. -->
                <div class="dashboard-section"> <!-- Section. -->
                    <div class="section-header"> <!-- Header. -->
                        <h2>My Information</h2> <!-- Judul. -->
                    </div>

                    <?php if ($student_data): ?> <!-- Kalau ada data siswa. -->
                        <div class="profile-card"> <!-- Card profil. -->
                            <div class="profile-header"> <!-- Header profil. -->
                                <div class="profile-avatar"><?php echo strtoupper(substr($student_data['name'], 0, 1)); ?></div> <!-- Avatar. -->
                                <div class="profile-info"> <!-- Info. -->
                                    <h3><?php echo htmlspecialchars($student_data['name']); ?></h3> <!-- Nama. -->
                                    <p class="profile-id">ID: <?php echo htmlspecialchars($student_data['student_id']); ?></p> <!-- ID. -->
                                </div>
                            </div>
                            <div class="profile-body"> <!-- Body. -->
                                <div class="info-row"> <!-- Row email. -->
                                    <span class="info-label">Email</span> <!-- Label. -->
                                    <span class="info-value"><?php echo htmlspecialchars($student_data['email']); ?></span> <!-- Value. -->
                                </div>
                                <div class="info-row"> <!-- Row grade. -->
                                    <span class="info-label">Grade</span>
                                    <span class="info-value"><?php echo htmlspecialchars($student_data['grade'] ?? 'Not assigned'); ?></span>
                                </div>
                                <div class="info-row"> <!-- Row phone. -->
                                    <span class="info-label">Phone</span>
                                    <span class="info-value"><?php echo htmlspecialchars($student_data['phone'] ?? 'Not provided'); ?></span>
                                </div>
                                <div class="info-row"> <!-- Row address. -->
                                    <span class="info-label">Address</span>
                                    <span class="info-value"><?php echo htmlspecialchars($student_data['address'] ?? 'Not provided'); ?></span>
                                </div>
                                <div class="info-row"> <!-- Row enrolled. -->
                                    <span class="info-label">Enrolled Since</span>
                                    <span class="info-value"><?php echo date('F j, Y', strtotime($student_data['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?> <!-- Kalau ga ada data. -->
                        <div class="empty-state"> <!-- Empty state. -->
                            <div class="empty-icon">👤</div> <!-- Icon. -->
                            <h3>Profile Not Found</h3> <!-- Judul. -->
                            <p>Your student profile has not been created yet. Please contact your teacher.</p> <!-- Deskripsi. -->
                        </div>
                    <?php endif; ?> <!-- Tutup kondisi. -->
                </div>
            <?php endif; ?> <!-- Tutup kondisi role. -->
        </div> <!-- Tutup wrapper. -->
    </div> <!-- Tutup container. -->

    <script src="script.js"></script> <!-- Script. -->
</body> <!-- Tutup body. -->
</html> <!-- Tutup html. -->
