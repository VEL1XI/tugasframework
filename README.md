# tugasframework
# Laravel File Manager - Panduan Instalasi

Panduan lengkap untuk membuat aplikasi file manager sederhana menggunakan Laravel dengan fitur upload, download, preview, dan delete file.

## 📋 Persyaratan Sistem

Pastikan sistem Anda sudah memiliki:
- **PHP** versi 8.1 atau lebih baru
- **Composer** (Dependency Manager untuk PHP)
- **Node.js** dan **npm** (opsional, untuk asset compilation)
- **Git** (untuk version control)
- **Web Server** (Apache/Nginx) atau menggunakan built-in server Laravel

### Cara Cek Persyaratan:
```bash
# Cek versi PHP
php --version

# Cek Composer
composer --version

# Cek Node.js (opsional)
node --version
npm --version
```

## 🚀 Langkah-Langkah Instalasi

### 1. Install Composer (jika belum ada)

**Windows:**
1. Kunjungi https://getcomposer.org/download/
2. Download Composer-Setup.exe
3. Jalankan installer dan ikuti petunjuk
4. Restart Command Prompt untuk memastikan Composer terdeteksi

**macOS:**
```bash
# Install via Homebrew (recommended)
brew install composer

# Atau install manual
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Linux (Ubuntu/Debian):**
```bash
# Update package manager
sudo apt update

# Install PHP dan dependencies yang diperlukan
sudo apt install php-cli php-mbstring php-xml php-curl php-zip unzip

# Download dan install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verifikasi instalasi
composer --version
```

### 2. Install Laravel

```bash
# Buat project Laravel baru
composer create-project laravel/laravel file-manager-app

# Masuk ke direktori project
cd file-manager-app

# Verifikasi instalasi Laravel
php artisan --version
```

### 3. Setup Environment

```bash
# Copy file environment template
cp .env.example .env

# Generate application key (wajib)
php artisan key:generate

# (Opsional) Edit file .env untuk konfigurasi database jika diperlukan
```

## ⚙️ Konfigurasi Project

### 1. Setup Storage System

```bash
# Buat symbolic link untuk mengakses file dari web
php artisan storage:link

# Buat direktori khusus untuk upload file
mkdir -p storage/app/public/uploads

# Set permission yang tepat (Linux/macOS)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 2. Verifikasi Konfigurasi

- Pastikan file `.env` sudah ada dan berisi `APP_KEY`
- Cek bahwa direktori `public/storage` sudah ter-link ke `storage/app/public`
- Pastikan direktori `storage/app/public/uploads` sudah dibuat

## 💻 Struktur File yang Perlu Dibuat

### 1. Controller
- **Lokasi:** `app/Http/Controllers/FileController.php`
- **Fungsi:** Menangani logika upload, download, delete, dan preview file
- **Method yang diperlukan:**
  - `index()` - Menampilkan daftar file
  - `upload()` - Menangani upload file
  - `download()` - Menangani download file
  - `stream()` - Menangani preview file
  - `delete()` - Menangani penghapusan file

### 2. Routes
- **Lokasi:** `routes/web.php`
- **Fungsi:** Mendefinisikan URL dan mapping ke controller
- **Routes yang diperlukan:**
  - `GET /files` - Halaman utama
  - `POST /files/upload` - Endpoint upload
  - `GET /files/download/{filename}` - Endpoint download
  - `GET /files/stream/{filename}` - Endpoint preview
  - `DELETE /files/delete/{filename}` - Endpoint delete

### 3. View Template
- **Lokasi:** `resources/views/files/index.blade.php`
- **Fungsi:** Interface user untuk interact dengan file
- **Komponen yang diperlukan:**
  - Form upload file
  - Tabel daftar file
  - Tombol aksi (download, preview, delete)
  - Alert messages untuk feedback

## 🎯 Menjalankan dan Testing

### 1. Start Development Server
```bash
# Jalankan server Laravel (default port 8000)
php artisan serve

# Atau dengan port custom
php artisan serve --port=8080
```

### 2. Akses Aplikasi
- Buka browser
- Kunjungi `http://localhost:8000/files`
- Interface file manager akan muncul

### 3. Test Fungsionalitas
1. **Test Upload:**
   - Pilih file dari komputer
   - Klik tombol Upload
   - Verifikasi file muncul di daftar

2. **Test Download:**
   - Klik tombol download (ikon hijau)
   - File akan otomatis terdownload

3. **Test Preview:**
   - Klik tombol preview (ikon biru)
   - File akan terbuka di tab baru

4. **Test Delete:**
   - Klik tombol delete (ikon merah)
   - Konfirmasi penghapusan
   - Verifikasi file hilang dari daftar

## 🔧 Troubleshooting Umum

### 1. Permission Issues (Linux/macOS)
```bash
# Ubah ownership ke web server
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

# Atau set permission 777 (tidak recommended untuk production)
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

### 2. Composer Issues
```bash
# Clear cache composer
composer clear-cache

# Update dependencies
composer update

# Regenerate autoload
composer dump-autoload
```

### 3. Laravel Artisan Issues
```bash
# Clear semua cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan optimize
```

### 4. File Upload Issues
- Cek `php.ini` untuk setting:
  - `upload_max_filesize = 10M`
  - `post_max_size = 12M`
  - `max_execution_time = 300`
- Restart web server setelah mengubah `php.ini`

### 5. Storage Link Issues
```bash
# Hapus link lama dan buat ulang
rm public/storage
php artisan storage:link

# Atau force create
php artisan storage:link --force
```

## 📝 Tips Development

### 1. Debugging
- Gunakan `dd()` untuk debug variabel
- Cek Laravel log di `storage/logs/laravel.log`
- Aktifkan debug mode di `.env`: `APP_DEBUG=true`

### 2. Security untuk Production
- Set `APP_DEBUG=false` di production
- Tambahkan validasi file type yang ketat
- Implementasi authentication/authorization
- Gunakan HTTPS
- Set proper file permissions

### 3. Performance
- Untuk file besar, pertimbangkan chunked upload
- Implementasi file compression
- Gunakan CDN untuk file static
- Cache file metadata

### 4. Scalability
- Untuk production, gunakan cloud storage (AWS S3, Google Cloud)
- Implementasi file versioning
- Tambahkan file search functionality
- Implementasi folder/category system

## 🎓 Fitur Laravel yang Dipelajari

Melalui project ini, Anda akan belajar:
- **Storage Facade** - File system Laravel
- **Request Validation** - Validasi input user
- **Blade Templating** - Template engine Laravel
- **Route Model Binding** - URL routing
- **Flash Messages** - Session-based messaging
- **File Handling** - Upload/download management
- **Error Handling** - Exception management

## 📚 Referensi Pembelajaran

- **Laravel Documentation:** https://laravel.com/docs
- **Laravel Storage:** https://laravel.com/docs/filesystem
- **Laravel Validation:** https://laravel.com/docs/validation
- **Blade Templates:** https://laravel.com/docs/blade

Selamat belajar Laravel! Ikuti langkah-langkah di atas secara berurutan untuk hasil terbaik. 🚀
