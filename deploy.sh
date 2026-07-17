#!/bin/bash
# ===========================================
# Eventix — DigitalOcean Droplet Setup Script
# ===========================================
# Run this on a fresh Ubuntu 22.04+ Droplet:
#   chmod +x deploy.sh && sudo ./deploy.sh
# ===========================================

set -e

echo ""
echo "=========================================="
echo "  EVENTIX — Server Deployment Script"
echo "=========================================="
echo ""

# -----------------------------------------------
# Step 1: Update system
# -----------------------------------------------
echo "[1/8] Updating system packages..."
apt update && apt upgrade -y

# -----------------------------------------------
# Step 2: Install Apache, MySQL, PHP
# -----------------------------------------------
echo "[2/8] Installing Apache, MySQL, PHP..."
apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-gd php-mbstring php-xml php-curl unzip git

# -----------------------------------------------
# Step 3: Enable Apache mod_rewrite
# -----------------------------------------------
echo "[3/8] Enabling Apache mod_rewrite..."
a2enmod rewrite
systemctl restart apache2

# -----------------------------------------------
# Step 4: Prompt for configuration
# -----------------------------------------------
echo ""
echo "=========================================="
echo "  Database Configuration"
echo "=========================================="

read -p "Enter database name [eventix_db]: " DB_NAME
DB_NAME=${DB_NAME:-eventix_db}

read -p "Enter database username [eventix_user]: " DB_USER
DB_USER=${DB_USER:-eventix_user}

read -sp "Enter database password: " DB_PASS
echo ""

read -p "Enter your GitHub repo URL (e.g. https://github.com/you/eventix.git): " REPO_URL

read -p "Enter your domain name (or leave blank to use IP): " DOMAIN_NAME

# -----------------------------------------------
# Step 5: Set up MySQL database
# -----------------------------------------------
echo "[4/8] Setting up MySQL database..."
mysql -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo "  ✓ Database '$DB_NAME' created"
echo "  ✓ User '$DB_USER' created"

# -----------------------------------------------
# Step 6: Clone repository
# -----------------------------------------------
echo "[5/8] Cloning repository..."
rm -rf /var/www/eventix
git clone "$REPO_URL" /var/www/eventix

# Create upload directories
mkdir -p /var/www/eventix/uploads/profiles
mkdir -p /var/www/eventix/uploads/venues
mkdir -p /var/www/eventix/uploads/payments

# Set permissions
chown -R www-data:www-data /var/www/eventix
chmod -R 775 /var/www/eventix/uploads

# -----------------------------------------------
# Step 7: Create .env file
# -----------------------------------------------
echo "[6/8] Creating .env file..."
cat > /var/www/eventix/.env << EOF
# Database
DB_HOST=localhost
DB_USER=$DB_USER
DB_PASS=$DB_PASS
DB_NAME=$DB_NAME
DB_PORT=3306

# App URL
APP_URL=${DOMAIN_NAME:+https://$DOMAIN_NAME}
EOF
chown www-data:www-data /var/www/eventix/.env
chmod 600 /var/www/eventix/.env
echo "  ✓ .env file created"

# -----------------------------------------------
# Step 8: Import database schema
# -----------------------------------------------
echo "[7/8] Importing database schema..."
mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < /var/www/eventix/database.sql
echo "  ✓ Database schema imported"

# -----------------------------------------------
# Step 9: Configure Apache VirtualHost
# -----------------------------------------------
echo "[8/8] Configuring Apache..."

SERVER_NAME=${DOMAIN_NAME:-$(curl -s ifconfig.me)}

cat > /etc/apache2/sites-available/eventix.conf << EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    ServerName $SERVER_NAME
    DocumentRoot /var/www/eventix

    <Directory /var/www/eventix>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/eventix_error.log
    CustomLog \${APACHE_LOG_DIR}/eventix_access.log combined
</VirtualHost>
EOF

a2ensite eventix.conf
a2dissite 000-default.conf
systemctl restart apache2

echo ""
echo "=========================================="
echo "  ✅ EVENTIX DEPLOYED SUCCESSFULLY!"
echo "=========================================="
echo ""
echo "  Your site is live at:"
if [ -n "$DOMAIN_NAME" ]; then
    echo "    http://$DOMAIN_NAME"
    echo ""
    echo "  To enable HTTPS, run:"
    echo "    sudo apt install certbot python3-certbot-apache -y"
    echo "    sudo certbot --apache -d $DOMAIN_NAME"
else
    echo "    http://$SERVER_NAME"
fi
echo ""
echo "  Database: $DB_NAME"
echo "  DB User:  $DB_USER"
echo "  Files:    /var/www/eventix/"
echo "  Logs:     /var/log/apache2/eventix_error.log"
echo ""
echo "=========================================="
