```bash
#!/bin/bash
#
# Wenom Installation auf Debian 12 - hier bitte Daten individuell anpassen:
#
########################## IHRE DATEN ###################
SCHULNUMMER=123456
FQDN=yourWenomDomain_or_IP.de
MARIADB_ROOT_PW=YourRootPassword
MARIADB_SCHEMA=wenom
MARIADB_USER=wenom
WENOM_DB_PW=YourWenomPassword
# technical Adminuser
WENOM_TECH_ADMIN=YourUser@YourDomain.de
WENOM_TECH_ADMIN_PW=YourTechadminPW
# mail - smtp Einstellungen
MAIL_HOST=YourMailHost.de
MAIL_PORT=465
MAIL_USERNAME=YourUsername
MAIL_PASSWORD=YourPassword
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@example.com"
#########################################################
#
#
##################### DATEN - INSTALLATION ######################################
WENOMVERSION=1.0.60
NODE_MAJOR=20
PHPVERSION=8.2
INSTALLPATH=/app/webnotenmanager
#################################################################################
#
URL=https://github.com/SVWS-NRW/SVWS-WebNotenManager/archive/refs/tags/v${WENOMVERSION}.tar.gz
export COMPOSER_ALLOW_SUPERUSER=1
#
#
### Software aktualisieren und installieren
#
apt update && apt upgrade -y
apt install -y curl zip dnsutils nmap net-tools nano mc git ca-certificates gnupg2 apache2 lsb-release apt-transport-https ca-certificates
#
#
### Apache2 konfigurieren
#
#
### DocumentRoot anpassen
mkdir -p ${INSTALLPATH}/public
sed -i "s|DocumentRoot.*$|DocumentRoot ${INSTALLPATH}/public|" /etc/apache2/sites-available/000-default.conf
sed -i "s|DocumentRoot.*$|DocumentRoot ${INSTALLPATH}/public|" /etc/apache2/sites-available/default-ssl.conf
#
echo "
<Directory ${INSTALLPATH}/public/>
   Options Indexes FollowSymLinks
   AllowOverride All
   Require all granted
</Directory>" >> /etc/apache2/apache2.conf 
#
#
### Apache2 neustarten
#
a2enmod ssl
a2enmod rewrite
a2ensite default-ssl.conf
systemctl restart apache2
systemctl status apache2 --no-pager
#
#
### nodejs aus dem node-source-Repository installieren
#
mkdir -p /etc/apt/keyrings
curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
apt update
apt install -y nodejs 
node -v
#
#
### php 8.2, libapache2-mod-fcgid, apache2 vorbereiten
#
a2dismod mpm_prefork && a2enmod mpm_event
wget -qO /etc/apt/keyrings/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb [signed-by=/etc/apt/keyrings/php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list
apt update
apt install -y php libapache2-mod-fcgid 
apt install -y php-{cli,fpm,curl,gd,mbstring,soap,bcmath,tokenizer,xml,xmlrpc,zip,mysql,pdo} 
#
#
### Konfiguriere Apache2, um PHP ueber FastCGI zu verwenden
#
a2enmod proxy_fcgi setenvif
a2enconf $PHPVERSION-fpm
systemctl restart apache2
systemctl status apache2 --no-pager
#
# optional: phpinfo anzeigen lassen
# echo "<?php phpinfo(); ?>" | tee /${INSTALLPATH}/public/info.php
#
#
### composer Installieren
#
apt install -y composer
composer --version
#
#
### Mariadb installieren
#
apt install -y mariadb-server 
systemctl start mariadb
systemctl enable mariadb
sleep 5
systemctl status mariadb --no-pager
#
#
### Sicherheitsrelevante Einstellungen in der DB vornehmen
#
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DROP USER IF EXISTS ''@'$(hostname)';"
mysql -e "DROP DATABASE IF EXISTS test;"
#
#
### User (siehe Variablen oben) in der MariaDB anlegen
#
mysql -e "CREATE USER '$MARIADB_USER'@'localhost' IDENTIFIED BY '$WENOM_DB_PW';"
# Datenbank (siehe Variablenoben) anlegen:
mysql -e "CREATE DATABASE $MARIADB_SCHEMA;"
# Rechte setzen
mysql -e "GRANT ALL PRIVILEGES ON $MARIADB_SCHEMA.* TO '$MARIADB_USER'@'localhost';"
# Root-Passwort setzen und Rechte neu laden
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '$MARIADB_ROOT_PW'; FLUSH PRIVILEGES;"
#
#
### WENOM installieren
#
cd ~
wget $URL
#
FILENAME=$(basename "$URL")
tar -xvf $FILENAME
DIRNAME=$(tar -tf $FILENAME | head -1 | cut -f1 -d"/")
cp -a $DIRNAME/. $INSTALLPATH
rm -rf $DIRNAME
#
# Wenom Einstellungen bearbeiten
cd $INSTALLPATH
cp .env.example .env
#
echo ".env wird bearbeitet - wenom settings werden gesetzt "
# 
# Auf Loca Modus stellen - einfachere Eingaben
sed -i "s|APP_ENV=.*$|APP_ENV=local|" .env
#
sed -i "s|APP_URL=.*$|APP_URL=https://${FQDN}|" .env
sed -i "s|DB_USERNAME=.*$|DB_USERNAME=${MARIADB_USER}|" .env
sed -i "s|DB_DATABASE=.*$|DB_DATABASE=${MARIADB_SCHEMA}|" .env
sed -i "s|DB_PASSWORD=.*$|DB_PASSWORD=${WENOM_DB_PW}|" .env
sed -i "s|SCHULNUMMER=.*$|SCHULNUMMER=${SCHULNUMMER}|" .env
#
#
sed -i "s|MAIL_HOST=.*$|MAIL_HOST=${MAIL_HOST}|" .env
sed -i "s|MAIL_PORT=.*$|MAIL_PORT=${MAIL_PORT}|" .env
sed -i "s|MAIL_USERNAME=.*$|MAIL_USERNAME=${MAIL_USERNAME}|" .env
sed -i "s|MAIL_PASSWORD=.*$|MAIL_PASSWORD=${MAIL_PASSWORD}|" .env
sed -i "s|MAIL_ENCRYPTION=.*$|MAIL_ENCRYPTION=${MAIL_ENCRYPTION}|" .env
sed -i "s|MAIL_FROM_ADDRESS=.*$|MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}|" .env
#
echo "Bitte die Einstellungen in der .env im Anschluss kontrollieren!"
#
# Build Prozesse
echo "build Prozesse werden durchgeführt"
composer install
php artisan key:generate --no-interaction
php artisan storage:link --no-interaction
npm install
npm run build
#
# optional - mailkonfig kann auch über die UI erfolgen.
# php artisan app:install
#
php artisan migrate:fresh --force
php artisan create:admin-user --user=$WENOM_TECH_ADMIN --password=$WENOM_TECH_ADMIN_PW
php artisan passport:install --force
#
# Auf Produktion Modus zurückstellen
sed -i "s|APP_ENV=.*$|APP_ENV=production|" .env
#
### Neuen User fuer den Dienst einrichten
#
useradd -m -G users -s /bin/bash wenom
chmod -R 777 $INSTALLPATH
chown -R wenom:wenom $INSTALLPATH
#
echo "Installation abgeschlossen."

```