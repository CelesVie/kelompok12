# Flowchart Alur Program Login Hingga CRUD Siswa

```
+-------+
| START |
+-------+
    |
    v
+---------------------------+
| INDEX.PHP: Redirect Login |
+---------------------------+
    |
    v
+------------+
| LOGIN.PHP  |<---------------+
+------------+                |
    |                         |
    v                         |
+---------------------------+ |
| Input: Username & Password| |
+---------------------------+ |
    |                         |
    v                         |
+-------------------------------+
| Proses Autentikasi di LOGIN.PHP|
+-------------------------------+
    |   Jika sukses   |    Jika gagal
    v                 |      v
+----------------+    | +------------------------+
| Set Session    |    | | Tampilkan Error &      |
+----------------+    | | Kembali ke Form Login  |
    |                 | +------------------------+
    v                 |
+---------------------+
| REDIRECT: DASHBOARD.PHP
+---------------------+
    |
    v
+-------------------------------+
| DASHBOARD.PHP (Role-based)    |
+-------------------------------+
    |           |
    |           v
    |    +-------------------------+
    |    | Jika role 'teacher'     |
    |    +-------------------------+
    |           |
    |           v
    |    +-----------------------+
    |    | CRUD_SISWA.PHP: Access|
    |    +-----------------------+
    |           |
    | <---[Cek Session + Role]---+
    |           |
    v   (Jika valid & teacher)
+--------------------------+
| CRUD Action (GET: action)|
+--------------------------+
    |
    |----"list": Lihat siswa
    |----"create": Tambah siswa
    |----"edit": Edit siswa
    |----"delete": Hapus siswa
    |
    v
+---------------------+
| Proses ke Database  |
| (config.php)        |
+---------------------+
    |
    v
+--------------------+
| Tampilkan hasil    |
+--------------------+
    |
    v
+-------------+
| LOGOUT.PHP  |
+-------------+
    |
    v
+-------+
|  END  |
+-------+
```

## Penjelasan Setiap Langkah dan File Penting

1. **index.php**  
   - Otomatis mengarahkan user ke `login.php`.

2. **login.php**  
   - Form input user: username/email dan password.
   - Cek autentikasi user ke database (`config.php`).
   - Jika benar, sesi dan role user diset, redirect ke `dashboard.php`.
   - Kalau belum pernah register, silahkan buat dulu dengan menekan teks `Create one`
   - Jika salah, tampilkan error.

3. **dashboard.php**
   - Mengecek sesi login. Jika belum login, redirect kembali ke `login.php`.
   - Tampilkan halaman utama berdasarkan peran:
     - *Teacher*: menu akses penuh ke CRUD siswa.
     - *Student*: hanya bisa melihat data dirinya.

4. **crud_siswa.php**  
   - Hanya bisa diakses oleh role 'teacher' (berdasarkan session dan role).
   - Action ditentukan dari parameter GET (`list`, `create`, `edit`, `delete`).
   - Masing-masing action:
     - **list**: Menampilkan daftar siswa.
     - **create**: Form tambah siswa, cek validasi.
     - **edit/view**: Edit atau lihat profil siswa.
     - **delete**: Hapus data siswa.
   - Semua proses query menggunakan koneksi database di `config.php`.

5. **config.php**  
   - Konfigurasi dan fungsi koneksi ke database.

6. **logout.php**  
   - Menghapus sesi dan kembali ke halaman login.

7. **register.php**  
   - Pendaftaran user baru (Guru/Siswa). Validasi dan input ke database.

8. **script.js**  
   - Fitur interaktif, animasi, auto-dismiss notifikasi.

## Hubungan Antar File

- Semua file utama (`login.php`, `dashboard.php`, `crud_siswa.php`, `register.php`) membutuhkan dan meng-include `config.php` untuk koneksi database.
- Data session dari login menentukan akses CRUD dan tampilan dashboard.
- Perpindahan halaman utama selalu via redirect HTTP, sesuai logika session/role dan aksi user.

## Ringkasan
- Alur utama: Index → Login → Dashboard (role) → CRUD (jika teacher) → Logout/End.
- Setiap file berperan sesuai proses urutan di flowchart di atas.

---
Referensi file: [List file di repo "kelompok12"](https://github.com/CelesVie/kelompok12)