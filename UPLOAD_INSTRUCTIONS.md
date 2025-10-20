# MyFX Trading Dashboard - File Upload Instructions

## 🚀 Quick Deployment Methods

### Option 1: Using cPanel File Manager (Easiest)

1. **Access cPanel** on your hosting provider
2. **Open File Manager**
3. **Navigate to public_html** (or your domain's folder)
4. **Create folder** named `myfx`
5. **Upload all files** from your local `myfx.php` folder
6. **Extract** if uploaded as ZIP
7. **Set permissions**:
   - Folders: 755
   - Files: 644
   - Data folder: 777

### Option 2: Using FTP/SFTP (FileZilla)

1. **Download FileZilla** (free FTP client)
2. **Connect to your server**:
   - Host: your-server-ip or yourdomain.com
   - Username: your-username
   - Password: your-password
   - Port: 21 (FTP) or 22 (SFTP)
3. **Navigate to** `/var/www/html/` or `/public_html/`
4. **Create folder** `myfx`
5. **Upload all files** from local folder
6. **Set permissions** via right-click → File Permissions

### Option 3: Command Line (SSH)

```bash
# Connect to your server
ssh username@your-server-ip

# Navigate to web directory
cd /var/www/html

# Create project directory
sudo mkdir myfx
cd myfx

# Upload files (use one of these methods):

# Method A: Using SCP from your local machine
scp -r "e:\Pydart Projects\myfx.php\*" username@your-server-ip:/var/www/html/myfx/

# Method B: Using rsync from your local machine
rsync -avz "e:\Pydart Projects\myfx.php/" username@your-server-ip:/var/www/html/myfx/

# Method C: Using wget (if files are on GitHub)
wget -O myfx.zip "https://github.com/yourusername/myfx-dashboard/archive/main.zip"
unzip myfx.zip
mv myfx-dashboard-main/* .
rm -rf myfx-dashboard-main myfx.zip

# Set proper permissions
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod 777 data/
sudo chmod 666 data/*.json
```

### Option 4: Using Git (Recommended for updates)

```bash
# On your server
cd /var/www/html
sudo git clone https://github.com/yourusername/myfx-dashboard.git myfx
cd myfx

# Set permissions
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod 777 data/

# For future updates
git pull origin main
```

## 📁 Essential Files to Upload

Make sure these files are uploaded:

### Core Files:
- `index.php`
- `login.php`
- `logout.php`
- `dashboard.php`
- `super-admin-dashboard.php`
- `config.php` (or rename config.production.php)

### Directories:
- `includes/` (all files)
- `ajax/` (all files)
- `data/` (all files)
- `assets/` (all files)
- `database/` (optional, for MySQL)

### Configuration:
- `.htaccess`
- `deploy.sh`
- `DEPLOYMENT_GUIDE.md`

## ⚙️ Post-Upload Configuration

### 1. Update config.php
```bash
# Edit configuration file
nano /var/www/html/myfx/config.php
```

Update these settings:
```php
// Production settings
define('DEVELOPMENT', false);
define('BASE_URL', 'https://yourdomain.com/myfx');

// Database (if using MySQL)
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_secure_password');

// Security
define('ADMIN_PASSWORD', 'your_new_secure_password');
```

### 2. Set File Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/html/myfx

# Set directory permissions
sudo find /var/www/html/myfx -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/html/myfx -type f -exec chmod 644 {} \;

# Make data directory writable
sudo chmod 777 /var/www/html/myfx/data/
sudo chmod 666 /var/www/html/myfx/data/*.json
```

### 3. Test the Installation

Visit your website:
- `https://yourdomain.com/myfx`

Login with:
- **Admin**: username: `admin`, password: `Access@myfx`
- **Super Admin**: username: `superadmin`, password: `Access@myfx`

## 🔒 Security Checklist

### After Upload:
- [ ] Change default passwords
- [ ] Set `DEVELOPMENT = false` in config.php
- [ ] Verify .htaccess is working
- [ ] Test file permissions
- [ ] Check data/ directory is not publicly accessible
- [ ] Verify SSL certificate is working
- [ ] Test all functionality

### Security URLs to Test:
- `https://yourdomain.com/myfx/data/` (should be blocked)
- `https://yourdomain.com/myfx/includes/` (should be blocked)
- `https://yourdomain.com/myfx/config.php` (should be blocked)

## 🚨 Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check .htaccess syntax
   - Verify file permissions
   - Check Apache error logs

2. **Permission Denied**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/myfx
   sudo chmod 777 /var/www/html/myfx/data
   ```

3. **Database Connection Failed**
   - Verify database credentials
   - Check if MySQL is running
   - Test database connection

4. **SSL Issues**
   ```bash
   # Get SSL certificate
   sudo certbot --apache -d yourdomain.com
   ```

5. **File Not Found**
   - Check Apache virtual host configuration
   - Verify document root path

## 📞 Support

If you encounter issues:
1. Check server error logs: `/var/log/apache2/error.log`
2. Check application logs: `/var/www/html/myfx/logs/`
3. Test with browser developer tools
4. Verify all files are uploaded correctly

Your MyFX Trading Dashboard should now be live and accessible!