<?php
/**
 * CRUD Student Management Page (Teacher Only) // Halaman CRUD buat siswa, cuma guru yang bisa akses, kayak VIP room di club.
 *
 * Handles Create, Read, Update, Delete operations for student data // Urus operasi CRUD buat data siswa, kayak bos yang atur semua pegawai.
 */

require_once 'config.php'; // Include config, kayak panggil temen buat bantu kerjaan.

 // Cek apakah user udah login dan role-nya teacher, kalau enggak ya redirect, kayak security di mall yang ngecek tiket.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { // Kalau belum login atau session ga valid, lempar ke login.
    header('Location: login.php'); // Redirect ke login, kayak pulang ke rumah kalau lupa bawa kunci.
    exit; // Stop eksekusi, biar ga lanjut ke bawah.
}

if ($_SESSION['role'] !== 'teacher') { // Kalau bukan teacher, redirect ke dashboard.
    header('Location: dashboard.php'); // Balik ke dashboard, kayak balik ke basecamp kalau salah jalan.
    exit; // Stop lagi, kayak rem darurat.
}

$action = $_GET['action'] ?? 'list'; // Ambil action dari URL, default 'list', kayak pilih menu di restoran.
$student_id = $_GET['id'] ?? null; // Ambil ID siswa dari URL, kalau ada, kayak cari nomor meja.
$error = ''; // Variabel buat error message, kayak kotak saran buat komplain.
$success = ''; // Variabel buat success message, kayak badge penghargaan.

// Handle DELETE action // Urus aksi delete, kayak hapus file yang ga penting.
if ($action === 'delete' && $student_id) { // Kalau action delete dan ada ID siswa.
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?"); // Prepare statement buat delete, kayak siapin pisau buat potong.
    $stmt->bind_param("i", $student_id); // Bind parameter ID, kayak isi bahan ke pisau.
    if ($stmt->execute()) { // Eksekusi query, kalau berhasil.
        $success = 'Student deleted successfully.'; // Pesan sukses, kayak "Yeay, berhasil!".
        $action = 'list'; // Balik ke list, kayak refresh halaman.
    } else { // Kalau gagal.
        $error = 'Failed to delete student.'; // Pesan error, kayak "Oops, ada yang salah".
    }
    $stmt->close(); // Tutup statement, kayak tutup pintu setelah keluar.
}

// Handle CREATE/UPDATE form submission // Urus submit form buat create atau update, kayak proses order di resto.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'edit'])) { // Kalau method POST dan action create/edit.
    $name = trim($_POST['name'] ?? ''); // Ambil nama, trim spasi, kayak bersihin debu.
    $student_id_number = trim($_POST['student_id'] ?? ''); // Ambil student ID, trim juga.
    $email = trim($_POST['email'] ?? ''); // Ambil email, trim lagi.
    $grade = trim($_POST['grade'] ?? ''); // Ambil grade.
    $phone = trim($_POST['phone'] ?? ''); // Ambil phone.
    $address = trim($_POST['address'] ?? ''); // Ambil address.

    // Validation // Validasi input, kayak cek apakah bahan masak fresh.
    if (empty($name) || empty($student_id_number) || empty($email)) { // Kalau ada yang kosong.
        $error = 'Name, Student ID, and Email are required fields.'; // Pesan error, kayak "Wajib diisi dong!".
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Kalau email ga valid.
        $error = 'Invalid email format.'; // Pesan error email.
    } else { // Kalau validasi lolos.
        if ($action === 'create') { // Kalau create.
            // Check if student_id already exists // Cek apakah student ID udah ada, kayak cek nomor KTP.
            $stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ?"); // Query cek duplikat.
            $stmt->bind_param("s", $student_id_number); // Bind parameter.
            $stmt->execute(); // Eksekusi.
            $result = $stmt->get_result(); // Ambil result.

            if ($result->num_rows > 0) { // Kalau ada duplikat.
                $error = 'Student ID already exists.'; // Pesan error duplikat.
            } else { // Kalau ga ada.
                // Insert new student // Insert siswa baru, kayak daftar anggota baru.
                $stmt = $conn->prepare("INSERT INTO students (name, student_id, email, grade, phone, address) VALUES (?, ?, ?, ?, ?, ?)"); // Prepare insert.
                $stmt->bind_param("ssssss", $name, $student_id_number, $email, $grade, $phone, $address); // Bind semua parameter.

                if ($stmt->execute()) { // Kalau berhasil insert.
                    $success = 'Student added successfully.'; // Pesan sukses.
                    $action = 'list'; // Balik ke list.
                } else { // Kalau gagal.
                    $error = 'Failed to add student.'; // Pesan error.
                }
            }
            $stmt->close(); // Tutup statement.
        } else { // Kalau edit.
            // Update existing student // Update siswa yang ada, kayak edit profil.
            $edit_id = $_POST['edit_id'] ?? null; // Ambil edit ID dari POST.

            if ($edit_id) { // Kalau ada edit ID.
                $stmt = $conn->prepare("UPDATE students SET name = ?, student_id = ?, email = ?, grade = ?, phone = ?, address = ? WHERE id = ?"); // Prepare update.
                $stmt->bind_param("ssssssi", $name, $student_id_number, $email, $grade, $phone, $address, $edit_id); // Bind parameter.

                if ($stmt->execute()) { // Kalau berhasil update.
                    $success = 'Student updated successfully.'; // Pesan sukses.
                    $action = 'list'; // Balik ke list.
                } else { // Kalau gagal.
                    $error = 'Failed to update student.'; // Pesan error.
                }
                $stmt->close(); // Tutup statement.
            }
        }
    }
}

// Get student data for edit/view // Ambil data siswa buat edit atau view, kayak ambil file dari arsip.
$student_data = null; // Inisialisasi variabel.
if (in_array($action, ['edit', 'view']) && $student_id) { // Kalau action edit/view dan ada ID.
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?"); // Prepare select.
    $stmt->bind_param("i", $student_id); // Bind ID.
    $stmt->execute(); // Eksekusi.
    $result = $stmt->get_result(); // Ambil result.
    $student_data = $result->fetch_assoc(); // Fetch data sebagai array.
    $stmt->close(); // Tutup statement.

    if (!$student_data) { // Kalau ga ada data.
        $error = 'Student not found.'; // Pesan error.
        $action = 'list'; // Balik ke list.
    }
}

// Get all students for list view // Ambil semua siswa buat list, kayak daftar nama di kelas.
if ($action === 'list') { // Kalau action list.
    $result = $conn->query("SELECT * FROM students ORDER BY created_at DESC"); // Query semua siswa, urut berdasarkan created_at.
    $all_students = $result->fetch_all(MYSQLI_ASSOC); // Fetch semua sebagai array.
}
?>
<!DOCTYPE html> <!-- DOCTYPE HTML, kayak kartu identitas halaman web. -->
<html lang="en"> <!-- Tag html dengan lang en, kayak bahasa utama Inggris. -->
<head> <!-- Head section, kayak kepala halaman, isi meta dan title. -->
    <meta charset="UTF-8"> <!-- Charset UTF-8, biar karakter ga aneh-aneh. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport buat responsive, kayak atur zoom di peta. -->
    <title>Manage Students - School System</title> <!-- Title halaman, kayak nama restoran. -->
    <link rel="stylesheet" href="style.css"> <!-- Link CSS, kayak pakaian buat halaman. -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> <!-- Link font Google, kayak pilih font keren. -->
</head>
<body> <!-- Body halaman, kayak badan manusia. -->
    <!-- Navigation Bar --> <!-- Navbar, kayak menu utama di resto. -->
    <nav class="navbar"> <!-- Tag nav, kayak navigasi kapal. -->
        <div class="nav-container"> <!-- Container nav, kayak kotak buat barang. -->
            <div class="nav-brand"> <!-- Brand nav, kayak logo merek. -->
                <span class="brand-icon">📚</span> <!-- Icon brand, emoji buku. -->
                <span class="brand-text">School System</span> <!-- Text brand, nama sistem. -->
            </div>
            <div class="nav-menu"> <!-- Menu nav, kayak daftar menu. -->
                <a href="dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a> <!-- Link ke dashboard, kayak pintu keluar. -->
                <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a> <!-- Link logout, kayak tombol off. -->
            </div>
        </div>
    </nav>

    <!-- Main Content --> <!-- Konten utama, kayak hidangan utama. -->
    <div class="main-container"> <!-- Container utama, kayak wadah besar. -->
        <div class="content-wrapper"> <!-- Wrapper konten, kayak bungkus kado. -->
            <?php if ($action === 'list'): ?> <!-- Kondisi kalau action list, kayak pilih menu list. -->
                <!-- List View --> <!-- View list, kayak daftar belanja. -->
                <div class="page-header"> <!-- Header halaman, kayak judul artikel. -->
                    <div> <!-- Div wrapper. -->
                        <h1>Student Management</h1> <!-- Judul h1, kayak headline berita. -->
                        <p>Manage all student records in the system</p> <!-- Paragraf deskripsi. -->
                    </div>
                    <a href="?action=create" class="btn btn-primary">Add New Student</a> <!-- Link tambah siswa, kayak tombol add. -->
                </div>

                <?php if ($error): ?> <!-- Kalau ada error, tampilkan alert. -->
                    <div class="alert alert-error" id="alert"><?php echo htmlspecialchars($error); ?></div> <!-- Alert error. -->
                <?php endif; ?>

                <?php if ($success): ?> <!-- Kalau ada success, tampilkan alert. -->
                    <div class="alert alert-success" id="alert"><?php echo htmlspecialchars($success); ?></div> <!-- Alert success. -->
                <?php endif; ?>

                <?php if (count($all_students) > 0): ?> <!-- Kalau ada siswa, tampilkan tabel. -->
                    <div class="table-container"> <!-- Container tabel, kayak meja makan. -->
                        <table class="data-table"> <!-- Tabel data, kayak spreadsheet. -->
                            <thead> <!-- Header tabel. -->
                                <tr> <!-- Baris header. -->
                                    <th>Student ID</th> <!-- Kolom Student ID. -->
                                    <th>Name</th> <!-- Kolom Name. -->
                                    <th>Email</th> <!-- Kolom Email. -->
                                    <th>Grade</th> <!-- Kolom Grade. -->
                                    <th>Phone</th> <!-- Kolom Phone. -->
                                    <th>Actions</th> <!-- Kolom Actions. -->
                                </tr>
                            </thead>
                            <tbody> <!-- Body tabel. -->
                                <?php foreach ($all_students as $student): ?> <!-- Loop foreach buat setiap siswa. -->
                                    <tr> <!-- Baris data. -->
                                        <td><span class="badge"><?php echo htmlspecialchars($student['student_id']); ?></span></td> <!-- TD dengan badge. -->
                                        <td><?php echo htmlspecialchars($student['name']); ?></td> <!-- TD nama. -->
                                        <td><?php echo htmlspecialchars($student['email']); ?></td> <!-- TD email. -->
                                        <td><?php echo htmlspecialchars($student['grade'] ?? '-'); ?></td> <!-- TD grade. -->
                                        <td><?php echo htmlspecialchars($student['phone'] ?? '-'); ?></td> <!-- TD phone. -->
                                        <td class="action-buttons"> <!-- TD actions. -->
                                            <a href="?action=view&id=<?php echo $student['id']; ?>" class="btn btn-icon btn-info" title="View">👁️</a> <!-- Link view. -->
                                            <a href="?action=edit&id=<?php echo $student['id']; ?>" class="btn btn-icon btn-warning" title="Edit">✏️</a> <!-- Link edit. -->
                                            <a href="?action=delete&id=<?php echo $student['id']; ?>" class="btn btn-icon btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')">🗑️</a> <!-- Link delete dengan confirm. -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?> <!-- Tutup foreach. -->
                            </tbody>
                        </table>
                    </div>
                <?php else: ?> <!-- Kalau ga ada siswa, tampilkan empty state. -->
                    <div class="empty-state"> <!-- State kosong, kayak ruangan sepi. -->
                        <div class="empty-icon">📝</div> <!-- Icon kosong. -->
                        <h3>No Students Yet</h3> <!-- Judul kosong. -->
                        <p>Start by adding your first student to the system.</p> <!-- Deskripsi. -->
                        <a href="?action=create" class="btn btn-primary">Add Student</a> <!-- Link tambah. -->
                    </div>
                <?php endif; ?>

            <?php elseif ($action === 'create' || $action === 'edit'): ?> <!-- Kondisi create atau edit. -->
                <!-- Create/Edit Form --> <!-- Form create/edit, kayak formulir pendaftaran. -->
                <div class="page-header"> <!-- Header halaman. -->
                    <div> <!-- Wrapper. -->
                        <h1><?php echo $action === 'create' ? 'Add New Student' : 'Edit Student'; ?></h1> <!-- Judul dinamis. -->
                        <p><?php echo $action === 'create' ? 'Enter student information below' : 'Update student information'; ?></p> <!-- Deskripsi dinamis. -->
                    </div>
                    <a href="?action=list" class="btn btn-secondary">Back to List</a> <!-- Link balik ke list. -->
                </div>

                <?php if ($error): ?> <!-- Alert error. -->
                    <div class="alert alert-error" id="alert"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="form-card"> <!-- Card form, kayak kartu nama. -->
                    <form method="POST" action="" class="crud-form"> <!-- Form POST. -->
                        <?php if ($action === 'edit'): ?> <!-- Kalau edit, tambah hidden input. -->
                            <input type="hidden" name="edit_id" value="<?php echo $student_data['id']; ?>"> <!-- Hidden ID. -->
                        <?php endif; ?>

                        <div class="form-row"> <!-- Row form, kayak baris di formulir. -->
                            <div class="form-group"> <!-- Group nama. -->
                                <label for="name">Full Name *</label> <!-- Label nama. -->
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Enter student's full name"
                                    value="<?php echo htmlspecialchars($student_data['name'] ?? $_POST['name'] ?? ''); ?>"
                                    required
                                > <!-- Input nama. -->
                            </div>

                            <div class="form-group"> <!-- Group student ID. -->
                                <label for="student_id">Student ID *</label> <!-- Label student ID. -->
                                <input
                                    type="text"
                                    id="student_id"
                                    name="student_id"
                                    placeholder="e.g., STD001"
                                    value="<?php echo htmlspecialchars($student_data['student_id'] ?? $_POST['student_id'] ?? ''); ?>"
                                    required
                                > <!-- Input student ID. -->
                            </div>
                        </div>

                        <div class="form-row"> <!-- Row kedua. -->
                            <div class="form-group"> <!-- Group email. -->
                                <label for="email">Email *</label> <!-- Label email. -->
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="student@school.com"
                                    value="<?php echo htmlspecialchars($student_data['email'] ?? $_POST['email'] ?? ''); ?>"
                                    required
                                > <!-- Input email. -->
                            </div>

                            <div class="form-group"> <!-- Group grade. -->
                                <label for="grade">Grade</label> <!-- Label grade. -->
                                <input
                                    type="text"
                                    id="grade"
                                    name="grade"
                                    placeholder="e.g., 10th Grade"
                                    value="<?php echo htmlspecialchars($student_data['grade'] ?? $_POST['grade'] ?? ''); ?>"
                                > <!-- Input grade. -->
                            </div>
                        </div>

                        <div class="form-row"> <!-- Row ketiga. -->
                            <div class="form-group"> <!-- Group phone. -->
                                <label for="phone">Phone Number</label> <!-- Label phone. -->
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    placeholder="+1 (555) 123-4567"
                                    value="<?php echo htmlspecialchars($student_data['phone'] ?? $_POST['phone'] ?? ''); ?>"
                                > <!-- Input phone. -->
                            </div>
                        </div>

                        <div class="form-group"> <!-- Group address. -->
                            <label for="address">Address</label> <!-- Label address. -->
                            <textarea
                                id="address"
                                name="address"
                                placeholder="Enter student's address"
                                rows="3"
                            ><?php echo htmlspecialchars($student_data['address'] ?? $_POST['address'] ?? ''); ?></textarea> <!-- Textarea address. -->
                        </div>

                        <div class="form-actions"> <!-- Actions form. -->
                            <a href="?action=list" class="btn btn-secondary">Cancel</a> <!-- Link cancel. -->
                            <button type="submit" class="btn btn-primary">
                                <?php echo $action === 'create' ? 'Add Student' : 'Update Student'; ?> <!-- Button submit dinamis. -->
                            </button>
                        </div>
                    </form>
                </div>

            <?php elseif ($action === 'view' && $student_data): ?> <!-- Kondisi view dan ada data. -->
                <!-- View Details --> <!-- View detail, kayak baca CV. -->
                <div class="page-header"> <!-- Header. -->
                    <div> <!-- Wrapper. -->
                        <h1>Student Details</h1> <!-- Judul. -->
                        <p>Viewing complete student information</p> <!-- Deskripsi. -->
                    </div>
                    <div> <!-- Wrapper actions. -->
                        <a href="?action=edit&id=<?php echo $student_data['id']; ?>" class="btn btn-primary">Edit</a> <!-- Link edit. -->
                        <a href="?action=list" class="btn btn-secondary">Back to List</a> <!-- Link balik. -->
                    </div>
                </div>

                <div class="profile-card"> <!-- Card profil, kayak kartu nama. -->
                    <div class="profile-header"> <!-- Header profil. -->
                        <div class="profile-avatar large"><?php echo strtoupper(substr($student_data['name'], 0, 1)); ?></div> <!-- Avatar. -->
                        <div class="profile-info"> <!-- Info profil. -->
                            <h3><?php echo htmlspecialchars($student_data['name']); ?></h3> <!-- Nama. -->
                            <p class="profile-id">ID: <?php echo htmlspecialchars($student_data['student_id']); ?></p> <!-- ID. -->
                        </div>
                    </div>
                    <div class="profile-body"> <!-- Body profil. -->
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
                        <div class="info-row"> <!-- Row created. -->
                            <span class="info-label">Created At</span>
                            <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($student_data['created_at'])); ?></span>
                        </div>
                        <div class="info-row"> <!-- Row updated. -->
                            <span class="info-label">Last Updated</span>
                            <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($student_data['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?> <!-- Tutup kondisi action. -->
        </div> <!-- Tutup content-wrapper. -->
    </div> <!-- Tutup main-container. -->

    <script src="script.js"></script> <!-- Script JS, kayak vitamin buat halaman. -->
</body> <!-- Tutup body. -->
</html> <!-- Tutup html. -->
