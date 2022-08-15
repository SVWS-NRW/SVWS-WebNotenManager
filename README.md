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
* php artisan key:generate
* php artisan migrate:fresh --seed
* npm install
* npm install --prefix resources\js\SVWS-Server\svws-ui-components
* npm run build --prefix resources\js\SVWS-Server\svws-ui-components
* npm run production

### Lokale Docker Installation
* git clone
* cd ins Repository
* composer install
* cp .env.copy .env
* php artisan key:generate
* php artisan migrate:fresh --seed
* /vendon/bin/sail up -d
* /vendon/bin/sail npm install
* /vendon/bin/sail npm install --prefix resources\js\SVWS-Server\svws-ui-components
* /vendon/bin/sail npm run build --prefix resources\js\SVWS-Server\svws-ui-components
* /vendon/bin/sail npm run dev

## Plugins: 
* Backend
    * https://github.com/barryvdh/laravel-debugbar
    * https://github.com/barryvdh/laravel-ide-helper
    * https://github.com/laravel/jetstream
* Frontend 
    * https://github.com/vuejs/pinia
    * https://github.com/tailwindlabs/tailwindcss
    * https://github.com/axios/axios

