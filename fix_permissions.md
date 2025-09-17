# OpenEClass Permission Fix

## Problem Identified
The CDM import and course creation is failing because:
- Web server process runs as user `www-data`
- Directories `/var/www/html/courses` and `/var/www/html/video` are owned by user `stav`
- `www-data` has no write permissions to these directories

## Solution
To fix the CDM import and course creation, you need to grant write permissions to the web server. Here are the options:

### Option 1: Change ownership to www-data (Recommended)
```bash
sudo chown -R www-data:www-data /var/www/html/courses /var/www/html/video
```

### Option 2: Add www-data to stav group and give group write permissions
```bash
sudo usermod -a -G stav www-data
sudo chgrp -R stav /var/www/html/courses /var/www/html/video
sudo chmod -R g+w /var/www/html/courses /var/www/html/video
sudo systemctl restart apache2  # Restart to pick up new group membership
```

### Option 3: Use ACLs (if available)
```bash
sudo setfacl -R -m u:www-data:rwx /var/www/html/courses /var/www/html/video
sudo setfacl -R -d -m u:www-data:rwx /var/www/html/courses /var/www/html/video
```

## Verification
After applying the fix, test with:
```bash
curl -X POST -d "test_course=1" http://localhost/debug_create_course_detailed.php
```

The output should show successful course creation instead of "❌ create_course() returned false".

## Next Steps
Once permissions are fixed:
1. Test CDM import functionality
2. Verify course creation works from the web interface
3. Test the enhanced CDM import script with complete metadata preservation