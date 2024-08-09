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

// Création d'une nouvelle marque
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $nom_marque = $_POST['nom'];

    $query = "INSERT INTO MARQUE (nom) VALUES (:nom)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nom', $nom_marque);

    if ($stmt->execute()) {
        $message = "Marque créée avec succès.";
    } else {
        $message = "Erreur lors de la création de la marque.";
    }
}

// Mise à jour d'une marque existante
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_marque = $_POST['id_marque'];
    $nom_marque = $_POST['nom'];

    $query = "UPDATE MARQUE SET nom = :nom WHERE id_marque = :id_marque";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nom', $nom_marque);
    $stmt->bindParam(':id_marque', $id_marque);

    if ($stmt->execute()) {
        $message = "Marque mise à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour de la marque.";
    }
}

// Suppression d'une marque
if (isset($_GET['delete'])) {
    $id_marque = $_GET['delete'];

    $query = "DELETE FROM MARQUE WHERE id_marque = :id_marque";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_marque', $id_marque);

    if ($stmt->execute()) {
        $message = "Marque supprimée avec succès.";
    } else {
        $message = "Erreur lors de la suppression de la marque.";
    }
}

// Récupération de la liste des marques
$query = "SELECT * FROM MARQUE";
$stmt = $db->prepare($query);
$stmt->execute();
$marques = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="container mt-5">
    <h1 class="title">Gestion des marques</h1>
    <?php if ($message != ''): ?>
        <div class="notification is-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h3 class="subtitle">Créer une nouvelle marque</h3>
    <form method="post" action="edit_marque.php" class="box">
        <div class="field">
            <label class="label" for="nom">Nom de la marque :</label>
            <div class="control">
                <input class="input" type="text" id="nom" name="nom" required>
            </div>
        </div>
        <div class="control">
            <input class="button is-primary" type="submit" name="create" value="Créer">
        </div>
    </form>

    <h3 class="subtitle">Liste des marques</h3>
    <table class="table is-fullwidth is-striped">
        <thead>
            <tr>
                <th>ID Marque</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marques as $marque): ?>
                <tr>
                    <td><?php echo $marque['id_marque']; ?></td>
                    <td><?php echo $marque['nom']; ?></td>
                    <td>
                        <form method="post" action="edit_marque.php" style="display:inline;">
                            <div class="field has-addons">
                                <div class="control">
                                    <input type="hidden" name="id_marque" value="<?php echo $marque['id_marque']; ?>">
                                    <input class="input" type="text" name="nom" value="<?php echo $marque['nom']; ?>" required>
                                </div>
                                <div class="control">
                                    <input class="button is-link" type="submit" name="update" value="Mettre à jour">
                                </div>
                                <div class="control">
                                    <a class="button is-danger is-outlined" href="edit_marque.php?delete=<?php echo $marque['id_marque']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette marque ?');">Supprimer</a>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.php';
?>
