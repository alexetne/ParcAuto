<?php
session_start();

// Inclusion du fichier database.php
include_once 'database.php';

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête SQL pour vérifier les informations d'identification
    $query = "SELECT * FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            // Définir les variables de session
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
            $_SESSION['niveau_privilege'] = $user['niveau_privilege'];

            // Redirection vers la page d'accueil ou de tableau de bord
            header("Location: index.php");
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Nom d'utilisateur incorrect.";
    }
}
include 'header.php';
?>

<div class="container mt-5">
    <h2 class="title">Connexion</h2>
    <?php if ($message != ''): ?>
        <div class="notification is-danger">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="login.php" class="box">
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

        <div class="control">
            <input class="button is-primary" type="submit" value="Se connecter">
        </div>
    </form>
</div>

<?php
include 'footer.php';
?>
