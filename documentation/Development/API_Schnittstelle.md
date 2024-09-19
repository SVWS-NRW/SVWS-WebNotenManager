# Webservices

Der WebNotenManager kann auch per Api angesprochen werden. 
Ziel ist es damit eine Maschine zu Maschine Synchronisation von Noten, Teilnoten und Bemerkungen für das laufende Schulhalbjahr zu ermöglichen. 
Ebenso können Stammdaten der Schule zum WebNotenManager hochgeladen werden. 

Hierzu gibt es grundsätlich 4 Funktionen: 

- Export
- Import
- Zurücksetzen
- (Synchronisation als Hintereinanderausführung von Export und Import)

Zu sicheren Kommunikation wird das [OAth2-Verfahren](OAuth2.md) eingesetzt. 


## API Schnittstelle

*Production:*

Methode | relativer Pfad | Beschreibung
------- | -------------- | ------------
POST | /api/secure/import | Import eines AES/BASE64 verschlüsselten, per GZIP komprimierten JSON.
GET  | /api/secure/export | Export eines AES/BASE64 verschlüsselten, per GZIP komprimierten JSON.

*Testing:*
Methode | relativer Pfad | Beschreibung
------- | -------------- | ------------
GET  | /api/export       | Vorschau, wie das Json für den Export formatiert ist.
GET  | /api/import/gzip  | Import einer GZIP komprimierten Datei (vgl. Api SVWS-Server) per GET Request.
POST | /api/import/gzip  | Import einer GZIP komprimierten Datei (vgl. Api SVWS-Server) per POST Request.
POST | /api/import       | Import einer reinen JSON per POST Request.
GET  | /api/import       | Import einer reinen JSON per POST Request.
GET  | /api/truncate     | Die Datenbank wird geleert.
GET  | /api/import/aes   | Import eines AES/BASE64 verschlüsselten, per GZIP komprimierten JSON aus der Datei ../database/seeders/data/enm.json.aes.base64 


## Synchronisation 

### Einrichtung

![Synchronisation_Einrichtung](graphics/Synchronisation_Einrichtung.png)


### Vorbereitung 

![Synchronisation_Vorbereitung](graphics/Synchronisation_Vorbereitung.png)


### Datenfluss
![Synchronisation](graphics/Synchronisation.png)



### WeNoM Export 

Es folgt eine Übersicht der Bedienung einzelner Api Endpunkte in WeNoM 

```bash 
curl --request GET  --url http://wenom.svws-nrw.de/api/export
```

Einstellungen bei Insomnia:
+ [GET] https://wenom.svws-nrw.de/api/export
+ 'No Body'  
+ 'no Auth' in der aktuellen Developer Version

 

### WeNoM Import

```bash 
curl --request POST \
   --url http://wenom.svws-nrw.de/api/import \
   --header 'Content-Type: application/json' \
   --data 'hier das JSON einsetzen'
```

Einstellungen bei Insomnia:
+ [POST] https://wenom.svws-nrw.de/api/import  
+ 'JSON' -> das JSON einfach reinkopieren 
+ 'no Auth' in der aktuellen Developer Version


#### WeNoM Gzip Import

```bash 
curl --request POST \
   --url http://wenom.svws-nrw.de/api/import \
   --header 'Accept-Encoding: gzip, deflate, br' \
   --header 'Content-Type: multipart/form-data' \
   --form '¬�  .... 
=@/home/svws/git/SVWS-TestMDBs/ENM-Json/ENM-Testdaten-01/enm.json.gz'
```

Einstellungen bei Insomnia:
+ [POST] https://wenom.svws-nrw.de/api/import/gzip
+ 'multipart', file, enm.json.gz auswählen
+ 'Headers' Add: header 'Accept-Encoding', value: 'gzip, deflate, br'
+ 'no Auth' in der aktuellen Developer Version


#### WeNoM Secure Import

Hier kann ein AES verschlüsselter Inhalt übertragen werden. Dazu muss der Schlüssel und das Salt in die .env Datei bei WeNoM 
eingetragen sein. Ebenso wird die Schulnummer geprüft.  

Vorgehen der API:  

+ Datei empfangen
+ entzippen
+ base64 entschluesseln
+ aes entschluesseln
+ Schulnummer prüfen 
+ importieren


Einstellungen bei Insonmia:
+ [POST] https://wenom.svws-nrw.de/api/secure/import 
+ TBD ...


#### WeNoM Zurücksetzen  

```bash 
curl --request GET --url http://wenom.svws-nrw.de/api/truncate
```

Einstellungen bei Insomnia:
+ [GET] https://wenom.svws-nrw.de/api/truncate
+ 'no Body' 
+ 'no Auth' in der aktuellen Developer Version





## Korrespondierende Schnittstellen beim SVWS-Server

Die Gegenseite der Synchronisation bildet der SVWS-Server

### Testdaten

Aus dem SVWS-Server heraus können die im Folgenden beschriebenen Testdaten im Json-Format generiert werden, um die Webservices vom WebNotenManager zu testen. 
Die Testdaten können aus verschiedenen Testdatenbaneken, ENM1,ENM2 und ENM3, generiert werden, die jeweils auf dem SVWS-Server nightly.svws-nrw.de einsehbar sind.
Ebenso können diese Testdaten als .json und die zugehörigen Testdatenbanken vom dem öffentlichen Github 
Repository [SVWS-NRW/SVWS-TestMDBs](https://github.com/SVWS-NRW/SVWS-TestMDBs) herunter geladen werden: 

[ENM1.json](https://raw.githubusercontent.com/SVWS-NRW/SVWS-TestMDBs/main/ENM-Json/ENM-Testdaten-01/ENMGesamt.json)  
[ENM2.json](https://raw.githubusercontent.com/SVWS-NRW/SVWS-TestMDBs/main/ENM-Json/ENM-Testdaten-02/ENMGesamt.json)  
[ENM3.json](https://raw.githubusercontent.com/SVWS-NRW/SVWS-TestMDBs/main/ENM-Json/ENM-Testdaten-03/ENMGesamt.json)  

Bitte entsprechend die Auswahl bzw. den Curl Befehl auf den passenden Server 1, 2, oder 3 anpassen. 

### Testdaten per Curl

#### SVWS Export 

Gesamt Export einer Json mit allen Klassen, Noten, Teilnoten, Bemerkungen, Lehrekräfte, etc. 

```bash 
curl --user "Admin:" -X 'GET' -k 'https://nightly.svws-nrw.de/db/ENM1/enm/alle' -H 'accept: application/json' 
```

#### SVWS Export, gezippt

s.o. nur als .zip
```bash 
curl --user "Admin:" -X 'GET' -k 'https://nightly.svws-nrw.de/db/ENM1/enm/alle/gzip' -H 'accept: application/json' 
```

#### SVWS Export, eine Lehrkraft

Export aller Daten für eine Lehrkraft, hier am Beispiel der Lehrer ID 123. Dies wird für das ENM Notenmodul benötigt. 

```bash
curl --user "Admin:" -X 'GET' -k 'https://nightly.svws-nrw.de/db/ENM1/enm/lehrer/123' -H 'accept: application/json'
```
