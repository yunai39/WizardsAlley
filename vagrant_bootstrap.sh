#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive

mysql_root_password='pwd'

# Update the box
# -----
apt-get update
apt-get install -y python-software-properties
add-apt-repository ppa:ondrej/php5
apt-get update
apt-get upgrade -y


# Apache
# -----
# Install
apt-get install -y apache2
# Allow modification on project files for Apache by changing Apache run user and group
sed -i 's/^export APACHE_RUN_USER=.*$/export APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sed -i 's/^export APACHE_RUN_GROUP=.*$/export APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars
# Disable default site
a2dissite 000-default
# Enable mod_rewrite
#a2enmod rewrite
# Add ServerName to apache conf
echo "ServerName localhost" > /etc/apache2/conf-available/wizardsalley.conf
a2enconf wizardsalley
# Setup hosts file
echo '
<VirtualHost *:80>
  DocumentRoot "/vagrant/web"
  ServerName www.wizards-alley.local
  <Directory "/vagrant/web">
    AllowOverride All
    Require all granted
  </Directory>
  DirectoryIndex app_dev.php
</VirtualHost>' > /etc/apache2/sites-available/wizardsalley.conf
echo '
<VirtualHost *:80>
  DocumentRoot "/usr/share/phpmyadmin"
  ServerName phpmyadmin.local
  <Directory "/usr/share/phpmyadmin">
    AllowOverride All
    Require all granted
  </Directory>
  DirectoryIndex index.php
</VirtualHost>' > /etc/apache2/sites-available/phpmyadmin.conf


# PHP 5.4
# -----
apt-get install -y libapache2-mod-php5
# Command-Line Interpreter
apt-get install -y php5-cli
# MySQL database connections directly from PHP
apt-get install -y php5-mysql
# cURL is a library for getting files from FTP, GOPHER, HTTP server
apt-get install -y php5-curl
# Module for MCrypt functions in PHP
apt-get install -y php5-mcrypt
apt-get install -y php5-intl
# Default timezone configuration
sed -i 's/^;\?date.timezone =.*$/date.timezone = Europe\/Paris/' /etc/php5/apache2/php.ini
sed -i 's/^;\?date.timezone =.*$/date.timezone = Europe\/Paris/' /etc/php5/cli/php.ini

# Mysql
# -----
apt-get install -y mysql-server-5.5
mysqladmin -u root password $mysql_root_password

# PhpMyAdmin
# -----
debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true" |
debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $mysql_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $mysql_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $mysql_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none"
apt-get install -y phpmyadmin


# Enable Apache sites
# -----
# Remove /var/www default
rm -rf /var/www
# Symlink /vagrant to /var/www
ln -fs /vagrant /var/www
# Clear cache and log files
rm -rf /var/www/app/cache/*
rm -rf /var/www/app/logs/*
# update Apache configuration
a2ensite wizardsalley
a2ensite phpmyadmin
service apache2 restart


# Install Composer
# -----
# Install cURL
apt-get install -y curl
# Download and 'install' Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
# Load Composer packages
cd /var/www
composer install


# Generate project DB
# -----
# Set up the database
mysql -u root -p$mysql_root_password <<< "CREATE DATABASE IF NOT EXISTS wizardalley"
# Generate schema
php app/console doctrine:schema:create
# Seeding data
for filename in /var/www/script-sql/*.sql; do
    mysql -u root -p$mysql_root_password wizardalley < $filename
done