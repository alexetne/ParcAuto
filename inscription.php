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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];
    $niveau_privilege = $_POST['niveau_privilege'];

    // Vérifier si les mots de passe correspondent
    if ($mot_de_passe !== $confirmer_mot_de_passe) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si le nom d'utilisateur est déjà pris
        $query = "SELECT * FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Le nom d'utilisateur est déjà pris.";
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur dans la base de données
            $query = "INSERT INTO utilisateur (nom_utilisateur, mot_de_passe, niveau_privilege) VALUES (:nom_utilisateur, :mot_de_passe, :niveau_privilege)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
            $stmt->bindParam(':mot_de_passe', $hashed_password);
            $stmt->bindParam(':niveau_privilege', $niveau_privilege);

            if ($stmt->execute()) {
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header("Location: login.php");
                exit();
            } else {
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
}

include 'header.php';
?>

<div class="container mt-5">
    <h2 class="title">Inscription</h2>
    <?php if ($message != ''): ?>
        <div class="notification is-danger">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="inscription.php" class="box">
        <div class="field">
            <label class="label" for="nom_utilisateur">Nom d'utilisateur :</label>
            <div class="control">
                <input class="input" type="text" id="nom_utilisateur" name="nom_utilisateur" required>
            </div>
        </div>

        <div class="field">
            <label class="label" for="mot_de_passe">Mot de passe :</label>
            <div class="control">
                <input class="input" type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
        </div>

        <div class="field">
            <label class="label" for="confirmer_mot_de_passe">Confirmer le mot de passe :</label>
            <div class="control">
                <input class="input" type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>
        </div>

        <div class="field">
            <label class="label" for="niveau_privilege">Niveau de privilège :</label>
            <div class="control">
                <div class="select">
                    <select id="niveau_privilege" name="niveau_privilege" required>
                        <option value="lecteur">Lecteur</option>
                        <option value="editeur">Éditeur</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="control">
            <input class="button is-primary" type="submit" value="S'inscrire">
        </div>
    </form>
</div>

<?php
include 'footer.php';
?>
