<VirtualHost *:80>
ServerName      www.dev.com
Redirect permanent / http://dev.com/
</VirtualHost>
