
CREATE DATABASE ERP_CAMION;

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
