<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bulma CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="index.php">
                <strong>Mon Site</strong>
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="index.php">Accueil</a>
                <a class="navbar-item" href="list.php">Gestion des camions</a>
                <a class="navbar-item" href="create_camion.php">Créer un camion</a>
                <a class="navbar-item" href="edit_marque.php">Gestion des marques</a>
                <a class="navbar-item" href="edit_modeles.php">Gestion des modèles</a>
                <a class="navbar-item" href="profil.php">Profil</a>
                <a class="navbar-item" href="manage_user.php">Gérer les profiles</a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <a class="button is-danger is-outlined" href="deconnexion.php">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
