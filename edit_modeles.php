<?php
session_start();

// Vérification si l'utilisateur est connecté et a le rôle d'éditeur ou d'administrateur
if (!isset($_SESSION['id_utilisateur']) || !in_array($_SESSION['niveau_privilege'], ['admin', 'editeur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est ni un admin ni un éditeur
    header("Location: login.php");
    exit();
}

// Inclusion du fichier database.php
include_once 'database.php';

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

$message = '';

// Suppression d'un modèle
if (isset($_GET['delete'])) {
    $id_modele = $_GET['delete'];

    // Vérification des camions associés
    $query = "SELECT COUNT(*) as count FROM camion WHERE id_modele = :id_modele";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_modele', $id_modele);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        $message = "Impossible de supprimer le modèle. Des camions y sont associés.";
    } else {
        $query = "DELETE FROM MODELE WHERE id_modele = :id_modele";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_modele', $id_modele);

        if ($stmt->execute()) {
            $message = "Modèle supprimé avec succès.";
        } else {
            $message = "Erreur lors de la suppression du modèle.";
        }
    }
}

// Récupération de la liste des marques pour le formulaire de sélection
$query = "SELECT * FROM MARQUE";
$stmt = $db->prepare($query);
$stmt->execute();
$marques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Création d'un nouveau modèle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $id_marque = $_POST['id_marque'];
    $nom_modele = $_POST['nom'];
    $commentaire = $_POST['commentaire'];

    $query = "INSERT INTO MODELE (id_marque, nom, commentaire) VALUES (:id_marque, :nom, :commentaire)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_marque', $id_marque);
    $stmt->bindParam(':nom', $nom_modele);
    $stmt->bindParam(':commentaire', $commentaire);

    if ($stmt->execute()) {
        $message = "Modèle créé avec succès.";
    } else {
        $message = "Erreur lors de la création du modèle.";
    }
}

// Mise à jour d'un modèle existant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_modele = $_POST['id_modele'];
    $id_marque = $_POST['id_marque'];
    $nom_modele = $_POST['nom'];
    $commentaire = $_POST['commentaire'];

    $query = "UPDATE MODELE SET id_marque = :id_marque, nom = :nom, commentaire = :commentaire WHERE id_modele = :id_modele";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_marque', $id_marque);
    $stmt->bindParam(':nom', $nom_modele);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':id_modele', $id_modele);

    if ($stmt->execute()) {
        $message = "Modèle mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour du modèle.";
    }
}

// Récupération de la liste des modèles avec association des marques
$query = "SELECT MODELE.*, MARQUE.nom AS marque_nom FROM MODELE LEFT JOIN MARQUE ON MODELE.id_marque = MARQUE.id_marque";
$stmt = $db->prepare($query);
$stmt->execute();
$modeles = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="container mt-5">
    <div class="box">
        <h1 class="title">Gestion des modèles</h1>
        <?php if ($message != ''): ?>
            <div class="notification <?php echo strpos($message, 'succès') !== false ? 'is-success' : 'is-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <h3 class="subtitle">Créer un nouveau modèle</h3>
        <form method="post" action="edit_modeles.php">
            <div class="field">
                <label class="label" for="id_marque">Marque :</label>
                <div class="control">
                    <div class="select">
                        <select id="id_marque" name="id_marque" required>
                            <?php foreach ($marques as $marque): ?>
                                <option value="<?php echo $marque['id_marque']; ?>"><?php echo $marque['nom']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="label" for="nom">Nom du modèle :</label>
                <div class="control">
                    <input class="input" type="text" id="nom" name="nom" required>
                </div>
            </div>
            <div class="field">
                <label class="label" for="commentaire">Commentaire :</label>
                <div class="control">
                    <textarea class="textarea" id="commentaire" name="commentaire"></textarea>
                </div>
            </div>
            <div class="control">
                <button class="button is-primary" type="submit" name="create">Créer</button>
            </div>
        </form>
    </div>

    <div class="box mt-5">
        <h3 class="subtitle">Liste des modèles</h3>
        <table class="table is-fullwidth is-striped">
            <thead>
                <tr>
                    <th>ID Modèle</th>
                    <th>Marque</th>
                    <th>Nom</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modeles as $modele): ?>
                    <tr>
                        <td><?php echo $modele['id_modele']; ?></td>
                        <td><?php echo $modele['marque_nom']; ?></td>
                        <td><?php echo $modele['nom']; ?></td>
                        <td><?php echo $modele['commentaire']; ?></td>
                        <td>
                            <form method="post" action="edit_modeles.php" style="display:inline;">
                                <input type="hidden" name="id_modele" value="<?php echo $modele['id_modele']; ?>">
                                <input type="hidden" name="id_marque" value="<?php echo $modele['id_marque']; ?>">
                                <input name="nom" value="<?php echo $modele['nom']; ?>">
                                <input type="hidden" name="commentaire" value="<?php echo $modele['commentaire']; ?>">
                                <div class="field has-addons">
                                    <div class="control">
                                        <button class="button is-link" type="submit" name="update">Mettre à jour</button>
                                    </div>
                                    <div class="control">
                                        <a class="button is-danger is-outlined" href="edit_modeles.php?delete=<?php echo $modele['id_modele']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ?');">Supprimer</a>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'footer.php';
?>
