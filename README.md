<p align="center"><img src="https://laravel.com/img/logotype.min.svg" width="400"></p>

# Aplikasi Laundry Sederhana

Aplikasi ini merupakan aplikasi laundry sederhana yang dibuat dengan framework Laravel 7 (sudah diupgrade ke Laravel 9). Aplikasi ini adalah project untuk menyelesaikan UAS Metodologi Penulisan Ilmiah ITB STIKOM Bali.

Daftar/Register admin dengan url `/register-admin`. Secret key dapat diubah di env atau default "Secret123".

Login admin:  
Email : admin@laundryxyz.com  
Pass : admin123

## Instalasi

1. Copy dan rename .env.example menjadi .env
2. Konfigurasi .env sesuai kebutuhan
3. Buat app key:

```
php artisan key:generate
```

4. Jalankan migrasi dan seed:

```
php artisan migrate:fresh --seed
```

## Informasi Tambahan

Aplikasi ini juga menerapkan queue database untuk menghapus foto profil ketika diganti, kalian bisa mengganti environment variable `QUEUE_CONNECTION` menjadi database.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
