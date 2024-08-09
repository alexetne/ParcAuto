<?php
include 'header.php';
include_once 'database.php';

// Démarrer la session
session_start();

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

$message = '';

// Suppression d'un camion
if (isset($_GET['delete']) && $_SESSION['niveau_privilege'] == 'admin') {
    $id_camion = $_GET['delete'];

    // Vérification si le camion existe
    $query = "SELECT * FROM camion WHERE ID_camion = :id_camion";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_camion', $id_camion);
    $stmt->execute();
    $camion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($camion) {
        // Suppression du camion
        $query = "DELETE FROM camion WHERE ID_camion = :id_camion";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_camion', $id_camion);

        if ($stmt->execute()) {
            $message = "Camion supprimé avec succès.";
        } else {
            $message = "Erreur lors de la suppression du camion.";
        }
    } else {
        $message = "Camion non trouvé.";
    }
}

// Requête SQL pour sélectionner toutes les données de la table camion, avec jointures sur les tables MARQUE et MODELE
$query = "SELECT camion.*, MARQUE.nom AS marque_nom, MODELE.nom AS modele_nom, MODELE.commentaire AS modele_commentaire
          FROM camion
          LEFT JOIN MARQUE ON camion.id_marque = MARQUE.id_marque
          LEFT JOIN MODELE ON camion.id_modele = MODELE.id_modele";
$stmt = $db->prepare($query);
$stmt->execute();
$camions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-5">
    <h1 class="title">Gestion des camions</h1>
    <?php if ($message != ''): ?>
        <div class="notification is-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <a href="create_camion.php" class="button is-primary mb-3">Créer un nouveau camion</a>

    <h2 class="subtitle">Filtres</h2>
    <div class="box">
        <div class="columns is-multiline">
            <?php
            $columns = [
                "ID_camion" => "ID",
                "reception_le" => "Réception le",
                "reception_par" => "Réception par",
                "marque_nom" => "Marque",
                "modele_nom" => "Modèle",
                "modele_commentaire" => "Commentaire Modèle",
                "VAN" => "VAN",
                "empattement" => "Empattement",
                "num_serie" => "Num Série",
                "PTAC" => "PTAC",
                "PTRA" => "PTRA",
                "max_essieu_av" => "Max Essieu Av",
                "max_essieu_ar" => "Max Essieu Ar",
                "etat_vehicule" => "Etat Véhicule",
                "nb_places" => "Nb Places",
                "km" => "Km",
                "couleur" => "Couleur",
                "enjoliver" => "Enjoliver",
                "cabine" => "Cabine",
                "boite" => "Boite",
                "roues" => "Roues",
                "code_affaire" => "Code Affaire",
                "raison_sociale" => "Raison Sociale",
                "type_vh" => "Type Véhicule",
                "info_type_vh" => "Info Type Véhicule",
                "commentaire" => "Commentaire"
            ];
            $default_checked = ["reception_le", "reception_par", "marque_nom", "modele_nom", "VAN", "num_serie", "code_affaire"];
            foreach ($columns as $column => $label) {
                $checked = in_array($column, $default_checked) ? "checked" : "";
                echo "<div class='column is-one-quarter'>
                        <label class='checkbox'>
                            <input type='checkbox' class='column-toggle' data-column='$column' $checked> $label
                        </label>
                      </div>";
            }
            ?>
        </div>
    </div>

    <table class="table is-fullwidth is-striped">
        <thead>
            <tr>
                <th class="ID_camion">ID</th>
                <th class="reception_le">Réception le</th>
                <th class="reception_par">Réception par</th>
                <th class="marque_nom">Marque</th>
                <th class="modele_nom">Modèle</th>
                <th class="modele_commentaire">Commentaire Modèle</th>
                <th class="VAN">VAN</th>
                <th class="empattement">Empattement</th>
                <th class="num_serie">Num Série</th>
                <th class="PTAC">PTAC</th>
                <th class="PTRA">PTRA</th>
                <th class="max_essieu_av">Max Essieu Av</th>
                <th class="max_essieu_ar">Max Essieu Ar</th>
                <th class="etat_vehicule">Etat Véhicule</th>
                <th class="nb_places">Nb Places</th>
                <th class="km">Km</th>
                <th class="couleur">Couleur</th>
                <th class="enjoliver">Enjoliver</th>
                <th class="cabine">Cabine</th>
                <th class="boite">Boite</th>
                <th class="roues">Roues</th>
                <th class="code_affaire">Code Affaire</th>
                <th class="raison_sociale">Raison Sociale</th>
                <th class="type_vh">Type Véhicule</th>
                <th class="info_type_vh">Info Type Véhicule</th>
                <th class="commentaire">Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($camions as $camion): ?>
                <tr>
                    <td class="ID_camion"><?php echo $camion['ID_camion']; ?></td>
                    <td class="reception_le"><?php echo $camion['reception_le']; ?></td>
                    <td class="reception_par"><?php echo $camion['reception_par']; ?></td>
                    <td class="marque_nom"><?php echo $camion['marque_nom']; ?></td>
                    <td class="modele_nom"><?php echo $camion['modele_nom']; ?></td>
                    <td class="modele_commentaire"><?php echo $camion['modele_commentaire']; ?></td>
                    <td class="VAN"><?php echo $camion['VAN']; ?></td>
                    <td class="empattement"><?php echo $camion['empattement']; ?></td>
                    <td class="num_serie"><?php echo $camion['num_serie']; ?></td>
                    <td class="PTAC"><?php echo $camion['PTAC']; ?></td>
                    <td class="PTRA"><?php echo $camion['PTRA']; ?></td>
                    <td class="max_essieu_av"><?php echo $camion['max_essieu_av']; ?></td>
                    <td class="max_essieu_ar"><?php echo $camion['max_essieu_ar']; ?></td>
                    <td class="etat_vehicule"><?php echo $camion['etat_vehicule']; ?></td>
                    <td class="nb_places"><?php echo $camion['nb_places']; ?></td>
                    <td class="km"><?php echo $camion['km']; ?></td>
                    <td class="couleur"><?php echo $camion['couleur']; ?></td>
                    <td class="enjoliver"><?php echo $camion['enjoliver'] ? 'Oui' : 'Non'; ?></td>
                    <td class="cabine"><?php echo $camion['cabine']; ?></td>
                    <td class="boite"><?php echo $camion['boite']; ?></td>
                    <td class="roues"><?php echo $camion['roues']; ?></td>
                    <td class="code_affaire"><?php echo $camion['code_affaire']; ?></td>
                    <td class="raison_sociale"><?php echo $camion['raison_sociale']; ?></td>
                    <td class="type_vh"><?php echo $camion['type_vh']; ?></td>
                    <td class="info_type_vh"><?php echo $camion['info_type_vh']; ?></td>
                    <td class="commentaire"><?php echo $camion['commentaire']; ?></td>
                    <td>
                        <div class="field is-grouped">
                            <?php if (in_array($_SESSION['niveau_privilege'], ['admin', 'editeur'])): ?>
                                <p class="control">
                                    <a class="button is-link" href="edit_camion.php?id=<?php echo $camion['ID_camion']; ?>">Éditer</a>
                                </p>
                            <?php endif; ?>
                            <?php if ($_SESSION['niveau_privilege'] == 'admin'): ?>
                                <p class="control">
                                    <a class="button is-danger is-outlined" href="edit_camion.php?id=<?php echo $camion['ID_camion']; ?>&delete=<?php echo $camion['ID_camion']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce camion ?');">Supprimer</a>
                                </p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.querySelectorAll('.column-toggle').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var column = document.querySelectorAll('.' + checkbox.getAttribute('data-column'));
            column.forEach(function (col) {
                col.style.display = checkbox.checked ? '' : 'none';
            });
        });
    });

    document.querySelectorAll('.column-toggle').forEach(function (checkbox) {
        if (!checkbox.checked) {
            var column = document.querySelectorAll('.' + checkbox.getAttribute('data-column'));
            column.forEach(function (col) {
                col.style.display = 'none';
            });
        }
    });
</script>

<?php
include 'footer.php';
?>
