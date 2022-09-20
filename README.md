## Pre-Installation
* Datenbank anlegen
* E-Mail Konto anlegen

### Zip Installation
* Zip-Datei auf den Server hochladen und entzippen
* Das Datenbank mit die *.sql Datei befüllen, welche befindet sich in `/database/imports/`
* Die Datei `.env.example` duplizieren und auf `.env` umbennenen
* .env anpassen:
    * APP_ENV=production
    * APP_DEBUG=false
    * APP_URL= mit den URL unter welche Adresse die App erreichbar wird befüllen
    * DB_* mit die Datenbank Credentials befüllen
    * MAIL_* mit SMTP Credentials befüllen
    * APP_KEY mit den Ergebnis von `echo 'base64:'.base64_encode(random_bytes(32));` befüllen
    * SCHOOL_NAME mit Name der Schuele befuellen

### SSH Installation
* git clone
* composer install
* cp .env.copy .env
* .env anpassen:
    * APP_ENV=production
    * APP_DEBUG=false
    * APP_URL= mit den URL wo die App erreichbar wird befüllen
    * DB_* mit die Datenbank Credentials befüllen
    * MAIL_* mit SMTP Credentials befüllen
    * SCHOOL_NAME mit Name der Schuele befuellen

In den Arbeitsordner das folgende Kommando ausfuhren:
```
php artisan key:generate \
&& php artisan migrate:fresh --seed \
&& npm install \
&& npm audit fix \
&& npm install --prefix resources/js/SVWS-Server/svws-webclient/ \
&& npm run build --prefix resources/js/SVWS-Server/svws-webclient/src/ui-components/ts/ \
&& npm run build
```

### Lokale Docker Installation
* git clone
* cd ins Repository
* composer install
* cp .env.copy .env
* .env anpassen:
  * APP_ENV=production oder local
  * APP_DEBUG=false oder true
  * APP_URL= mit den URL wo die App erreichbar wird befüllen
  * DB_CONNECTION=mysql
  * DB_HOST=mysql 
  * DB_PORT=3306 
  * DB_DATABASE=laravel 
  * DB_USERNAME=sail 
  * DB_PASSWORD=password
  * MAIL_* mit SMTP Credentials befüllen
  * SCHOOL_NAME mit Name der Schuele befuellen


In den Arbeitsordner das folgende Kommando ausfuhren:
```
alias sail="./vendor/bin/sail" \
&& sail up -d \
&& sail php artisan key:generate \
&& sail php artisan migrate:fresh --seed \
&& sail npm install \
&& sail npm audit fix \
&& sail npm install --prefix resources/js/SVWS-Server/svws-webclient \
&& sail npm run build --prefix resources/js/SVWS-Server/svws-webclient/src/ui-components/ts/ \
&& sail npm run build
```

## Plugins: 
* Backend
    * https://github.com/barryvdh/laravel-debugbar
    * https://github.com/barryvdh/laravel-ide-helper
    * https://github.com/laravel/jetstream
* Frontend 
    * https://github.com/vuejs/pinia
    * https://github.com/tailwindlabs/tailwindcss
    * https://github.com/axios/axios

echo 123 && \
echo 4356
