Voici une version corrigée et améliorée de votre README :

# PARC AUTO

## Description

Cette documentation vous guide dans l'installation et la configuration de l'application Parc Auto.

## Sommaire

- [Installation](#installation-fr)

## Installation (FR)

### 1. Pré-requis

#### 1.1 Installation du serveur web

Vous pouvez utiliser Nginx ou Apache2 comme serveur web.

##### Apache2 :

```bash
sudo apt update
sudo apt dist-upgrade -y
sudo apt install -y apache2
sudo systemctl start apache2
```

##### Nginx :

```bash
sudo apt update
sudo apt dist-upgrade -y
sudo apt install -y nginx
sudo systemctl start nginx
```

#### 1.2 Installation du serveur SQL

Nous utilisons MySQL pour cette application.

```bash
sudo apt install -y mysql-server
sudo systemctl start mysql
```

#### 1.3 Installation de PHP

```bash
sudo apt install -y php
```

Pour Apache2 :

```bash
sudo systemctl restart apache2
```

Pour Nginx :

```bash
sudo systemctl restart nginx
```

#### 1.4 Installation des modules PHP

Installez les modules PHP suivants :

- fpdf
- mysql
- pdo
- phpqrcode

```bash
sudo apt install -y php-fpdf php-mysql php-pdo phpqrcode
```

Pour Apache2 :

```bash
sudo systemctl restart apache2
```

Pour Nginx :

```bash
sudo systemctl restart nginx
```

#### 1.5 Installation du package Parc Auto

```bash
cd /var/www/html
git clone https://github.com/alexetne/ParcAuto.git
```

### 2. Installation de la base de données

Entrez dans le serveur MySQL :

```bash
sudo mysql
```

#### 2.1 Création de la base de données

```sql
CREATE DATABASE ERP_CAMION;
```

#### 2.2 Création des tables

```sql
CREATE TABLE MARQUE (
    id_marque INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(250)
);

CREATE TABLE MODELE (
    id_modele INT PRIMARY KEY AUTO_INCREMENT,
    id_marque INT,
    nom VARCHAR(250),
    commentaire TEXT,
    FOREIGN KEY (id_marque) REFERENCES MARQUE(id_marque)
);

CREATE TABLE camion (
    ID_camion INT PRIMARY KEY AUTO_INCREMENT,
    reception_le DATETIME DEFAULT CURRENT_TIMESTAMP,
    reception_par VARCHAR(250),
    id_marque INT, 
    id_modele INT,
    VAN VARCHAR(17),
    empattement INT,
    num_serie VARCHAR(250),
    PTAC INT,
    PTRA INT,
    max_essieu_av INT,
    max_essieu_ar INT,
    etat_vehicule ENUM('occasion', 'neuf'),
    nb_places INT,
    km BIGINT,
    couleur VARCHAR(250),
    enjoliver BOOLEAN,
    cabine ENUM('simple', 'double', 'profonde'),
    boite ENUM('auto', 'manuelle'),
    roues ENUM('simple', 'jumelees'),
    code_affaire VARCHAR(250),
    raison_sociale VARCHAR(250),
    type_vh ENUM('Fu','C3.5','CMED','CP12-16','BAR-PAT','autre'),
    info_type_vh TEXT,
    commentaire TEXT,
    FOREIGN KEY (id_marque) REFERENCES MARQUE(id_marque),
    FOREIGN KEY (id_modele) REFERENCES MODELE(id_modele)
);

CREATE TABLE utilisateur (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    nom_utilisateur VARCHAR(250),
    mot_de_passe VARCHAR(250),
    niveau_privilege ENUM('admin', 'editeur', 'lecteur')
);
```

#### 2.3 Création de l'utilisateur administrateur de la base de données

```sql
CREATE USER '<Votre Nom Utilisateur>'@'localhost' IDENTIFIED BY '<Votre Mot de Passe>';

GRANT ALL PRIVILEGES ON ERP_CAMION.* TO '<Votre Nom Utilisateur>'@'localhost';
```

#### 2.4 Configuration du fichier de connexion à la base de données

Allez dans le dossier ParcAuto et modifiez `database.php` :

```bash
cd /var/www/html/ParcAuto
nano database.php
```

Remplacez `<username>` par votre nom d'utilisateur et `<password>` par votre mot de passe.

### Création de l'utilisateur administrateur de l'application

Ouvrez le fichier `inscription.php` :

```bash
nano inscription.php
```

Commentez les lignes suivantes pour permettre la création du premier utilisateur :

```php
// if (!isset($_SESSION['id_utilisateur']) || $_SESSION['niveau_privilege'] !== 'admin') {
//     // Rediriger vers la page de connexion si l'utilisateur n'est pas un admin
//     header("Location: login.php");
//     exit();
// }
```

Ensuite, rendez-vous sur `http://<ip_du_serveur>/ParcAuto/inscription.php` pour créer votre utilisateur administrateur.

Une fois cette opération terminée, décommentez les lignes dans `inscription.php` pour réactiver la sécurité :

```php
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['niveau_privilege'] !== 'admin') {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas un admin
    header("Location: login.php");
    exit();
}
```

Ce README devrait désormais être clair et fonctionnel pour les utilisateurs qui souhaitent installer et configurer l'application Parc Auto.
