# 🚀 cPanel Deployment Guide for Myforexcart Trading Dashboard

## 📁 Files to Upload

Upload all these files and folders to your cPanel File Manager:

### Required Files & Folders:
```
📁 Your Domain Root (public_html/)
├── 📄 index.php                 (Main entry point)
├── 📄 login.php                 (Login page)
├── 📄 dashboard.php             (Main dashboard)
├── 📄 logout.php                (Logout handler)
├── 📄 config.php                (Configuration)
├── 📄 .htaccess                 (URL rewriting)
├── 📁 ajax/                     (AJAX endpoints)
│   ├── 📄 get_accounts.php
│   ├── 📄 get_metric.php
│   └── 📄 save_metric.php
├── 📁 assets/                   (Images & static files)
│   └── 📄 myfx.png
├── 📁 data/                     (JSON data storage)
│   ├── 📄 accounts.json
│   ├── 📄 metrics.json
│   ├── 📄 trading-metrics.json
│   └── 📄 users.json
└── 📁 includes/                 (PHP includes)
    ├── 📄 auth.php
    ├── 📄 footer.php
    └── 📄 header.php
```

## 🔧 cPanel Deployment Steps

### Step 1: Access cPanel File Manager
1. Login to your cPanel
2. Navigate to **File Manager**
3. Go to **public_html** folder (your domain root)

### Step 2: Upload Files
1. **Delete default files** (if any):
   - Remove default `index.html` or `index.php`
   - Clear the `public_html` folder

2. **Upload method options**:
   - **Option A**: Upload as ZIP and extract
   - **Option B**: Upload files individually

### Step 3: Set Folder Permissions
Set these folder permissions via cPanel File Manager:
```
📁 data/          → 755 or 777 (writable)
📁 assets/        → 755
📁 ajax/          → 755
📁 includes/      → 755
📄 All .php files → 644
```

### Step 4: Configure PHP Settings (if needed)
1. Go to **Select PHP Version** in cPanel
2. Ensure PHP 7.4+ is selected
3. Enable required extensions:
   - ✅ json
   - ✅ session
   - ✅ fileinfo

### Step 5: Test the Installation
1. Visit your domain: `https://yourdomain.com`
2. Should redirect to login page
3. Login with: **admin** / **Access@myfx**
4. Verify dashboard loads correctly

## 🔐 Security Considerations

### Important Security Settings:
1. **Change default credentials** in `config.php`:
   ```php
   define('ADMIN_USERNAME', 'your_new_username');
   define('ADMIN_PASSWORD', 'your_secure_password');
   ```

2. **Protect data folder** - Add to `.htaccess`:
   ```apache
   # Protect data directory
   <Directory "data">
       Order deny,allow
       Deny from all
   </Directory>
   ```

3. **SSL Certificate**: Ensure your domain has SSL enabled

## 🌐 Domain Configuration

### For Main Domain:
- Upload to: `/public_html/`
- Access via: `https://yourdomain.com`

### For Subdomain:
- Create subdomain in cPanel: `trading.yourdomain.com`
- Upload to: `/public_html/trading/`
- Access via: `https://trading.yourdomain.com`

### For Subdirectory:
- Upload to: `/public_html/trading/`
- Access via: `https://yourdomain.com/trading/`

## 🔍 Troubleshooting

### Common Issues:

1. **500 Internal Server Error**:
   - Check file permissions
   - Verify PHP version compatibility
   - Check error logs in cPanel

2. **Login not working**:
   - Verify session support is enabled
   - Check file permissions on `data/` folder
   - Clear browser cache

3. **Data not saving**:
   - Set `data/` folder permission to 755 or 777
   - Check PHP error logs

4. **Images not loading**:
   - Verify `assets/` folder uploaded correctly
   - Check file paths are correct

## 📊 Post-Deployment Checklist

- [ ] Site loads without errors
- [ ] Login system works
- [ ] Dashboard displays correctly
- [ ] Metric editing and saving works
- [ ] Logo displays properly
- [ ] All buttons function correctly
- [ ] SSL certificate active
- [ ] Default credentials changed

## 🎯 Quick Deployment Commands

If you have SSH access, you can use these commands:
```bash
# Navigate to web root
cd /home/username/public_html

# Upload and extract (if using ZIP)
unzip trading-dashboard.zip

# Set permissions
chmod 755 data/
chmod 644 *.php
```

## 📞 Support

For issues:
1. Check cPanel error logs
2. Verify PHP version (7.4+)
3. Ensure all files uploaded correctly
4. Test with different browsers

---
**🎉 Your Myforexcart Trading Dashboard is ready for production!**