# 🟢 Realtime Chat App — Panduan Instalasi

## Fitur
- ✅ Bubble chat kiri/kanan (gaya WhatsApp)
- ✅ Indikator Online / Offline + Last seen
- ✅ Chat Private (1-on-1)
- ✅ Chat Grup
- ✅ Realtime (pesan masuk tanpa refresh)
- ✅ Pesan tersimpan di database (tidak hilang saat refresh)
- ✅ Logout
- ✅ Notifikasi unread badge

---

## 📁 Salin file-file ini ke project Laravel kamu

```
app/
  Events/
    MessageSent.php         → salin ke project
    UserStatusChanged.php   → salin ke project
  Http/Controllers/
    ChatController.php      → salin ke project
  Models/
    Message.php             → salin ke project
    Group.php               → salin ke project
    User.php                → REPLACE file User.php lama

database/migrations/
  2026_05_17_000001_create_messages_table.php
  2026_05_17_000002_create_groups_table.php
  2026_05_17_000003_add_online_status_to_users.php

resources/
  views/chat/index.blade.php
  js/app.js                 → REPLACE app.js lama

routes/
  web.php                   → REPLACE web.php lama
  channels.php              → REPLACE channels.php lama
```

---

## ⚙️ Langkah-langkah

### 1. Install package yang dibutuhkan
```bash
composer require pusher/pusher-php-server
npm install --save-dev laravel-echo pusher-js
```

### 2. Daftar akun Pusher GRATIS
1. Buka https://pusher.com → Sign Up (gratis)
2. Buat app baru → pilih cluster **mt1** (Asia)
3. Copy **App ID, Key, Secret, Cluster**

### 3. Update .env
Buka file `.env` dan isi:
```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=isi-app-id-kamu
PUSHER_APP_KEY=isi-app-key-kamu
PUSHER_APP_SECRET=isi-app-secret-kamu
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. Uncomment BroadcastServiceProvider
Buka `config/app.php`, cari dan uncomment:
```php
App\Providers\BroadcastServiceProvider::class,
```

### 5. Jalankan migration
```bash
php artisan migrate
```

> Jika error karena urutan foreign key (groups belum ada saat messages dibuat),
> jalankan migration groups dulu:
> ```bash
> php artisan migrate --path=database/migrations/2026_05_17_000002_create_groups_table.php
> php artisan migrate
> ```

### 6. Build assets
```bash
npm run dev
# atau untuk production:
npm run build
```

### 7. Jalankan server
```bash
php artisan serve
```

Buka http://127.0.0.1:8000

---

## 🔧 Troubleshooting

### Pesan tidak realtime (tidak masuk tanpa refresh)
- Pastikan `.env` sudah diisi dengan key Pusher yang benar
- Jalankan `npm run dev` di terminal terpisah
- Cek di browser console apakah ada error Pusher

### Akun lain tidak bisa kirim pesan
- Pastikan route `/messages` ada di `web.php`
- Pastikan user sudah login (middleware `auth`)

### Error "Column not found: last_seen_at"
- Jalankan `php artisan migrate` lagi

### Chat hilang saat refresh
- Chat TIDAK akan hilang — tersimpan di database SQLite/MySQL
- Pastikan migration sudah dijalankan

---

## 📦 Package yang diinstall
```json
{
  "require": {
    "pusher/pusher-php-server": "^7.2"
  },
  "devDependencies": {
    "laravel-echo": "^1.15",
    "pusher-js": "^8.4"
  }
}
```
