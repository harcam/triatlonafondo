#!/bin/bash

## Assuming a basic LAMP stack running on Ubuntu

echo -e "\e[1m--- Update Repositories ---\e[0m"
sudo add-apt-repository -y ppa:ondrej/php5-oldstable
sudo apt-get update > /dev/null

echo -e "\e[1m--- Update to PHP5.4 ---\e[0m"
sudo apt-get install -y php5
sudo apt-get install -y php5-json libapache2-mod-php5
sudo service apache2 restart

echo -e "\e[1m--- Install cURL ---\e[0m"
sudo apt-get install -y curl
sudo apt-get install -y libcurl3 php5-curl php5-intl
sudo service apache2 restart

echo -e "\e[1m--- Prepare /var/www ---\e[0m"
sudo adduser $USER www-data
sudo chown -R www-data:www-data /var/www
sudo chmod -R g+rw /var/www

echo -e "\e[1m--- Configure Apache & Virtual Hosts ---\e[0m"
echo - Prepare log files -
sudo mkdir /etc/apache2/logs
sudo chmod 777 -R /etc/apache2/logs

echo - Enable RewriteEngine -
sudo a2enmod rewrite

echo - Enable Virtual Hosts -
sudo a2dissite default
sudo a2dissite 000-default

sudo cp vhost/triatlonafondo.com /etc/apache2/sites-available/triatlonafondo.com
sudo cp vhost/triatlonafondo.com /etc/apache2/sites-available/triatlonafondo.com.conf
sudo cp vhost/www.triatlonafondo.com /etc/apache2/sites-available/www.triatlonafondo.com
sudo cp vhost/www.triatlonafondo.com /etc/apache2/sites-available/www.triatlonafondo.com.conf

sudo a2ensite triatlonafondo.com
sudo a2ensite www.triatlonafondo.com
sudo service apache2 restart

echo - Fix Permissions -
cd ..
sudo chmod 777 -R www
cd www

echo -e "\e[1m--- Install Symfony on triatlonafondo.com ---\e[0m"
cd triatlonafondo.com

echo - Download Composer -
curl -sS https://getcomposer.org/installer | php
php composer.phar config --global discard-changes true

echo - Install Symfony -
php composer.phar install

echo - Clean up installation -
chmod -R 777 app/cache
chmod -R 777 app/logs
chmod -R 777 app/config/parameters.yml

echo - Prepare .htaccess file for dev environment -
cp ../vhost/dev_htaccess web/.htaccess

echo -e "\e[1mAll done.\e[0m"
