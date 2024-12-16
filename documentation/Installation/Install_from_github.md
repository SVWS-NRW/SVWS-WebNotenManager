```bash
!/bin/bash
#
# Wenom Installation auf Debian 12 - hier bitte Daten individuell anpassen:
#
########################## IHRE DATEN ###################
SCHULNUMMER=123456 					#
FQDN=yourWenomDomain.de					#	
MARIADB_ROOT_PW=YourRootPassword			#	
							#
MARIADB_SCHEMA=wenom					#
MARIADB_USER=wenom					#
WENOM_DB_PW=YourWenomPassword				#
							#
WENOM_TECH_ADMIN=YourUser@YourDomain.de			#
WENOM_TECH_ADMIN_PW=YourTechadminPW			#
#########################################################
#
#
#
#						
##################### DATEN - INSTALLATION ######################################
NODE_MAJOR=20									#
PHPVERSION=8.2									#
										#
INSTALLPATH=/app/webnotenmanager						#
url=https://github.com/SVWS-NRW/SVWS-WebNotenManager/releases/tag/v1.0.2.tar.gz	#
										#	
#################################################################################
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
sed -i "s|DocumentRoot.*$|DocumentRoot ${INSTALLPATH}/public|" /etc/apache2/sites-available/000-default.conf
sed -i "s|DocumentRoot.*$|DocumentRoot ${INSTALLPATH}/public|" /etc/apache2/sites-available/000-default-le-ssl.conf
sed -i "s|DocumentRoot.*$|DocumentRoot ${INSTALLPATH}/public|" /etc/apache2/sites-available/default-ssl.conf
#
echo "
<Directory /app/${INSTALLPATH}/public/>
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
apt install nodejs -y
node -v
#
#
### php 8.2, libapache2-mod-fcgid, apache2 vorbereiten
#
a2dismod mpm_prefork && a2enmod mpm_event
wget -qO /etc/apt/keyrings/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb [signed-by=/etc/apt/keyrings/php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) bookworm main" | tee /etc/apt/sources.list.d/php.list
apt install php libapache2-mod-fcgid -y
apt install php-{cli,fpm,curl,gd,mbstring,soap,bcmath,tokenizer,xml,xmlrpc,zip,mysql,pdo} -y
#
#
### Konfiguriere Apache2, um PHP ueber FastCGI zu verwenden
#
a2enmod proxy_fcgi setenvif
a2enconf $PHPVERSION-fpm
systemctl restart apache2
systemctl status apache2 --no-pager
#
echo "<?php phpinfo(); ?>" | tee /${INSTALLPATH}/public/info.php
#
#
### composer Installieren
#
apt install composer
composer --version
#
#
### Mariadb installieren
#
apt install mariadb-server -y
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
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '$MARIADB_PW'; FLUSH PRIVILEGES;"
#
#
### WENOM installieren
#
cd ~
wget $url
#
filename=$(basename "$url")
tar -xvf $filename
dirname=$(tar -tf $filename | head -1 | cut -f1 -d"/")
cp -a $dirname/. $INSTALLPATH
rm $filename
#
cd $INSTALLPATH
cp .env.example .env
#
composer install
php artisan key:generate
php artisan storage:link
npm install
npm run build
#
php artisan app:install
php artisan migrate:fresh --force
php artisan create:admin-user --user=$WENOM_TECH_ADMIN --password=$WENOM_TECH_ADMIN_PW
php artisan passport:install --force
#
### Neuen User fuer den Dienst einrichten
#
useradd -m -G users -s /bin/bash wenom
mkdir -p ${INSTALLPATH}/public
chmod -R 777 $INSTALLPATH
chown -R wenom:wenom $INSTALLPATH
#
echo "Bitte die Einstellungen in der .env überarbeiten!"
```