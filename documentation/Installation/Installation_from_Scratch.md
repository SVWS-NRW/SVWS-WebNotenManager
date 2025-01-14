# Installation WeNoM from Scratch

Hier wird die Installation des WebNotenmanagers direkt aus den Githubquellen beschrieben. Dieses Vorgehen ist empfehlenswert, wenn Sie die aktuellste Version bzw. vielmehr die Betaversionen und Feature-Branches ansehen und testen wollen. D.h. Entwickler und frühe Testuser können sich hier gerne bedienen. Für einen stabilen Rollout bzw. den live-Betrieb an Schule sollten andere Installationsmethoden verwendet werden. 

Dieses Dokument beinhaltet 3 Skripte, die je nach Bedarf nacheinander ausgeführt werden können: 
1) Vorbereitung der Serverinfrastruktur
2) Mögliche Sicherheitseinstellungen des Serves
3) Installation des Webnotenmanagers


## Vorbereitung der Serverinfrastruktur

Aktuell wird der Webnotenmanager auf einen Debian12 mit Apache2 und php8.2 Betrieben. Mit dem ersten Skript können Sie eine frische Debian12 Umgebung auf den WebNotemManager vorbereiten. 
Installieren Sie ein Debian12 und loggen Sie sich als root ein. Tragen Sie Ihre Daten in die Variablen ein und führen Sie dieses Skript aus:


```bash 
#!/bin/bash
#
# Ihre Daten, bitte individuell anpassen:
# 
SCHULNUMMER=123456
FQDN=svws-devops.de
MARIADB_PW=YourPassword
WENOM_DB_PW=YourDbUser
# 
WENOM_TECH_ADMIN=YourUser@YourDomain.de
WENOM_TECH_ADMIN_PW=YourTechadminPW
#
NODE_MAJOR=20
GITSOURCE=https://github.com/SVWS-NRW/SVWS-WebNotenManager
INSTALLPATH=/app/SVWS-WebNotenManager
# !BITTE bei diesen Verzeichnisnamen bleiben!
#
#
# Software aktualisieren und nachinstallieren
#
apt update && apt upgrade -y
apt install -y curl zip dnsutils nmap net-tools nano git ca-certificates gnupg apache2

# Apache2 konfigurieren:
#
# Neuen User für den Dienst einrichten
useradd	-m -G users -s /bin/bash wenom
mkdir -p $INSTALLPATH/public
chmod -R 777 $INSTALLPATH
chown -R wenom:wenom /app
#
# innerhalb der folgenden <VirtualHost>-Blocks "DocumentRoot /app/SVWS-WebNotenManager/public/" anpassen
# /etc/apache2/sites-available/000-default.conf     
 sed  -i 's/DocumentRoot.*$/DocumentRoot \/app\/SVWS-WebNotenManager\/public/' /etc/apache2/sites-available/000-default.conf 
# /etc/apache2/sites-available/000-default-le-ssl.conf
 sed  -i 's/DocumentRoot.*$/DocumentRoot \/app\/SVWS-WebNotenManager\/public/' /etc/apache2/sites-available/000-default-le-ssl.conf 
# /etc/apache2/sites-available/default-ssl.conf
 sed  -i 's/DocumentRoot.*$/DocumentRoot \/app\/SVWS-WebNotenManager\/public/' /etc/apache2/sites-available/default-ssl.conf
#
# unter /etc/apache2/apache2.conf den folgenden Eintrag neu am Ende in des File schreiben
# <Directory /app/SVWS-WebNotenManager/public/>
#   Options Indexes FollowSymLinks
#   AllowOverride All
#   Require all granted
# </Directory>
echo "
<Directory /app/SVWS-WebNotenManager/public/>
   Options Indexes FollowSymLinks
   AllowOverride All
   Require all granted
</Directory>" >> /etc/apache2/apache2.conf 
#
# Apache2 neustarten
a2enmod rewrite
systemctl restart apache2
#
# 
# nodejs aus dem node-source-Repository installieren
mkdir -p /etc/apt/keyrings
curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
#
# Alternative:
#curl -fsSL https://deb.nodesource.com/setup_$NODE_MAJOR.x -o nodesource_setup.sh
#bash nodesource_setup.sh
#
apt update
apt install nodejs -y
node -v
#
#
# php 8.2 und libapache2-mod-fcgid Modul für FastCGI installieren, apache2 vorbereiten
#
a2dismod mpm_prefork && a2enmod mpm_event
apt install -y lsb-release apt-transport-https ca-certificates
wget -qO /etc/apt/keyrings/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb [signed-by=/etc/apt/keyrings/php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) bookworm main" | tee /etc/apt/sources.list.d/php.list
apt install php8.2 libapache2-mod-fcgid -y
apt install php8.2-{cli,fpm,curl,gd,mbstring,soap,bcmath,tokenizer,xml,xmlrpc,zip,mysql,pdo} -y
#
#
# Konfiguriere Apache2, um PHP über FastCGI zu verwenden
#
a2enmod proxy_fcgi setenvif
a2enconf php8.2-fpm
systemctl restart apache2
rm /var/www/html/index.html
#
# optional zum Testen
echo "<?php phpinfo(); ?>" | tee /app/SVWS-WebNotenManager/public/info.php
#
# composer Installieren
export COMPOSER_ALLOW_SUPERUSER=1
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
composer --version
#
#
# Mariadb installieren
#
apt install mariadb-server -y
# 
# MariaDB-Dienst starten (überprüfen, ob der Dienst läuft)
systemctl start mariadb
systemctl enable mariadb
#
# Warten, bis der Dienst bereit ist
sleep 5
#
# Sicherheitsrelevante Einstellungen in der DB vornehmen
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DROP USER IF EXISTS ''@'$(hostname)';"
mysql -e "DROP DATABASE IF EXISTS test;"
#
# User "wenom" in der MariaDB anlegen
mysql -e "CREATE USER 'wenom'@'localhost' IDENTIFIED BY '$WENOM_DB_PW';"
# Datenbank "webnotenmanager" anlegen
mysql -e "CREATE DATABASE webnotenmanager;"
# Rechte setzen 
mysql -e "GRANT ALL PRIVILEGES ON webnotenmanager.* TO 'wenom'@'localhost';"
# Root-Passwort setzen und Rechte neu laden
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '$MARIADB_PW'; FLUSH PRIVILEGES;"
```

## Sicherheitseinstellungen

Bitte beachten Sie, dass der Service im Internet läuft und damit geeignete Gegenmaßnahmen 
zu möglichen Hackerangriffen, wie zum Beilspiel ufw, failtoban, no Rootlogin per SSH und 
Weiteres zu ergreifen sind. Ebenso sollte ein Zertifikat eingerichtet werden. 

Ein Vorschlag dies umzusetzen wäre zum Beispiel:  

```bash 
#!/bin/bash
#
# Variablen definieren, bitte individuell anpassen:
# 
# Ihre Daten, bitte individuell anpassen:
# 
SCHULNUMMER=123456
FQDN=svws-devops.de
MARIADB_PW=YourPassword
WENOM_DB_PW=YourDbUser
# 
WENOM_TECH_ADMIN=YourUser@YourDomain.de
WENOM_TECH_ADMIN_PW=YourTechadminPW
#
NODE_MAJOR=20
#
# Firewall
apt install ufw -y
ufw allow https
ufw allow http
ufw allow ssh
ufw --force enable 
ufw status
#
# Fail2Ban
apt install fail2ban -y
cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sed -i 's/backend = auto/backend = systemd/g' /etc/fail2ban/jail.local
systemctl restart fail2ban
systemctl is-enabled fail2ban
systemctl is-active fail2ban
#
# sshguard
apt install sshguard -y
systemctl is-enabled sshguard
systemctl is-active sshguard
#
#
# SSL-Zertifikat einrichten
apt install certbot python3-certbot-apache -y
certbot --apache -n -d $FQDN -m $WENOM_TECH_ADMIN --agree-tos
#
#
# Umleitung über SSL zu erzwingen - Innerhalb von <VirtualHost *:443> folgendes unten ergänzen:
# RewriteEngine On
# RewriteCond %{HTTPS} off
# RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
#
sed '/^<Virt.*/a \\nRewriteEngine On\nRewriteCond %{HTTPS} off\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n\n' /etc/apache2/sites-available/000-default-le-ssl.conf > /etc/apache2/sites-available/000-default-le-ssl.conf.new
mv /etc/apache2/sites-available/000-default-le-ssl.conf.new /etc/apache2/sites-available/000-default-le-ssl.conf
# /etc/apache2/sites-available/000-default-le-ssl.conf
 sed -i 's/DocumentRoot.*$/DocumentRoot \/app\/webnotenmanager\/public/' /etc/apache2/sites-available/000-default-le-ssl.conf 
# /etc/apache2/sites-available/default-ssl.conf
 sed -i 's/DocumentRoot.*$/DocumentRoot \/app\/webnotenmanager\/public/' /etc/apache2/sites-available/default-ssl.conf
#
systemctl reload apache2 
#
# ggf. root login über SSH ausschalten 
# ! Achtung hierbei: 
# Es muss noch user mit potentiellen Root-Rechten im System eingerichtet sein. 
# Bei Debian 12 unkritisch, da standartmäßig ausgeschaltet
```

## WeNoM Installation direkt aus den Sources 

**Wichtig: dieses Skript bezieht sich auf die Konfiguration aus den o. g. Skripten**

```bash 
#!/bin/bash
#
## Ihre Daten, bitte individuell anpassen:
# 
SCHULNUMMER=123456
FQDN=svws-devops.de
MARIADB_PW=YourPassword
WENOM_DB_PW=YourDbUser
# 
WENOM_TECH_ADMIN=YourUser@YourDomain.de
WENOM_TECH_ADMIN_PW=YourTechadminPW
# !Bitte bei diesen Verzeichnamen verbleiben!
#
NODE_MAJOR=20
GITSOURCE=https://github.com/SVWS-NRW/SVWS-WebNotenManager
INSTALLPATH=/app/SVWS-WebNotenManager
#
rm -rf $INSTALLPATH
cd /app
#
# Das GIT-Repository unter dem entsprechenden Verzeichnis /app clonen
git clone $GITSOURCE
#
# Composer ausführen
export COMPOSER_ALLOW_SUPERUSER=1
cd /app/SVWS-WebNotenManager/
composer install
#
# Wenom Konfiguration erstellen
cp .env.example .env
#
# URL anpassen
sed  -i 's/APP_URL.*$/APP_URL=https:\/\/'$FQDN'/' .env
#
# Datenbank zugangsdaten einstellen
sed  -i 's/DB_DATABASE.*$/DB_DATABASE=webnotenmanager/' .env
sed  -i 's/DB_USERNAME.*$/DB_USERNAME=wenom/' .env
sed  -i 's/DB_PASSWORD.*$/DB_PASSWORD='$WENOM_DB_PW'/' .env
#
# schulnummer eintragen 
sed  -i 's/SCHULNUMMER.*$/SCHULNUMMER='$SCHULNUMMER'/' .env
#
# ggf. APP_DEBUG=false auf true setzen (Laravel setzt dann automatisch den LOG_LEVEL auf debug, 
# wenn nichts anderes in der .env Datei angegeben wird. 
# APP_DEBUG auf true setzen
#sed -i 's/^APP_DEBUG=.*/APP_DEBUG=true/' ".env"
#
# Key fürs Backend generieren, Wenom kompilieren, leeres schemata erstellen
php artisan key:generate
npm install
npm run build
# mögliche Workaurounds zur Befüllung der Datenbank zu testzwecken: 
# php artisan migrate:fresh --seed # Erzeugt ein Schema mit einem Adminuser und Settings
php artisan migrate:fresh # Erzeugt ein leeres Schema
# php artisan db:seed --class=JsonImportSeeder # Importiert aus dem Json ins Schema testdaten
php artisan passport:install # Erstellt die Keys fürs OAuth Verfahren
#
chmod -R 777 /app/SVWS-WebNotenManager
#
# Adminuser erstellen:
#  php artisan create:admin-user --user=wenom@gmail.com --password=meinpasswort
php artisan create:admin-user --user=$WENOM_TECH_ADMIN --password=$WENOM_TECH_ADMIN_PW
```