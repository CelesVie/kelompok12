-- Skema database buat sistem sekolah, kayak blueprint bangunan biar ga salah bangun.
-- File ini isi perintah SQL buat bikin struktur database, kayak resep masak biar ga salah bahan.

-- Bikin database kalau belum ada, kayak siapin wadah dulu sebelum isi.
CREATE DATABASE IF NOT EXISTS school_system; -- Nama database school_system, kayak nama restoran.

-- Pake database yang baru dibikin, kayak masuk ke restoran.
USE school_system; -- Masuk ke school_system.

-- Bikin tabel users, kayak daftar tamu yang datang.
CREATE TABLE IF NOT EXISTS users ( -- Kalau belum ada, bikin tabel users.
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID auto increment, kayak nomor antrian.
    username VARCHAR(50) NOT NULL UNIQUE, -- Username unik, ga boleh sama kayak nama panggilan.
    email VARCHAR(100) NOT NULL UNIQUE, -- Email unik juga, kayak alamat rumah yang beda-beda.
    password VARCHAR(255) NOT NULL, -- Password hashed, kayak rahasia yang dikunci.
    role ENUM('teacher', 'student') NOT NULL, -- Role teacher atau student, kayak jabatan di kantor.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Waktu dibikin, kayak stempel waktu lahir.
); -- Tutup tabel users.

-- Bikin tabel students, kayak daftar siswa kelas.
CREATE TABLE IF NOT EXISTS students ( -- Kalau belum ada, bikin tabel students.
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID auto increment lagi.
    user_id INT NULL, -- Foreign key ke users, nullable buat siswa yang belum punya akun.
    name VARCHAR(100) NOT NULL, -- Nama lengkap, kayak nama asli di KTP.
    student_id VARCHAR(20) NOT NULL UNIQUE, -- Student ID unik, kayak nomor induk siswa.
    email VARCHAR(100) NOT NULL, -- Email siswa.
    grade VARCHAR(50) NULL, -- Grade/kelas, kayak tingkat sekolah.
    phone VARCHAR(20) NULL, -- Nomor telepon, kayak nomor HP buat hubungi.
    address TEXT NULL, -- Alamat lengkap, kayak alamat rumah.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu dibikin.
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Waktu diupdate, kayak stempel terakhir edit.
); -- Tutup tabel students.

-- Tambah constraint foreign key, kayak hubungan keluarga biar ga cerai.
ALTER TABLE students -- Alter tabel students.
ADD CONSTRAINT fk_students_user_id -- Nama constraint.
FOREIGN KEY (user_id) REFERENCES users(id) -- Foreign key ke users(id).
ON DELETE SET NULL -- Kalau user dihapus, set null.
ON UPDATE CASCADE; -- Kalau user diupdate, cascade.

-- Insert students sample.
INSERT INTO students (user_id, name, student_id, email, grade, phone, address) VALUES -- Insert ke students.
(2, 'John Doe', 'STD001', 'student1@school.com', '10th Grade', '+1 (555) 123-4567', '123 Main St, Anytown, USA'), -- Student 1.
(NULL, 'Jane Smith', 'STD002', 'jane@school.com', '9th Grade', '+1 (555) 987-6543', '456 Oak Ave, Somewhere, USA'), -- Student 2 tanpa user_id.
(NULL, 'Bob Johnson', 'STD003', 'bob@school.com', '11th Grade', '+1 (555) 456-7890', '789 Pine Rd, Elsewhere, USA'); -- Student 3.

-- Di production, pake password kuat dan hashing yang bener