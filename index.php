<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
}

// Inclusion du fichier database.php
include_once 'database.php';

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

// Récupération des statistiques
$query = "SELECT COUNT(*) AS total_camions FROM camion";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
$total_camions = $stats['total_camions'];

$query = "SELECT COUNT(*) AS total_utilisateurs FROM utilisateur";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
$total_utilisateurs = $stats['total_utilisateurs'];

// Récupération de la liste des camions
$query = "SELECT camion.*, MARQUE.nom AS marque_nom, MODELE.nom AS modele_nom
          FROM camion
          LEFT JOIN MARQUE ON camion.id_marque = MARQUE.id_marque
          LEFT JOIN MODELE ON camion.id_modele = MODELE.id_modele";
$stmt = $db->prepare($query);
$stmt->execute();
$camions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de la liste des utilisateurs (accessible uniquement aux admins)
$utilisateurs = [];
if ($_SESSION['niveau_privilege'] === 'admin') {
    $query = "SELECT * FROM utilisateur";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
include 'header.php';
?>

<div class="container mt-5">
    <div class="box">
        <h1 class="title">Tableau de bord</h1>
        <h2 class="subtitle">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom_utilisateur']); ?></h2>
        
        <div class="columns">
            <div class="column">
                <div class="notification is-info">
                    <p class="title"><?php echo $total_camions; ?></p>
                    <p class="subtitle">Total camions</p>
                </div>
            </div>
            <div class="column">
                <div class="notification is-primary">
                    <p class="title"><?php echo $total_utilisateurs; ?></p>
                    <p class="subtitle">Total utilisateurs</p>
                </div>
            </div>
        </div>
        
        <h3 class="subtitle">Liste des camions</h3>
        <table class="table is-fullwidth is-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Réception le</th>
                    <th>Réception par</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>VAN</th>
                    <th>Num Série</th>
                    <th>État</th>
                    <th>Couleur</th>
                    <!-- Ajoutez d'autres colonnes si nécessaire -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($camions as $camion): ?>
                    <tr>
                        <td><?php echo $camion['ID_camion']; ?></td>
                        <td><?php echo $camion['reception_le']; ?></td>
                        <td><?php echo $camion['reception_par']; ?></td>
                        <td><?php echo $camion['marque_nom']; ?></td>
                        <td><?php echo $camion['modele_nom']; ?></td>
                        <td><?php echo $camion['VAN']; ?></td>
                        <td><?php echo $camion['num_serie']; ?></td>
                        <td><?php echo $camion['etat_vehicule']; ?></td>
                        <td><?php echo $camion['couleur']; ?></td>
                        <!-- Ajoutez d'autres colonnes si nécessaire -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($_SESSION['niveau_privilege'] === 'admin'): ?>
            <h3 class="subtitle">Liste des utilisateurs</h3>
            <table class="table is-fullwidth is-striped">
                <thead>
                    <tr>
                        <th>ID Utilisateur</th>
                        <th>Nom Utilisateur</th>
                        <th>Niveau Privilège</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?php echo $utilisateur['id_utilisateur']; ?></td>
                            <td><?php echo $utilisateur['nom_utilisateur']; ?></td>
                            <td><?php echo $utilisateur['niveau_privilege']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div class="buttons">
            <a class="button is-link" href="profil.php">Mon Profil</a>
            <a class="button is-danger is-outlined" href="deconnexion.php">Déconnexion</a>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
