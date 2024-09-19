# WebNotenManager Handbuch


Der WebNotenManager (WeNoM) kann als Plattform außerhalb des Schulverwaltungsnetzes eingesetzt werden, um hier den Lehrkräften eine externen Möglichkeit zu geben, ihre Noten, Teilnoten, Bemerkungen und Fehlstunden einzutragen. Diese Daten werden dann über eine sichere Verbindung mit dem SVWS-Server synchronisiert. 

## Einrichtung 

### Systemvoraussetzungen

### Konfigurationseinstellungen

Alle technischen Konfigurationseinstellungen werden in einer .env (Environtment) Datei gespeichert. Diese befindet sich im Hauptverzeichnis, in der Regel: /app/webnotenmaneger/

### Email Konfiguration 

Unter der Administrations App des Webnotenmanager kann im Menuepunkt Emaileinstellungen eine Emailadresse zum Versenden der Anmeldelinks bzw. des Zweiten Faktors hinterlegt werden. 



## Benutzerverwaltung 

### technischer Administrationszugang

Bei der Ersteinrichtung muss ein technischer Administrator eingerichtet werden. Dieser soll die Synchronisation mit dem SVWS-Server einrichten und dürchführen können. Nach erfolgreicher Einrichtung der Datenbank kann mit dem Befehl 

```bash 
 php artisan create:admin-user
```

ein technischer Administrator angelegt werden. 


### Zugang als Lehrkraft

Alle Benutzer außer dem technischen Administrator können nur im SVWS-Server angelegt werden. Es sind somit für diese Benutzer eine Schulnummer, die Emailadresse und das Lehrkraftkürzel eindeutig vorgegeben und in der Datenbank des Webnotenmanagers eingetragen. 

#### neues Passwort anfordern

Bei Neuanmeldung einer Lehrerkraft oder auch bei einem vergessenem Passwort wird der Button "passwort anfordern" vom Benutzer auf der Anmeldemaske angeklickt. 

![Passwort anfordern](./graphics/pw_anfordern.png)

Nun wird die eigene Schulnummer, Emailadresse und das Lehrkraftkürzel eingetragen. Falls diese Daten mit denen aus dem SVWS-Server erhaltenen Daten übereinstimmen, wird auf die Emailadresse der Lehrkraft ein 15 min lang gültiger link zum Setzen des neuen Passworts geschickt. 

![Passwort zurücksetzen mail](./graphics/mail.png)

Hierbei muss natürlich bei der Einrichtung in der Administationsoberfläche des Webnotenmanagers eine gültige Emailadresse eingerichtet sein. 


