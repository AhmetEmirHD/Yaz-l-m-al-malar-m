# Multi-Theme System

Gelişmiş PHP tabanlı çoklu tema yönetim sistemi.

## Özellikler

- ✅ Tek ana admin panel
- ✅ Birden fazla tema yönetimi
- ✅ Her tema kendi mini admin paneline sahip
- ✅ Klasör bazlı tema sistemi
- ✅ ZIP ile tema yükleme
- ✅ Aktif tema değiştirme
- ✅ Güvenli PDO bağlantısı
- ✅ Bootstrap 5 arayüzü

## Kurulum

1. Dosyaları `c:\xampp\htdocs\multi-theme-system` klasörüne kopyalayın
2. Tarayıcıda açın: `http://localhost/multi-theme-system/install.php`
3. Kurulum sihirbazını tamamlayın

## Varsayılan Giriş Bilgileri

- **Admin Panel:** `http://localhost/multi-theme-system/admin/login.php`
- **Kullanıcı Adı:** `admin`
- **Şifre:** `admin123`

## Proje Yapısı

```
multi-theme-system/
├── config/
│   └── database.php          # Veritabanı ayarları
├── includes/
│   └── functions.php         # Helper fonksiyonları
├── admin/
│   ├── login.php            # Admin giriş
│   ├── dashboard.php        # Dashboard
│   ├── themes.php           # Tema yönetimi
│   └── logout.php           # Çıkış
├── themes/
│   └── locksmith-theme/     # Çilingir teması
│       ├── theme.json       # Tema bilgileri
│       ├── layout.php       # Ana layout
│       ├── header.php       # Header
│       ├── footer.php       # Footer
│       ├── style.css        # Stiller
│       └── admin/
│           └── index.php     # Tema admin paneli
├── sql/
│   └── database.sql         # Veritabanı şeması
├── index.php                # Ana sayfa (aktif tema yükler)
└── install.php              # Kurulum scripti
```

## Tema Yapısı

Her tema `/themes` klasörü altında bulunmalı ve şu dosyaları içermelidir:

```
theme-name/
├── theme.json       # Zorunlu - Tema bilgileri
├── layout.php       # Zorunlu - Ana layout
├── header.php       # Opsiyonel
├── footer.php       # Opsiyonel
├── style.css        # Opsiyonel
└── admin/           # Opsiyonel - Tema admin paneli
    └── index.php
```

### theme.json Örneği

```json
{
    "name": "Tema Adı",
    "description": "Tema açıklaması",
    "version": "1.0.0",
    "author": "Yazar",
    "has_admin": true
}
```

## Kullanım

### Ana Admin Panel

1. Admin paneline giriş yapın
2. **Tema Yönetimi** sayfasına gidin
3. **Yeni Tema Yükle** butonuna tıklayın
4. ZIP dosyasını seçin ve yükleyin
5. Temayı **Aktif Et** butonu ile aktif edin

### Tema Admin Paneli

Her tema kendi ayarlarını yönetebilir:
- Site adı, marka adı
- İletişim bilgileri
- Hero bölümü metinleri
- CTA bölümü metinleri

## Güvenlik

- PDO ile güvenli veritabanı bağlantısı
- Tüm inputlar sanitize ediliyor
- Password hashing (PASSWORD_DEFAULT)
- Session-based admin authentication

## Veritabanı

- **admin_users:** Admin kullanıcıları
- **themes:** Tema bilgileri
- **theme_settings:** Tema ayarları (key-value)

## Geliştirme

### Yeni Tema Oluşturma

1. `/themes` klasörüne yeni klasör oluşturun
2. `theme.json` dosyasını ekleyin
3. `layout.php` dosyasını oluşturun
4. İsteğe bağlı olarak `header.php`, `footer.php`, `style.css` ekleyin
5. İsteğe bağlı olarak `admin/index.php` ile tema admin paneli oluşturun
6. Temayı admin panelden ZIP olarak yükleyin

### Tema Fonksiyonları

```php
// Aktif tema bilgisi
$theme = getActiveTheme();

// Tema ayarı al
$value = getThemeSetting($themeId, 'key', 'default');

// Tema ayarı kaydet
setThemeSetting($themeId, 'key', 'value');

// Temayı aktif et
activateTheme($themeId);
```

## Lisans

Bu proje açık kaynak olarak geliştirilmiştir.

## Destek

Sorularınız için: admin@example.com
