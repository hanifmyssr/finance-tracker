# 💰 Pencatat Keuangan Pribadi

Aplikasi web sederhana untuk mencatat pemasukan dan pengeluaran pribadi, dilengkapi dashboard dengan grafik visualisasi (pie chart & tren bulanan).

## ✨ Fitur

- Tambah, edit, dan hapus transaksi (pemasukan/pengeluaran)
- Kategori transaksi (Gaji, Makanan, Transportasi, dll)
- Dashboard ringkasan: total pemasukan, total pengeluaran, saldo
- Grafik pie chart pengeluaran per kategori
- Grafik tren pemasukan vs pengeluaran 6 bulan terakhir
- Filter transaksi berdasarkan bulan dan kategori

## 🛠️ Teknologi

- **Backend:** PHP (native, tanpa framework)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Chart.js
- **Server lokal:** XAMPP

## 📁 Struktur Folder

```
finance-tracker/
├── assets/
│   ├── css/style.css
│   └── js/
├── config/
│   └── db.php              # Koneksi database
├── includes/
│   ├── header.php          # Navbar & head
│   └── footer.php          # Footer & script
├── index.php                # Dashboard + grafik
├── transactions.php         # Daftar transaksi + filter
├── add_transaction.php      # Form tambah transaksi
├── edit_transaction.php     # Form edit transaksi
├── delete_transaction.php   # Handler hapus transaksi
├── database.sql             # Struktur & data awal database
└── README.md
```

## 🚀 Cara Menjalankan (dengan XAMPP)

1. Clone atau download repo ini ke folder `htdocs` XAMPP:
   ```
   C:\xampp\htdocs\finance-tracker
   ```
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`), lalu import file `database.sql` untuk membuat database dan tabel.
4. Sesuaikan koneksi database di `config/db.php` jika perlu (default: host `localhost`, user `root`, password kosong).
5. Buka browser dan akses:
   ```
   http://localhost/finance-tracker
   ```

## 📸 Halaman

- **Dashboard** (`index.php`) — ringkasan saldo dan grafik
- **Daftar Transaksi** (`transactions.php`) — semua transaksi dengan filter
- **Tambah Transaksi** (`add_transaction.php`) — form input transaksi baru

## 💡 Pengembangan Selanjutnya (Ide)

- Autentikasi login multi-user
- Export data ke Excel/CSV
- Set budget limit per kategori dengan notifikasi
- Mode gelap (dark mode)
- Deploy ke hosting (misalnya dengan database cloud)

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan portofolio pribadi. Bebas digunakan dan dimodifikasi.
