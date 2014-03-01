<VirtualHost *:80>
ServerName      dev.com
ServerAdmin     admin@dev.com
ServerSignature email
DocumentRoot    /var/www/dev.com/web
RewriteEngine   On
<Directory /var/www/dev.com/web>
AllowOverride   All
</Directory>
#CustomLog       logs/dev.com.access.log combined
#ErrorLog        logs/dev.com.error.log
</VirtualHost>
