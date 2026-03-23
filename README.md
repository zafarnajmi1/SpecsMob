# GSMarena Clone

A comprehensive Laravel-based web application that replicates and extends the functionality of GSMarena, a popular mobile device specifications and reviews platform. This project demonstrates modern Laravel development practices with a focus on SEO optimization, user engagement, and detailed device cataloging.

## 🎯 Project Overview

GSMarena Clone is a full-featured platform for browsing, comparing, and reviewing mobile devices and gadgets. It provides detailed specifications, pricing information, user reviews, video demonstrations, and company brand information all in one place.

## ✨ Features

- **Device Catalog**: Extensive database of mobile devices with detailed specifications
- **Product Comparison**: Compare multiple devices side-by-side
- **Specifications Management**: Comprehensive device specs including display, performance, battery, and more
- **Pricing & Deals**: Track device prices across multiple stores with historical price data
- **User Reviews & Ratings**: Community-driven reviews and star ratings
- **Video Integration**: Embedded device demonstration and review videos
- **Admin Dashboard**: Comprehensive admin panel for managing content
- **SEO Optimization**: Meta tags, sitemaps, structured data, and schema markup
- **Multi-currency Support**: Display prices in different currencies
- **Device Variants**: Handle different color, storage, and region variants
- **Contact System**: User contact messages and inquiries
- **Favorites System**: Bookmark and save favorite devices

## 💻 Tech Stack

- **Backend**: [Laravel](https://laravel.com) - Modern PHP framework
- **Frontend**: Laravel Blade templates, Vite for asset bundling
- **Database**: MySQL/PostgreSQL with Eloquent ORM
- **Testing**: Pest PHP for unit and feature tests
- **PHP**: 8.2+

## 🛠️ Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or PostgreSQL
- Node.js & npm (for frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/gsmarena-clone.git
   cd gsmarena-clone
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Create environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Update `.env` with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gsmarena_clone
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database** (optional)
   ```bash
   php artisan db:seed
   ```

8. **Build assets**
   ```bash
   npm run dev
   ```

## 🚀 Running the Application

### Development Server

Start the Laravel development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Build Assets (Production)

```bash
npm run build
```

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/      # Application controllers
│   ├── Middleware/       # Custom middleware
│   └── View/            # View composers
├── Models/              # Eloquent models
│   ├── Device.php       # Main device model
│   ├── DeviceSpec*.php  # Device specification models
│   ├── Review.php       # Review model
│   ├── Deal.php         # Pricing and deals
│   ├── User.php         # User model
│   └── ...
├── Services/            # Business logic services
├── Helpers/             # Helper functions and utilities
├── Providers/           # Service providers
└── Traits/              # Reusable trait classes

config/
├── app.php              # Application configuration
├── database.php         # Database configuration
├── services.php         # Third-party services
└── ...

database/
├── migrations/          # Database schema migrations
├── factories/           # Model factories for testing
└── seeders/            # Database seeders

resources/
├── views/              # Blade templates
├── js/                 # JavaScript files
└── css/                # Stylesheets

tests/
├── Feature/            # Feature tests
└── Unit/               # Unit tests
```

## 🗄️ Key Models & Relationships

- **Device**: Main product model with variants and specifications
- **DeviceSpec**: Detailed specifications grouped by category
- **Review**: User reviews and ratings
- **DeviceOffer**: Pricing information from various stores
- **Deal**: Special pricing or promotions
- **Brand**: Manufacturer information
- **User**: Application users with favorites and reviews
- **Video**: Device demonstration and review videos

## 🔐 Authorization & Permissions

The project uses Laravel's authorization system with custom permission roles for:
- Admin users (full access)
- Content managers (manage devices and reviews)
- Regular users (browse, compare, and review)

## 🌍 SEO Features

- Dynamic meta tags and Open Graph support
- XML sitemaps
- Schema markup for structured data
- SEO-friendly URL slugs
- Meta description management
- Canonical URLs

## 📝 Database Migrations

Key database tables are managed through Laravel migrations:
- Devices and variants
- Specifications and categories
- Users and authentication
- Reviews and comments
- Pricing and offer history
- Media and images

Run migrations with:
```bash
php artisan migrate
```

## 🧪 Testing

Run the test suite:
```bash
./vendor/bin/pest
```

Run with coverage:
```bash
./vendor/bin/pest --coverage
```

## 📚 Artisan Commands

The project includes custom Artisan commands. View all available commands:
```bash
php artisan
```

## 🔧 Configuration

Key configuration files:
- `.env` - Environment variables
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/filesystems.php` - File storage configuration

## 🐛 Troubleshooting

### Storage Permissions
Ensure the `storage` directory is writable:
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please follow the Laravel contribution guidelines and ensure all tests pass before submitting a pull request.

## 📞 Support

For issues and questions, please open an issue in the GitHub repository.
