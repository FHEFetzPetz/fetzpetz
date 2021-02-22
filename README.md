# Projekt: FetzPetz
## Projekt Grundlagen, Dynamische Webprogrammierung

## Online Shop für Party Zubehör

Im Rahmen der Lehrveranstaltung Grundlagen- und Dynamsische Webprogrammierung haben wir als Projekt einen Onlineshop erstellt, um die Grundlagen in PHP, HTML, CSS und JavaScript zu erlernen.
Unser Projekt FetzPetz ist ein Online Shop für Indoor und Outdoor Party Zubehör, auf dem sich die typischen Elemente eines Shop wiederfinden.

## Projektteam

- **Saskia Wohlers** - [Profil](https://github.com/schnoernja)
- **Dirk Hofmann** - [Profil](https://github.com/Munchkin129)
- **Luca Voges** - [Profil](https://github.com/Vogeslu)

### Kernfunktionalitäten

- Registrierung/Login
- Warenkorb
- Wunschliste
- Produktansicht
- Checkout
- Benutzerprofil
- Suchfunktion
- Adminstrationsoberfläche
- Produktkategorien

## Installation

### Mindestanforderungen

- Git
- PHP 7.2+
- MySQL or MariaDB
- Apache or NGINX

Alternativ können Sie auch XAMPP nutzen [Link](https://www.apachefriends.org/de/index.html).

#### 1. Repository herunterladen

Klonen mit HTTPS: [https://github.com/FHEFetzPetz/fetzpetz](https://github.com/FHEFetzPetz/fetzpetz)

Klonen mit SSH: `git@github.com:FHEFetzPetz/fetzpetz.git`

#### 2. Datenbank oder XAMPP starten

#### 3. Konfiguration ändern

Passen Sie die Konfiguration in der `index.php` und in dem Ordner `src/config` an.

#### 4. setup.php ausführen

Führen Sie den Befehl `php setup.php` im Terminal im Stammverzeichnis aus. Folgen Sie den angezeigten Anweisungen, um die Datenbank, den Adminnutzer und die Testdaten zu importieren.

#### 5. Seite abrufen

Erstellen Sie eine PHP-Instanz mittels `php -S localhost:[PORT]` oder rufen Sie diese mit XAMPP auf.

## Ordnerstruktur

### /assets

Beinhaltet alle Styles, Fonts, Icons, Bilder, JavaScript und Uploads.

### /logs

Beinhaltet das LOG für die Applikation.

### /setup

Beinhaltet das Erstellungsskript (SQL) und die Testdaten.

### /src

#### /components

Beinhaltet die Basiskomponenten Contoller und Model.

#### /config

Beinhaltet die Konfiguration für die Datenbank, das LOG und Weiteres.

#### /controller

Beinhaltet die Controller für die einzelnen Routen.

#### /core

Beinhaltet das Kernelement Service, welches für die Module relevant ist.

#### /models

Beinhaltet die Datenbankmodelle.

#### /services

Beinhaltet alle Kernmodule des Projektes.

##### DatabaseService

Verwaltet die Projektdatenbank mittels PDO.

##### LoggerService

Verwaltet die LOG-Dateien und erstellt neue Einträge.

##### ModelService

Sucht, erstellt, updatet, löscht und verwaltet die Datenbankmodelle.

##### NotificationService

Verwaltet die, in den Session abgelegten Benachrichtigungen.

##### RequestService

Ordnet der URL einen passenden Controller zu.

##### SecurityService

Verwaltet die Session des Nutzers.

##### ShopService

Verwaltet den Warenkorb, die Wunschliste und gibt Bestellungen auf.

#### Kernel

Initialisiert alle Module des Projektes.

### /views

Beinhaltet alle Front-End-Views, Komponenten und Templates.

### autoload

Lädt alle relevanten PHP Dateien.

### index

Ausgangsdatei für Anfragen, Initialisiert die Konfiguration, den Autoloader und gibt Befehle an den RequestService.

### setup

Relevante Datei für die Erstellung der Datenbank, der Testdaten und Adminbenutzer. Datei wird nach Ausführung gelöscht.
