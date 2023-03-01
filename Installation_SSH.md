# Installation per ssh unter den user root auf Debian 11. 

## Härten des Systems 

### Firewall 

```
apt install ufw

ufw allow https
ufw allow ssh
ufw enable


ufw status
```
### rootlogin



### failtoban



## Grundinstallation

```
apt update && apt upgrade -y  
apt install curl zip dnsutils nmap net-tools nano git 
curl -fsSL https://deb.nodesource.com/setup_16.x | bash 
apt install -y nodejs  
apt install apache2  
apt install php libapache2-mod-php php-mysql php-mbstring php-xmlrpc php-soap php-gd php-xml php-cli php-zip php-bcmath php-tokenizer php-json php-pear
rm /var/www/html/index.html   
echo "<?php phpinfo(); ?>" >> /var/www/html/index.php  #dann mal im Browser nachgucken obs läuft  

```
### Werte bei php anpassen 

In /etc/php/8.1/apache2/php.ini die folgenden Werte eintragen 

```
max_execution_time = 180
max_input_time = 180
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
extension=curl #auskommentieren
extention= bz2 #(pers hier auch auskommentieren

```

### ToDos

+ das könnten wir aber in der nächsten Config wahrscheinlich auch in das .htaccess file schreiben
+ die php Versionim terminal 


## Grundinstallationinstallation prüfen

Insbesondere die Werte für Speicher und die installieren Module kann man hier wiederfinden: 

![image](/uploads/990cd34a05048ae7637f06117a6e54bc/image.png)


## mariadb installieren und DB anlegen
  
```
apt install mariadb-server  
mysql_secure_installation  
```
- mysql root Passwort setzen und merken 
- Unix Socket auth : Y
- nicht noch mal ein root pw setzen: n 
- anonyme User raus: Y
- Disallow root login remotely: Y
- Test datenbanken entfernen: Y


```
mariadb -u root -p   

Create database wenom;  
CREATE USER 'wenom'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON . TO 'wenom'@'localhost';
FLUSH PRIVILEGES;

exit;  
```


## Composer installieren

  
```
curl -sS https://getcomposer.org/installer | php  
mv composer.phar /usr/local/bin/composer  
chmod +x /usr/local/bin/composer  
  
```

# Installation von WeNoM

## Verzeichnis an legen und Download

```
mkdir /app  
cd /app  
git clone https://git.svws-nrw.de/phpprojekt/webnotenmanager  
cd webnotenmanager/  
(composer update)
composer install --ignore-platform-req=ext-curl  
cp .env.example .env  
nano .env 

```
Hier sinnvoll die folgenden Werte eintragen bzw. mit Ihren Angaben ergänzen: 

- APP_URL=http://wenom.svws-nrw.de  
- DB_CONNECTION=mysql  
- DB_HOST=localhost  
- DB_PORT=3306  
- DB_DATABASE=wenom  
- DB_USERNAME=root  
- DB_PASSWORD= IHR_PASSWORT 


```
php artisan key:generate  
php artisan migrate:fresh --seed
npm install  
npm run build
```
(ggf muss man auf eine neue npa version wechseln)
(npm audit fix)


Unter: /etc/apache2/sites-available/000-default.conf  das root Verzeichnis anpassen: 

+ DocumentRoot /app/webnotenmanager/public/

ebenso unter /etc/apache2/sites-available/default-ssl.conf : 

+ DocumentRoot /app/webnotenmanager/public/  

```
chown -R www-data:www-data /app  
chmod -R 777 /app/webnotenmanager/  
/etc/init.d/apache2 restart  
```

Unter /etc/apache2/apache2.config den folgenden Eintrag neu am Ende in des File schreiben: 
```
<Directory /app/webnotenmanager/public/>
   Options Indexes FollowSymLinks
   AllowOverride All
   Require all granted
</Directory>
```

mod_rewrite muss neu geladen werden:

```
a2enmod rewrite
```
# Certificate generieren
```
apt install certbot python3-certbot-apache
certbot --apache -d svws-devops.de

```

entsprechend eine email Adresse angeben und dann testen: 

https://IHR_SERVER/
