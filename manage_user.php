<?php
session_start();

// Vérification si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['niveau_privilege'] !== 'admin') {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas un admin
    header("Location: login.php");
    exit();
}

// Inclusion du fichier database.php
include_once 'database.php';

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

$message = '';

// Gestion des modifications et suppressions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_user'])) {
        // Suppression d'un utilisateur
        $id_utilisateur = $_POST['id_utilisateur'];
        $query = "DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        if ($stmt->execute()) {
            $message = "Utilisateur supprimé avec succès.";
        } else {
            $message = "Erreur lors de la suppression de l'utilisateur.";
        }
    } elseif (isset($_POST['update_password'])) {
        // Modification du mot de passe
        $id_utilisateur = $_POST['id_utilisateur'];
        $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
        $hashed_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
        $query = "UPDATE utilisateur SET mot_de_passe = :mot_de_passe WHERE id_utilisateur = :id_utilisateur";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        if ($stmt->execute()) {
            $message = "Mot de passe mis à jour avec succès.";
        } else {
            $message = "Erreur lors de la mise à jour du mot de passe.";
        }
    }
}

// Récupération de la liste des utilisateurs
$query = "SELECT * FROM utilisateur";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>

<div class="container mt-5">
    <div class="box">
        <h2 class="title">Gestion des Utilisateurs</h2>
        <?php if ($message != ''): ?>
            <div class="notification <?php echo strpos($message, 'succès') !== false ? 'is-success' : 'is-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="buttons">
            <a href="inscription.php" class="button is-primary">Créer un nouvel utilisateur</a>
        </div>

        <table class="table is-fullwidth is-striped">
            <thead>
                <tr>
                    <th>ID Utilisateur</th>
                    <th>Nom Utilisateur</th>
                    <th>Niveau Privilège</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id_utilisateur']; ?></td>
                        <td><?php echo $user['nom_utilisateur']; ?></td>
                        <td><?php echo $user['niveau_privilege']; ?></td>
                        <td>
                            <form method="post" action="manage_user.php" class="field has-addons">
                                <div class="control">
                                    <input type="hidden" name="id_utilisateur" value="<?php echo $user['id_utilisateur']; ?>">
                                    <input class="input" type="text" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe">
                                </div>
                                <div class="control">
                                    <button class="button is-link" type="submit" name="update_password">Mettre à jour</button>
                                </div>
                                <div class="control">
                                    <button class="button is-danger is-outlined" type="submit" name="delete_user">Supprimer</button>
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
