# Myforexcart Trading Portal - PHP Version

## Overview
Complete PHP-based trading admin portal with the same design and functionality as the Next.js version. Built with pure PHP, featuring secure authentication, real-time AJAX updates, and JSON-based data storage.

## Features
- ✅ **Secure Authentication** - PHP session-based login with CSRF protection
- ✅ **Trading Dashboard** - Real-time metrics with editable values
- ✅ **Account Management** - Live accounts table with CRUD operations
- ✅ **AJAX Updates** - Dynamic content updates without page refresh
- ✅ **Responsive Design** - Mobile-friendly interface
- ✅ **Data Persistence** - JSON file-based storage system
- ✅ **Security Features** - Input validation, session timeout, login attempts

## Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx/IIS)
- Write permissions for data directory

## Installation

### 1. Upload Files
Upload all files to your web server directory.

### 2. Set Permissions
```bash
chmod 755 php-version/
chmod 777 php-version/data/
```

### 3. Access Application
Visit: `http://your-domain.com/php-version/`

## Default Credentials
- **Username:** admin
- **Password:** Access@myfx

## File Structure
```
php-version/
├── index.php              # Home page
├── login.php              # Login page
├── logout.php             # Logout handler
├── dashboard.php          # Main dashboard
├── config.php             # Configuration
├── .htaccess              # Apache configuration
├── includes/
│   ├── header.php         # Common header
│   ├── footer.php         # Common footer
│   └── auth.php           # Authentication classes
├── ajax/
│   ├── get_metric.php     # Get metric data
│   ├── save_metric.php    # Save metric data
│   └── get_accounts.php   # Get accounts data
├── data/                  # Data storage (auto-created)
│   ├── metrics.json       # Trading metrics
│   ├── accounts.json      # Live accounts
│   └── users.json         # User data
└── assets/
    └── logo.png           # Site logo
```

## Configuration

### Database-Free Design
This application uses JSON files for data storage, making it:
- Easy to deploy on any PHP hosting
- No database setup required
- Portable and lightweight
- Perfect for small to medium deployments

### Security Settings
Edit `config.php` to customize:
- Session timeout duration
- Login attempt limits
- CSRF token settings
- File paths and permissions

## Usage

### 1. Login
- Access the login page
- Use demo credentials or create new users
- Secure session management with timeout

### 2. Dashboard
- View real-time trading metrics
- Edit metric values with AJAX updates
- Monitor live trading accounts
- Quick statistics overview

### 3. Data Management
- All data stored in JSON files
- Automatic backups on changes
- Easy export/import capabilities

## AJAX Endpoints

### GET /ajax/get_metric.php?key=metric_name
Get specific metric data
```json
{
  "success": true,
  "metric": {
    "value": 2500000,
    "percentage": 12.5,
    "trend": "up"
  }
}
```

### POST /ajax/save_metric.php
Save metric data
```json
{
  "key": "trading_volume",
  "value": 2500000,
  "percentage": 12.5,
  "trend": "up"
}
```

### GET /ajax/get_accounts.php
Get all accounts data
```json
{
  "success": true,
  "accounts": [...],
  "total_accounts": 5,
  "total_balance": 393931.50,
  "active_accounts": 4
}
```

## Customization

### Adding New Metrics
1. Edit `config.php` default metrics
2. Update dashboard display
3. Add AJAX handlers if needed

### Styling Changes
- All CSS is inline in `includes/header.php`
- Modify gradient colors and styling
- Responsive design included

### New Features
- Add new AJAX endpoints in `/ajax/`
- Extend DataManager class in `includes/auth.php`
- Add new pages following existing structure

## Security Features

### Authentication
- Password hashing with PHP `password_hash()`
- Session-based authentication
- Session timeout protection
- Login attempt limiting

### CSRF Protection
- CSRF tokens on all forms
- Token validation on submissions
- Secure token generation

### Input Validation
- All user inputs sanitized
- JSON validation for AJAX requests
- SQL injection prevention (no SQL used)
- XSS protection with `htmlspecialchars()`

### File Security
- .htaccess protection for sensitive files
- Data directory access restrictions
- Secure file permissions

## Troubleshooting

### Permission Issues
```bash
chmod 777 data/
chown -R www-data:www-data php-version/
```

### Session Issues
- Check PHP session configuration
- Verify write permissions for session files
- Check session.cookie_secure settings

### AJAX Errors
- Verify authentication before AJAX calls
- Check Content-Type headers
- Enable PHP error reporting for debugging

## Deployment

### Shared Hosting
- Upload files via FTP/cPanel
- No database setup required
- Works on most PHP hosting providers

### VPS/Dedicated Server
- Use Apache/Nginx
- Configure PHP-FPM if needed
- Set up SSL certificate

### Docker
- PHP 8+ with Apache
- Mount data directory as volume
- Configure environment variables

## Backup

### Manual Backup
Copy the entire `data/` directory to backup all user data and settings.

### Automated Backup
```bash
#!/bin/bash
tar -czf backup-$(date +%Y%m%d).tar.gz data/
```

## Support
- Check file permissions if issues occur
- Enable PHP error reporting for debugging
- Review `.htaccess` configuration for Apache
- Ensure PHP version compatibility

This PHP version provides identical functionality to the Next.js version while being deployable on any standard PHP hosting environment.