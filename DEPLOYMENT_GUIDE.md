# MyFX Trading Dashboard - VPS Deployment Guide

## 🚀 VPS Server Deployment Instructions

### Prerequisites
- VPS server with root access
- Domain name (optional but recommended)
- SSH access to your VPS

## 📋 Step-by-Step Deployment

### 1. Server Setup (Ubuntu/Debian)

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y apache2 php8.1 php8.1-mysql php8.1-curl php8.1-json php8.1-mbstring php8.1-xml php8.1-zip unzip curl git

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

### 2. Upload Files to Server

#### Option A: Using SCP/SFTP
```bash
# From your local machine, upload the project
scp -r "e:\Pydart Projects\myfx.php" username@your-server-ip:/var/www/html/myfx

# Or use FileZilla/WinSCP to upload files
```

#### Option B: Using Git (Recommended)
```bash
# On your VPS server
cd /var/www/html
sudo git clone https://github.com/yourusername/myfx-dashboard.git myfx
# Or upload via FTP/cPanel
```

### 3. Set File Permissions

```bash
# Navigate to project directory
cd /var/www/html/myfx

# Set proper ownership
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Make data directory writable
sudo chmod 777 data/
sudo chmod 666 data/*.json
```

### 4. Configure Apache Virtual Host

Create Apache configuration file:
```bash
sudo nano /etc/apache2/sites-available/myfx.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/myfx
    
    <Directory /var/www/html/myfx>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
    
    # Security headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    
    # Hide sensitive files
    <Files "*.json">
        Require all denied
    </Files>
    
    <Directory /var/www/html/myfx/data>
        Require all denied
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/myfx_error.log
    CustomLog ${APACHE_LOG_DIR}/myfx_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite myfx.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

### 5. SSL Certificate (Free with Let's Encrypt)

```bash
# Install Certbot
sudo apt install snapd
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot

# Create certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Verify auto-renewal
sudo certbot renew --dry-run
```

### 6. MySQL Database Setup (Optional)

If you want to use MySQL instead of file-based storage:

```bash
# Install MySQL
sudo apt install mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE myfx_trading;
CREATE USER 'myfx_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON myfx_trading.* TO 'myfx_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 7. Configure Application

Edit the configuration file:
```bash
sudo nano /var/www/html/myfx/config.php
```

Update database settings if using MySQL:
```php
// For production use
define('DB_HOST', 'localhost');
define('DB_NAME', 'myfx_trading');
define('DB_USER', 'myfx_user');
define('DB_PASS', 'your_secure_password');

// Set development to false
define('DEVELOPMENT', false);
```

### 8. Firewall Configuration

```bash
# Enable UFW firewall
sudo ufw enable

# Allow SSH, HTTP, and HTTPS
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443

# Check status
sudo ufw status
```

### 9. Performance Optimization

#### PHP Configuration
```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

Optimize these settings:
```ini
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
session.gc_maxlifetime = 14400
opcache.enable = 1
opcache.memory_consumption = 128
```

#### Apache Configuration
```bash
sudo nano /etc/apache2/apache2.conf
```

Add performance settings:
```apache
# Enable compression
LoadModule deflate_module modules/mod_deflate.so
<Location />
    SetOutputFilter DEFLATE
    SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png)$ no-gzip dont-vary
</Location>

# Enable caching
LoadModule expires_module modules/mod_expires.so
ExpiresActive On
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
ExpiresByType image/png "access plus 1 month"
ExpiresByType image/jpg "access plus 1 month"
ExpiresByType image/jpeg "access plus 1 month"
```

Restart services:
```bash
sudo systemctl restart apache2
sudo systemctl restart php8.1-fpm
```

### 10. Backup Strategy

Create backup script:
```bash
sudo nano /root/backup_myfx.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/root/backups"
DATE=$(date +%Y%m%d_%H%M%S)
PROJECT_DIR="/var/www/html/myfx"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup files
tar -czf $BACKUP_DIR/myfx_files_$DATE.tar.gz $PROJECT_DIR

# Backup database (if using MySQL)
# mysqldump -u myfx_user -p myfx_trading > $BACKUP_DIR/myfx_db_$DATE.sql

# Keep only last 7 days of backups
find $BACKUP_DIR -name "myfx_*" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and add to cron:
```bash
sudo chmod +x /root/backup_myfx.sh

# Add to crontab (daily backup at 2 AM)
sudo crontab -e
0 2 * * * /root/backup_myfx.sh
```

### 11. Monitoring and Logs

View application logs:
```bash
# Apache logs
sudo tail -f /var/log/apache2/myfx_error.log
sudo tail -f /var/log/apache2/myfx_access.log

# PHP logs
sudo tail -f /var/log/php8.1-fpm.log
```

### 12. Security Hardening

#### Hide Apache version
```bash
sudo nano /etc/apache2/conf-available/security.conf
```

```apache
ServerTokens Prod
ServerSignature Off
```

#### Create .htaccess for additional security
```bash
sudo nano /var/www/html/myfx/.htaccess
```

```apache
# Disable directory browsing
Options -Indexes

# Protect sensitive files
<Files "config.php">
    Require all denied
</Files>

<Files "*.json">
    Require all denied
</Files>

# Force HTTPS (if SSL is configured)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

## 🌐 DNS Configuration

Point your domain to your VPS:

1. **A Record**: `yourdomain.com` → `your-vps-ip`
2. **A Record**: `www.yourdomain.com` → `your-vps-ip`

## ✅ Final Verification

1. **Test the application**: Visit `https://yourdomain.com`
2. **Login with credentials**:
   - Admin: `admin` / `Access@myfx`
   - Super Admin: `superadmin` / `Access@myfx`
3. **Check all functionality**:
   - Dashboard displays correctly
   - Edit functionality works
   - Add new accounts works
   - AJAX requests successful

## 🔧 Troubleshooting

### Common Issues:

1. **Permission Errors**:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/myfx
   sudo chmod 777 /var/www/html/myfx/data
   ```

2. **Apache Not Starting**:
   ```bash
   sudo apache2ctl configtest
   sudo systemctl status apache2
   ```

3. **PHP Errors**:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

4. **Database Connection Issues**:
   - Check MySQL service: `sudo systemctl status mysql`
   - Verify credentials in `config.php`
   - Test connection: `mysql -u myfx_user -p`

## 📱 Mobile Optimization

The dashboard is already responsive, but for better mobile experience:

1. **Test on different devices**
2. **Monitor performance with tools like GTMetrix**
3. **Consider implementing Progressive Web App (PWA) features**

## 🚀 Production Tips

1. **Regular Updates**: Keep PHP and Apache updated
2. **Monitor Resources**: Use tools like `htop`, `iotop`
3. **Log Rotation**: Configure logrotate for application logs
4. **CDN**: Consider using Cloudflare for better performance
5. **Monitoring**: Set up Uptime monitoring

Your MyFX Trading Dashboard is now ready for production use on your VPS server!