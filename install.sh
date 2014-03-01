#!/bin/bash
# version 1.5 - 2014.03.2

echo -e "\e[1m--- Update Repositories ---\e[0m"
sudo add-apt-repository -y ppa:ondrej/php5-oldstable
sudo apt-get update > /dev/null

echo -e "\e[1m--- Install Apache2 ---\e[0m"
sudo apt-get install -y apache2
sudo a2enmod rewrite
sudo service apache2 restart

echo -e "\e[1m--- Install PHP5.4 ---\e[0m"
sudo apt-get install -y php5
sudo apt-get install -y php5-json libapache2-mod-php5
sudo service apache2 restart

echo -e "\e[1m--- Install PHP5 MySQL Drivers ---\e[0m"
sudo apt-get install -y php5-mysql

echo -e "\e[1m--- Prepare /var/www ---\e[0m"
sudo adduser $USER www-data
sudo chown -R www-data:www-data /var/www
sudo chmod -R g+rw /var/www

echo -e "\e[1m--- Install MySQL ---\e[0m"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password devroot'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password devroot'
sudo apt-get install -y mysql-server
sudo apt-get install -y php5-mysql

echo -e "\e[1m--- Install cURL ---\e[0m"
sudo apt-get install -y curl
sudo apt-get install -y libcurl3 php5-curl php5-intl
sudo service apache2 restart

echo -e "\e[1m--- Configure Apache & Virtual Hosts ---\e[0m"
echo - Prepare log files -
sudo mkdir /etc/apache2/logs
sudo chmod 777 -R /etc/apache2/logs

echo - Enable Virtual Hosts -
sudo a2dissite default
sudo a2dissite 000-default

sudo cp vhost/dev.com /etc/apache2/sites-available/dev.com
sudo cp vhost/dev.com /etc/apache2/sites-available/dev.com.conf
sudo cp vhost/www.dev.com /etc/apache2/sites-available/www.dev.com
sudo cp vhost/www.dev.com /etc/apache2/sites-available/www.dev.com.conf

sudo a2ensite dev.com
sudo a2ensite www.dev.com
sudo service apache2 restart

echo - Update Hosts file -
sudo chmod 777 /etc/hosts
sudo echo "127.0.0.1 dev.com" >> /etc/hosts
sudo chmod 644 /etc/hosts

echo - Fix Permissions -
cd ..
sudo chmod 777 -R www
cd www

echo -e "\e[1m--- Install Symfony on dev.com ---\e[0m"
mkdir dev.com

echo - Download Composer -
curl -sS https://getcomposer.org/installer | php
php composer.phar config --global discard-changes true

echo - Create Symfony installation -
php composer.phar create-project --no-interaction symfony/framework-standard-edition dev.com/ 2.4.*

echo - Clean up installation -
mv composer.phar dev.com/composer.phar
chmod -R 777 dev.com/app/cache
chmod -R 777 dev.com/app/logs
chmod -R 777 dev.com/app/config/parameters.yml

echo - Prepare .htaccess file for dev environment -
cp vhost/dev_htaccess dev.com/web/.htaccess

echo -e "\e[1mAll done.\e[0m"
