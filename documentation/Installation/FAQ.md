# FAQ

- Ich kann mich nicht einloggen - User und Passwort falsch. 
Es muss zunächst ein Benutzer mit Adminrechten in einer leeren Datenbankl angelegt werden. dies gfeschieht auf der Konsole mit adminrechten mit dem Befehl: 
´´´bash 
php artisan create:admin-user --user=$WENOM_TECH_ADMIN --password=$WENOM_TECH_ADMIN_PW
´´´

- Ich kann mich einloggen, aber fast jeder Aufruf wird mit einem roten Fehler quittiert. 
Es wurde noch kein Key generiert, der die Kommunikation zwischen Backend und Frontend regelt. 
´´´bash 
php artisan key:generate
´´´