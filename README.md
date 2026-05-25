md
# 🟢 Realtime Chat App — Laravel + Socket.IO

Aplikasi chat realtime berbasis **Laravel 13**, **Node.js**, dan **Socket.IO** yang mendukung percakapan pribadi antar pengguna dengan tampilan modern seperti aplikasi chat pada umumnya.

# ✨ Fitur Utama

- ✅ Login & Register User
- ✅ Realtime Chat (tanpa refresh)
- ✅ Private Chat (1-on-1)
- ✅ Bubble Chat kiri & kanan
- ✅ Sidebar daftar user
- ✅ Riwayat chat tersimpan di database
- ✅ Chat tetap ada setelah refresh
- ✅ Multi User Login
- ✅ Logout
- ✅ Responsive UI sederhana

# 🛠️ Teknologi yang Digunakan

## Backend
- Laravel 13
- PHP 8+
- MySQL

## Frontend
- Blade Template
- Vite
- JavaScript

## Realtime Server
- Node.js
- Express.js
- Socket.IO

# 📦 Package yang Digunakan

## Composer
bash
composer require laravel/breeze

## NPM
```bash
npm install
npm install express socket.io cors socket.io-client
```

# 📁 Struktur Project

```text
app/
 ├── Http/Controllers/
 │     └── ChatController.php
 │
 ├── Models/
 │     ├── Message.php
 │     └── User.php
 │
resources/
 ├── views/
 │     └── dashboard.blade.php
 │
 └── js/
       └── app.js

routes/
 └── web.php

database/
 └── migrations/
```

# ⚙️ Cara Instalasi

## 1. Clone Repository

bash
git clone https://github.com/username/laravel-chat-app.git

Masuk ke folder project:

bash
cd laravel-chat-app


# 2. Install Dependency Laravel

bash
composer install

# 3. Install Dependency Node.js
bash
npm install


Install package realtime:
bash
npm install express socket.io cors socket.io-client


# 4. Setup Environment

Copy file `.env`

## Windows
```bash
copy .env.example .env
```

## Linux / MacOS
bash
cp .env.example .env

Generate application key:

```bash
php artisan key:generate

# 5. Konfigurasi Database

Buat database baru, contoh:

```text
chat_app
```

Lalu edit file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_app
DB_USERNAME=root
DB_PASSWORD=


# 6. Jalankan Migration

bash
php artisan migrate

# 7. Jalankan Project

Project membutuhkan 3 terminal aktif.


## Terminal 1 — Laravel Server

bash
php artisan serve


## Terminal 2 — Vite

bash
npm run dev

Jika menggunakan PowerShell dan muncul error execution policy:

powershell
npm.cmd run dev

## Terminal 3 — Socket.IO Server

bash
npm start

Jika menggunakan PowerShell:

powershell
npm.cmd start


# 🚀 Cara Menggunakan

## 1. Jalankan aplikasi

Buka browser:
`text
http://127.0.0.1:8000


## 2. Register akun pertama

Contoh:

| Name | Email |
|---|---|
| Mutia | mutia@gmail.com |


## 3. Buka Incognito / Private Window

Shortcut Chrome / Edge:

text
CTRL + SHIFT + N

## 4. Register akun kedua

Contoh:

| Name | Email |
|---|---|
| Zaskia | zaskia@gmail.com |

## 5. Login menggunakan akun berbeda

- Browser biasa → akun pertama
- Incognito → akun kedua

## 6. Mulai Chat

- Klik user pada sidebar
- Ketik pesan
- Klik tombol **Send**
- Pesan akan muncul realtime tanpa refresh 🎉

# 💬 Fitur Realtime

Socket.IO digunakan untuk:
- mengirim pesan realtime
- menerima pesan realtime
- komunikasi antar browser
- update chat tanpa reload halaman

# 🗄️ Database

## Tabel `users`
Digunakan untuk menyimpan data akun user.

## Tabel `messages`
Digunakan untuk menyimpan:
- sender
- receiver
- isi pesan
- timestamp chat

 🧩 Troubleshooting

## Error: `npm.ps1 cannot be loaded`

Gunakan:

powershell
npm.cmd run dev

atau:

powershell
npm.cmd start

## Error: `Port 5173 is in use`

Vite otomatis pindah port, misalnya:

text
http://localhost:5174

## Chat tidak realtime

Pastikan:
- `npm start` berjalan
- `php artisan serve` berjalan
- `npm run dev` berjalan

## Chat hilang setelah refresh

Pastikan:
- migration sudah dijalankan
- route `/save-message` sudah ada
- tabel `messages` tersedia di database

# 📸 Tampilan Aplikasi

Fitur tampilan:
- Sidebar daftar user
- Bubble chat modern
- Bubble kanan untuk user sendiri
- Bubble kiri untuk lawan chat
- Input pesan realtime

# 👨‍💻 Author

Mutia Sitompul 
Project Realtime Chat App Laravel + Socket.IO

