## Installation
* Datenbank anlegen
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

## Plugins: 
* https://github.com/barryvdh/laravel-debugbar
* https://github.com/barryvdh/laravel-ide-helper