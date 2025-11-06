# Security Best Practices

## Admin Panel Security

### 1. Rename Admin Folder

**IMPORTANT:** The admin folder has been configured to use a configurable path for security.

**Steps to rename:**

1. **Update the configuration** in `config/config.php`:
   ```php
   define('ADMIN_PATH', 'cms-panel-2024'); // Change to your preferred name
   ```

2. **Rename the folder** from `admin` to your chosen name (e.g., `cms-panel-2024`)

3. **Update all admin file references** - A helper function `adminUrl()` is available in `includes/functions.php` to generate admin URLs dynamically.

**Recommended folder names:**
- `cms-panel-2024`
- `dashboard-x7k9`
- `manage-system`
- `control-panel-2024`
- Or any random string like `x7k9p2m`

### 2. Security Features Implemented

✅ **Rate Limiting**: Maximum 5 failed login attempts per 15 minutes
✅ **Login Attempt Logging**: All login attempts are logged with IP addresses
✅ **Session Management**: Secure session handling with timeout
✅ **Password Hashing**: Using PHP's `password_hash()` with bcrypt
✅ **Input Sanitization**: All user inputs are sanitized
✅ **Prepared Statements**: SQL injection protection via PDO prepared statements

### 3. Additional Security Recommendations

**For Production:**

1. **Change Default Password**
   ```sql
   UPDATE admin_users SET password = '$2y$10$...' WHERE username = 'admin';
   ```
   Use PHP's `password_hash()` to generate new hash.

2. **Enable HTTPS**: Always use SSL/TLS in production

3. **IP Whitelisting** (Optional): Restrict admin access to specific IPs
   ```php
   $allowed_ips = ['your.ip.address'];
   if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
       die('Access denied');
   }
   ```

4. **Two-Factor Authentication**: Consider implementing 2FA for admin accounts

5. **Regular Updates**: Keep PHP and MySQL updated

6. **File Permissions**: Set proper file permissions (644 for files, 755 for directories)

7. **Hide PHP Version**: Add to `.htaccess`:
   ```apache
   ServerSignature Off
   ```

8. **Disable Directory Listing**: Already configured in `.htaccess`

### 4. Database Security

- Use strong database passwords
- Limit database user privileges
- Regular database backups
- Keep MySQL updated

### 5. Monitoring

- Monitor `login_attempts` table for suspicious activity
- Set up alerts for multiple failed login attempts
- Regular security audits

---

**Remember:** Security through obscurity (renaming admin folder) is just one layer. Always implement multiple security layers for enterprise-grade protection.

