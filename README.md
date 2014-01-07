ListApp
===========

Current Release: 1.0

Installation
------------

### Choose a Server
Use your own server or sign up at the RackSpace Cloud
http://www.rackspace.com/cloud/
Create a Cloud Server
Image: Ubuntu 12.04
RAM: 1 GB
- save your password -

When the image is built, you can ssh in at the IP address:
```
ssh root@xxx.xxx.xxx.xxx
```

### Setup Your DNS:
```
listapp.yourdomain.com A record to xxx.xxx.xxx.xxx (IP address)
```

### Configure Your Server
Run the following steps to install LAMP:

```
sudo apt-get update
sudo apt-get install apache2
sudo apt-get install php5 libapache2-mod-php5
sudo apt-get install php5-curl
sudo apt-get install php-pear
sudo pear install Mail
sudo pear install Mail_mime
sudo a2enmod rewrite
```

### Configure your site in apache:
```
cd /etc/apache2/sites-available/
sudo nano listapp
```
```
<VirtualHost *:80>
        ServerName listapp.yourdomain.com
        DocumentRoot /var/www/listapp/app 
   DirectoryIndex index.php
   <Directory /var/www/listapp/app/>
      AllowOverride All
      Order Deny,Allow
      Allow from all
   </Directory>
</VirtualHost>
```

Enable the site
sudo a2ensite listapp

### Install MySQL Server:
```
sudo apt-get install mysql-server php5-mysal
- provide a password for mysql: xxxxxxx
```

### Restart Apache:
```
sudo service apache2 restart
```
### Install the Code

You can use wget or clone it from [github](https://github.com/mailgun/listapp):
```
sudo apt-get install git-core
ssh-keygen -t rsa -C "you@yourdomain.com"
- enter the path for /home/root/.ssh/id_rsa
cd /root/.ssh
more id_rsa.pub
```
- copy key to your forked github project settings deploy key

Test connection to Github
```
ssh -T git@github.com
```
Clone the repository:
```
cd /var/www
git clone git@github.com:newscloud/listapp.git
```

### Install Composer and the Mailgun PHP SDK

```PHP
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Mailgun as a dependency
cd /var/www/listapp/app/protected/vendor
php composer.phar require mailgun/mailgun-php:~1.1
``` 

For shared hosts with SSH access, you might need to run this instead (contact 
your shared host for assistance): 
```
php -d detect_unicode=Off -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
```

### Initialize the database:
```
mysql -uroot -p
create database listapp;

(replace newuser and password below with your own)
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';
FLUSH PRIVILEGES;
```
[see also] (https://www.digitalocean.com/community/articles/how-to-create-a-new-user-and-grant-permissions-in-mysql)

### Build Your Configuration File

Build the configuration file for your application:
- you'll need to sign up for Mailgun.com (at least free level) to get API keys
```
cd /var/www
mkdir secure
cd /var/www/secure
sudo nano config-filtered.ini
```
- copy & paste in your settings using sample-config.ini (in this directory)
- as an example
- but use the mysql password and mailgun keys and your chosen domain
- you can find your mailgun keys here: https://mailgun.com/cp
- set superuser to the email address you want list messages sent from
e.g. superuser = "jeff@newscloud.com"

### Install the Database
Run the database migrations (recommended) or import the .mysql file:
```
cd /var/www/listapp
./app/protected/yiic migrate up
```
Enter your admin user name, email and password
- this is what you'll use to log in to the application

### Try Out Your App
Visit your web page:
```
http://listapp.yourdomain.com
```
Login with your user name and password

You should be ready to go...

Alert: If you run into a problem with directory permissions seeing the bootstrap directory e.g. CException Alias "bootstrap.widgets.TbNavbar" is invalid. Make sure it points to an existing directory or file. You can fix this by granting the Apache user access to all of your directories:
```
sudo chown www-data:www-data -R /var/www/listapp
```
You can post issues on Github:
[Listapp](https://github.com/mailgun/listapp/issues)

Or, as comments on the mailgun blog:
[Tutorial](http://blog.mailgun.com/post/turnkey-mailing-list-applet-using-the-mailgun-php-sdk)

### Contact the author
[Jeff Reifman](http://jeffreifman.com/contact) [Consulting](http://jeffreifman.com/consulting)
