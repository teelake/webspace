# Production Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Database Configuration
- [x] Database credentials updated in `config/database.php`
- [x] Database name: `u232647434_webspace`
- [x] Username: `u232647434_web_user`
- [x] Host: `localhost` (standard for shared hosting)

### 2. Site Configuration
- [x] Error reporting disabled for production
- [x] Error logging enabled
- [x] Site URL auto-detects from server
- [x] Timezone set to Africa/Lagos

### 3. File Permissions
Ensure proper file permissions:
```bash
# Files
find . -type f -exec chmod 644 {} \;

# Directories
find . -type d -exec chmod 755 {} \;

# Uploads directory (writable)
chmod 755 uploads/
chmod 755 uploads/*/
```

### 4. Database Import
1. Access phpMyAdmin or MySQL client
2. Select database: `u232647434_webspace`
3. Import `database/schema.sql`
4. Verify all tables are created

### 5. Required Directories
Create these directories if they don't exist:
```bash
mkdir -p uploads/services
mkdir -p uploads/portfolio
mkdir -p uploads/blog
mkdir -p uploads/training
mkdir -p uploads/partners
mkdir -p uploads/general
mkdir -p logs
chmod 755 uploads/* logs
```

### 6. Assets
Ensure all assets are uploaded:
- Logo files in `assets/img/logo/`
- Favicon in `assets/img/logo/`
- Any custom images

### 7. Security
- [x] Admin path configured: `cms-panel-2024`
- [ ] Change default admin password
- [ ] Enable HTTPS/SSL
- [ ] Verify .htaccess is working
- [ ] Check file permissions

### 8. Testing
Test the following:
- [ ] Homepage loads correctly
- [ ] All pages accessible
- [ ] Admin login works
- [ ] Contact form submits
- [ ] Image uploads work
- [ ] Database connections work
- [ ] Clean URLs work (no .php extensions)

## 🚀 Deployment Steps

### Step 1: Upload Files
Upload all files to your web server via FTP/SFTP:
- All PHP files
- Assets folder
- Config files
- .htaccess

### Step 2: Database Setup
1. Import `database/schema.sql` into `u232647434_webspace`
2. Verify all tables are created
3. Test database connection

### Step 3: Configure Server
1. Ensure Apache mod_rewrite is enabled
2. Verify .htaccess is being read
3. Check PHP version (7.4+ required)

### Step 4: Set Permissions
```bash
# Uploads directory (must be writable)
chmod 755 uploads/
chmod 755 uploads/*/

# Logs directory
chmod 755 logs/

# Config files (readable only)
chmod 644 config/*.php
```

### Step 5: Test
1. Visit your domain
2. Test all pages
3. Test admin login
4. Test contact form
5. Test image uploads

## 🔐 Security Checklist

- [x] Error display disabled
- [x] Error logging enabled
- [x] Admin path obfuscated
- [ ] Default password changed
- [ ] HTTPS enabled
- [ ] File permissions set correctly
- [ ] .htaccess security headers enabled

## 📧 Email Configuration

The contact form uses PHP's `mail()` function. For production, consider:
1. Using PHPMailer with SMTP
2. Configuring SMTP settings
3. Testing email delivery

## 🔍 Post-Deployment

### Monitor
- Check error logs: `logs/php-errors.log`
- Monitor server logs
- Check database connections
- Verify all functionality

### Backup
- Set up regular database backups
- Backup uploads directory
- Keep configuration files backed up

## 🆘 Troubleshooting

### Database Connection Issues
- Verify credentials in `config/database.php`
- Check database name, username, password
- Ensure database exists
- Check host (usually `localhost` for shared hosting)

### Clean URLs Not Working
- Verify mod_rewrite is enabled
- Check .htaccess is in root directory
- Verify RewriteBase is correct

### Image Upload Issues
- Check uploads directory permissions (755)
- Verify directory exists
- Check PHP upload limits in php.ini

### Admin Login Issues
- Verify admin path in `config/config.php`
- Check database connection
- Verify admin_users table exists
- Check session configuration

## 📞 Support

For issues, check:
1. Error logs: `logs/php-errors.log`
2. Server error logs
3. Database connection status
4. File permissions

---

**Production URL:** https://www.webspace.ng
**Database:** u232647434_webspace
**Admin Path:** /cms-panel-2024/
**Default Admin:** admin / admin123 (CHANGE THIS!)

