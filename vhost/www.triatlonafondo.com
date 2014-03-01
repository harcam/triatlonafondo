<VirtualHost *:80>
ServerName      www.triatlonafondo.com
ServerAdmin     tritanes@gmail.com
ServerSignature email
DocumentRoot    /var/www/www.triatlonafondo.com/web
RewriteEngine   On
<Directory /var/www/triatlonafondo.com/web>
AllowOverride   All
</Directory>
#CustomLog       logs/triatlonafondo.com.access.log combined
#ErrorLog        logs/triatlonafondo.com.error.log
</VirtualHost>