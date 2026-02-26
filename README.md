# Laravel JWT Authentication & Role-Permission System (Redis Blacklist)

Bu proje, **Laravel** tabanlı uygulamalar için  
**Stateless Authentication (JWT)**, **Redis destekli Token Blacklist** ve  
**Dinamik Yetkilendirme (RBAC)** mimarisini profesyonel standartlarda sunar.

JWT altyapısı olarak `tymon/jwt-auth` kullanılmıştır.  
Kullanıcı **logout olduğunda**, token süresi dolmamış olsa bile **Redis üzerinde blacklist’e alınır**  
ve tekrar kullanılması engellenir.

Mimari yapı:

- Repository Pattern
- Service Layer
- Permission-based middleware
- Redis-backed JWT blacklist

---

## 🚀 Öne Çıkan Özellikler

- **JWT Authentication (Stateless)**
    - `tymon/jwt-auth` ile güvenli kimlik doğrulama
    - Session kullanılmaz

- **Redis Token Blacklist**
    - Logout olan kullanıcıların token’ları Redis’te tutulur
    - Token süresi dolmadan tekrar kullanılamaz

- **Role & Permission (RBAC)**
    - Kullanıcı → Rol → İzin ilişkisi
    - Slug-based permission kontrolü

- **Repository Pattern**
    - Veritabanı erişimi soyutlanmıştır

- **Service Layer**
    - Business logic controller’dan ayrılmıştır

- **Permission Middleware**
    - Role değil, doğrudan izin kontrolü (`user-list`, `user-delete` vb.)

- **Mass Assignment Protection**
    - Modellerde `$fillable` kullanımı

---

## 🧱 Kullanılan Teknolojiler

- PHP 8+
- Laravel
- MySQL / PostgreSQL
- Redis
- tymon/jwt-auth

---

## 🛠 Kurulum (How to Install)

### Projeyi Klonlayın

```bash
git clone https://github.com/kullanici_adin/proje_adin.git
cd proje_adin
```

### PHP Bağımlılıklarını Yükleyin

```
composer install
```

### Ortam Dosyasını Oluşturun

```
cp .env.example .env
```

### .env dosyasında aşağıdakileri düzenleyin:

```
APP_NAME="Laravel JWT RBAC"
APP_ENV=local
APP_KEY=
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jwt_rbac
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Redis Kurulumu ve Çalıştırma

```
sudo apt update
sudo apt install redis-server
sudo systemctl enable redis
sudo systemctl start redis
```

## Kontrol:

```
redis-cli ping
# PONG
```

### Uygulama ve JWT Anahtarlarını Oluşturun

```
php artisan key:generate
php artisan jwt:secret
```

### JWT Config Yayınlama

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

## config/jwt.php içinde blacklist açık olmalı:

# tymon/jwt-auth, logout edilen token’ları Redis üzerinde otomatik blacklist’te tutar.

```
'blacklist_enabled' => true,
```

### Veritabanını ve Seed Verilerini Oluşturun

```
php artisan migrate --seed
```

### Uygulamayı Başlatın

```
php artisan serve
```

### Kullanım

- POST
```
/api/auth/login
```

- Body (JSON)

```
{
  "email": "admin@test.com",
  "password": "password123"
}
```
- Response
```
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOi...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

- HTTP Header
```
Authorization: Bearer {access_token}
```

- Logout (Token Blacklist)
```
POST /api/auth/logout
```
!!! 
Token süresi dolmamış olsa bile
Token’ı Redis blacklist içine alır
Aynı token tekrar kullanılamaz
