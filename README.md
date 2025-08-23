# Geolokasi UMKM Kuliner

Platform digital yang menghubungkan UMKM kuliner lokal dengan pelanggan setia menggunakan teknologi geolokasi dan sistem rating yang transparan.

## 🚀 Fitur Utama

### Untuk UMKM Kuliner
- **Dashboard Penjual**: Kelola outlet, menu, dan pesanan
- **Manajemen Outlet**: Tambah, edit, dan kelola informasi outlet
- **Manajemen Menu**: Kelola menu dengan gambar dan harga
- **Sistem Verifikasi**: Outlet terverifikasi untuk kepercayaan pelanggan
- **Analitik**: Lihat statistik outlet dan performa menu

### Untuk Pelanggan
- **Pencarian Berdasarkan Lokasi**: Temukan outlet terdekat dengan GPS
- **Pencarian Menu**: Cari menu berdasarkan nama, kategori, dan harga
- **Sistem Rating & Review**: Berikan feedback untuk outlet dan menu
- **Informasi Lengkap**: Lihat jam operasional, alamat, dan status outlet

### Untuk Admin
- **Dashboard Admin**: Kelola semua outlet dan pengguna
- **Sistem Verifikasi**: Verifikasi outlet baru
- **Manajemen Pengguna**: Kelola role dan akses pengguna
- **Monitoring Platform**: Lihat statistik keseluruhan platform

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (Development) / MySQL (Production)
- **Frontend**: Bootstrap 5, Font Awesome
- **Authentication**: Laravel Built-in Auth
- **File Storage**: Laravel Storage
- **Geolocation**: GPS Coordinates (Latitude/Longitude)

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM (untuk asset compilation)
- Web server (Apache/Nginx) atau PHP built-in server

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd geolokasi_umkm
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

#### Untuk Development (SQLite)
```bash
# Buat file database SQLite
touch database/database.sqlite

# Edit .env file
DB_CONNECTION=sqlite
# Comment semua konfigurasi MySQL
```

#### Untuk Production (MySQL)
```bash
# Edit .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geolokasi_umkm
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Jalankan Migrasi dan Seeder
```bash
php artisan migrate:fresh --seed
```

### 6. Compile Assets
```bash
npm run dev
# atau untuk production
npm run build
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👥 Role Pengguna

### 1. Super Admin
- Akses penuh ke semua fitur
- Kelola admin dan role pengguna
- Verifikasi outlet

### 2. Admin
- Kelola outlet dan menu
- Verifikasi outlet baru
- Monitoring platform

### 3. Penjual/Outlet
- Kelola outlet sendiri
- Tambah dan edit menu
- Lihat pesanan dan review

### 4. Pembeli
- Cari outlet dan menu
- Berikan rating dan review
- Lihat informasi outlet

## 🔐 Akun Default

Setelah menjalankan seeder, tersedia akun default:

### Super Admin
- Email: superadmin@example.com
- Password: password

### Admin
- Email: admin@example.com
- Password: password

### Penjual
- Email: penjual@example.com
- Password: password

### Pembeli
- Email: pembeli@example.com
- Password: password

## 📱 Fitur Aplikasi

### 1. Halaman Utama
- Hero section dengan search
- Kategori populer
- Outlet unggulan
- Menu rekomendasi

### 2. Sistem Pencarian
- **Pencarian Outlet**: Berdasarkan nama, kategori, rating
- **Pencarian Menu**: Berdasarkan nama, kategori, harga
- **Pencarian Lokasi**: Berdasarkan koordinat GPS dan radius

### 3. Dashboard
- **Admin Dashboard**: Statistik platform, kelola outlet
- **Penjual Dashboard**: Statistik outlet, kelola menu
- **Pembeli Dashboard**: Riwayat pesanan, outlet favorit

### 4. Manajemen Outlet
- CRUD outlet dengan gambar
- Kategori outlet
- Jam operasional
- Koordinat GPS

### 5. Manajemen Menu
- CRUD menu dengan gambar
- Harga dan deskripsi
- Status ketersediaan
- Menu rekomendasi

### 6. Sistem Rating & Review
- Rating 1-5 bintang
- Komentar untuk outlet dan menu
- Verifikasi review

### 7. Sistem Order
- Pembuatan pesanan
- Status pesanan
- Riwayat pesanan

## 🗄️ Struktur Database

### Tabel Utama
- `users` - Data pengguna
- `roles` - Role pengguna
- `outlets` - Data outlet
- `menus` - Data menu
- `categories` - Kategori outlet/menu
- `ratings` - Rating outlet
- `reviews` - Review menu
- `orders` - Data pesanan
- `order_items` - Item dalam pesanan

### Relasi
- User ↔ Role (Many-to-Many)
- User ↔ Outlet (One-to-One)
- Outlet ↔ Menu (One-to-Many)
- Outlet ↔ Category (Many-to-Many)
- User ↔ Rating (One-to-Many)
- User ↔ Review (One-to-Many)
- User ↔ Order (One-to-Many)

## 🔧 Konfigurasi

### File Storage
- Gambar outlet dan menu disimpan di `storage/app/public/`
- Jalankan `php artisan storage:link` untuk membuat symbolic link

### Geolocation
- Koordinat GPS disimpan dalam format decimal
- Radius pencarian dalam kilometer
- Sistem dapat diintegrasikan dengan Google Maps API

### Email
- Konfigurasi SMTP untuk notifikasi
- Template email untuk verifikasi dan reset password

## 🚀 Deployment

### 1. Production Environment
```bash
# Set environment
APP_ENV=production
APP_DEBUG=false

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Web Server
- Apache dengan mod_rewrite
- Nginx dengan konfigurasi Laravel
- SSL certificate untuk keamanan

### 3. Database
- MySQL dengan optimasi
- Backup regular
- Monitoring performa

## 🧪 Testing

```bash
# Unit tests
php artisan test

# Feature tests
php artisan test --testsuite=Feature

# Browser tests
php artisan dusk
```

## 📊 Monitoring

### Logs
- Laravel logs di `storage/logs/`
- Error tracking
- Performance monitoring

### Analytics
- User activity
- Outlet performance
- Search analytics

## 🔒 Keamanan

- CSRF protection
- SQL injection prevention
- XSS protection
- File upload validation
- Role-based access control
- Input sanitization

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Support

- Email: support@geolokasi-umkm.com
- Dokumentasi: [Wiki](wiki-url)
- Issues: [GitHub Issues](issues-url)

## 🗺️ Roadmap

### Versi 1.1
- [ ] Integrasi Google Maps API
- [ ] Sistem notifikasi real-time
- [ ] Mobile app (React Native)

### Versi 1.2
- [ ] Sistem pembayaran
- [ ] Delivery tracking
- [ ] Analytics dashboard

### Versi 2.0
- [ ] Multi-language support
- [ ] Advanced search filters
- [ ] Social media integration

---

**Dibuat dengan ❤️ untuk UMKM Kuliner Indonesia**
