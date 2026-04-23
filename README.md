# Skill Test Elsoft API

Backend API berbasis Laravel 12 untuk kebutuhan autentikasi, master item, dan transaksi stock issue.

## Teknologi

- PHP 8.2+
- Laravel 12
- MySQL
- Laravel Sanctum (Bearer Token)
- Laravel Pint (code style)
- Scribe (API documentation)

## Fitur Utama

- Auth portal (signin/logout) dengan token Sanctum
- Master Item (list, create, update, delete)
- Stock Issue parent (list, create, get detail parent, update, delete)
- Stock Issue detail (create, get detail, update, delete)

## Quick Start

1. Clone repository.
2. Install dependency backend dan frontend.
3. Siapkan konfigurasi `.env`.
4. Jalankan migration + seeder.
5. Jalankan server.

Perintah cepat:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
php artisan serve
```


## Konfigurasi Environment

Sesuaikan konfigurasi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skilltest_elsoft
DB_USERNAME=root
DB_PASSWORD=
```

Variabel tambahan yang dipakai project:

- `TOKEN_EXPIRE_HOURS` (default: `3`, satuan jam)

## Data Seeder Default

Seeder utama ada di `DatabaseSeeder` dan menjalankan:

- `CompanySeeder`
- `RoleSeeder`
- `UserSeeder`
- `ItemMasterSeeder`
- `StockIssueMasterSeeder`

User default dari `UserSeeder`:

1. Admin
	- domain: `admin`
	- username: `admin`
	- password: `admin123`
2. Testcase
	- domain: `testcase`
	- username: `testcase`
	- password: `testcase123`

## Autentikasi

Autentikasi menggunakan Bearer Token (Sanctum).

1. Login ke endpoint signin.
2. Ambil token dari response signin.
3. Kirim header berikut pada endpoint yang diproteksi:

```http
Authorization: Bearer <token>
Accept: application/json
```

## Struktur Endpoint

Base route didefinisikan di `routes/api.php`:

- `portal/api/*` untuk auth
- `admin/api/*` untuk modul admin

### Auth

- `POST /portal/api/auth/signin`
- `POST /portal/api/auth/logout` (auth:sanctum)

### Item

- `GET /admin/api/item/list`
- `POST /admin/api/item`
- `POST /admin/api/item/save`
- `DELETE /admin/api/item/delete`

### Stock Issue (Parent)

- `GET /admin/api/v1/stockissue/list`
- `POST /admin/api/v1/stockissue`
- `GET /admin/api/v1/stockissue/{oid}`
- `POST /admin/api/v1/stockissue/{oid}`
- `DELETE /admin/api/v1/stockissue/{oid}`

### Stock Issue Detail

- `POST /admin/api/v1/stockissue/detail`
- `GET /admin/api/v1/stockissue/detail/{oid}`
- `POST /admin/api/v1/stockissue/detail/{oid}`
- `DELETE /admin/api/v1/stockissue/detail/{oid}`

## Dokumentasi API (Scribe)

Generate dokumentasi:

```bash
php artisan scribe:generate
```

Karena konfigurasi Scribe menggunakan mode `laravel`, docs bisa diakses di:

- `/docs`
- `/docs.postman`
- `/docs.openapi`

## Quality Checks

Format code:

```bash
php vendor/bin/pint
```

Jalankan test:

```bash
php artisan test
```

## Unit Test yang Tersedia

Test otomatis API tersedia di folder `tests/Feature`:

- `AuthApiTest.php`
	- signin
	- logout
- `ItemApiTest.php`
	- list item
	- create item
	- update item
	- delete item
- `StockIssueApiTest.php`
	- list/create/get/update/delete stock issue parent
	- create/get/update/delete stock issue detail

Menjalankan test per file:

```bash
php artisan test tests/Feature/AuthApiTest.php
php artisan test tests/Feature/ItemApiTest.php
php artisan test tests/Feature/StockIssueApiTest.php
```

## Catatan Implementasi

- ID utama menggunakan UUID.
- Penulisan response distandarkan lewat helper `ResponseHelper`.
- Arsitektur mengikuti pola Controller -> Service -> Repository.
