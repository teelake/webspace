# Webspace Innovation Hub Limited - Corporate Website with CMS

A complete, multi-page corporate website with an integrated CMS admin backend built with PHP, MySQL, Tailwind CSS, and modern JavaScript.

## 🚀 Features

### Frontend
- **Modern, Responsive Design** - Built with Tailwind CSS v3+
- **SEO Optimized** - Meta tags, OG tags, semantic HTML5
- **Smooth Animations** - AOS (Animate On Scroll) library
- **Clean URLs** - Extensionless URLs using .htaccess
- **Pages**: Home, About, Services, Training, Portfolio, Blog, Contact

### Backend (Admin CMS)
- **Secure Authentication** - Session-based login system
- **Content Management**:
  - Home content management
  - About content management
  - Services (CRUD)
  - Portfolio (CRUD)
  - Blog with WYSIWYG editor (TinyMCE)
  - Training programs (CRUD)
  - Partners management
  - Contact messages viewer
  - Global settings
- **Image Upload** - With validation and management
- **Modern Admin UI** - Tailwind CSS styled dashboard

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- XAMPP, Laragon, or similar local server

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/teelake/webspace.git
cd webspace
```

### 2. Database Setup

1. Open phpMyAdmin (or your MySQL client)
2. Create a new database named `webspace_db`
3. Import the database schema:
   ```bash
   mysql -u root -p webspace_db < database/schema.sql
   ```
   Or import `database/schema.sql` through phpMyAdmin

### 3. Configuration

1. Update database credentials in `config/database.php`:
   ```php
   private $host = 'localhost';
   private $db_name = 'webspace_db';
   private $username = 'root';
   private $password = '';
   ```

2. Update site URL in `config/config.php`:
   ```php
   define('SITE_URL', 'http://localhost/webspace');
   ```

### 4. File Permissions

Ensure the uploads directory is writable:
```bash
mkdir uploads
chmod 755 uploads
```

Create subdirectories:
```bash
mkdir -p uploads/services uploads/portfolio uploads/blog uploads/training uploads/partners uploads/general
chmod 755 uploads/*
```

### 5. Assets

Place your logo files in the `assets/` directory:
- `webspace-logo.png`
- `webspace-logo-renewed-blue.jpg`
- `webspace-favicon.png`

## 🔐 Admin Access

**Default Login Credentials:**
- Username: `admin`
- Password: `admin123`

**⚠️ Important:** Change the default password after first login!

To change password, you can update it directly in the database:
```sql
UPDATE admin_users SET password = '$2y$10$...' WHERE username = 'admin';
```
Use PHP's `password_hash()` function to generate a new hash.

## 📁 Project Structure

```
webspace/
├── admin/              # Admin CMS backend
│   ├── includes/       # Admin header/footer
│   ├── index.php       # Dashboard
│   ├── login.php       # Admin login
│   ├── services.php    # Services management
│   ├── portfolio.php   # Portfolio management
│   ├── blog.php        # Blog management
│   ├── training.php    # Training programs
│   ├── partners.php    # Partners management
│   ├── messages.php    # Contact messages
│   └── settings.php    # Global settings
├── assets/             # Static assets (logos, images)
├── config/             # Configuration files
├── database/           # Database schema
├── includes/           # Frontend includes (header, footer)
├── uploads/            # Uploaded files (images)
├── index.php           # Homepage
├── about.php           # About page
├── services.php        # Services page
├── training.php        # Training page
├── portfolio.php       # Portfolio page
├── blog.php            # Blog listing & single post
├── contact.php         # Contact page
├── .htaccess           # URL rewriting rules
└── README.md           # This file
```

## 🎨 Customization

### Colors
Update Tailwind colors in `includes/header.php`:
```javascript
colors: {
    primary: '#1D4ED8',
    accent: '#10B981',
}
```

### Company Information
Update company details in `config/config.php`:
```php
define('SITE_NAME', 'Webspace Innovation Hub Limited');
define('SITE_TAGLINE', 'Your Complete Digital Partner');
define('SITE_EMAIL', 'hello@webspace.ng');
```

## 📧 Contact Form

The contact form:
- Saves messages to the database
- Sends email notifications to the admin
- Uses PHP's `mail()` function (configure your mail server)

For production, consider using PHPMailer with SMTP.

## 🔒 Security Notes

- Change default admin password
- Use strong passwords
- Keep PHP and MySQL updated
- Configure proper file permissions
- Use HTTPS in production
- Sanitize all user inputs (already implemented)
- Use prepared statements (already implemented)

## 🚀 Deployment

1. Upload all files to your web server
2. Ensure Apache mod_rewrite is enabled
3. Create database and import schema
4. Update configuration files
5. Set proper file permissions
6. Test all functionality

## 📝 License

This project is proprietary software for Webspace Innovation Hub Limited.

## 👥 Support

For support, contact:
- Email: hello@webspace.ng
- Phone: 09133905681, 08137449310

## 🔄 Future Enhancements

- Laravel migration ready
- Additional admin features
- Advanced SEO tools
- Analytics integration
- Multi-language support

---

**Built with ❤️ for Webspace Innovation Hub Limited**

