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

$message = '';

// Récupération des informations de l'utilisateur connecté
$query = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    // Vérifier si les mots de passe correspondent
    if ($mot_de_passe !== $confirmer_mot_de_passe) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si le nom d'utilisateur est déjà pris par un autre utilisateur
        $query = "SELECT * FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur AND id_utilisateur != :id_utilisateur";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Le nom d'utilisateur est déjà pris.";
        } else {
            // Hachage du mot de passe si un nouveau mot de passe est fourni
            $hashed_password = !empty($mot_de_passe) ? password_hash($mot_de_passe, PASSWORD_DEFAULT) : $user['mot_de_passe'];

            // Mise à jour des informations de l'utilisateur
            $query = "UPDATE utilisateur SET nom_utilisateur = :nom_utilisateur, mot_de_passe = :mot_de_passe WHERE id_utilisateur = :id_utilisateur";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
            $stmt->bindParam(':mot_de_passe', $hashed_password);
            $stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);

            if ($stmt->execute()) {
                $message = "Mise à jour réussie.";
                // Mettre à jour la session
                $_SESSION['nom_utilisateur'] = $nom_utilisateur;
            } else {
                $message = "Erreur lors de la mise à jour. Veuillez réessayer.";
            }
        }
    }
}
include 'header.php';
?>
<div class="container mt-5">
    <div class="box">
        <h2 class="title">Mon Profil</h2>
        <?php if ($message != ''): ?>
            <div class="notification <?php echo strpos($message, 'réussie') !== false ? 'is-success' : 'is-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="profil.php">
            <div class="field">
                <label class="label" for="nom_utilisateur">Nom d'utilisateur :</label>
                <div class="control">
                    <input class="input" type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?php echo htmlspecialchars($user['nom_utilisateur']); ?>" required>
                </div>
            </div>
            <div class="field">
                <label class="label" for="mot_de_passe">Nouveau mot de passe :</label>
                <div class="control">
                    <input class="input" type="password" id="mot_de_passe" name="mot_de_passe">
                </div>
            </div>
            <div class="field">
                <label class="label" for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe :</label>
                <div class="control">
                    <input class="input" type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe">
                </div>
            </div>
            <div class="control">
                <button class="button is-primary" type="submit">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
<?php
include 'footer.php';
?>

