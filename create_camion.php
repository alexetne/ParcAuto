<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté et a un statut d'admin ou d'éditeur
if (!isset($_SESSION['id_utilisateur']) || !in_array($_SESSION['niveau_privilege'], ['admin', 'editeur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est ni un admin ni un éditeur
    header("Location: login.php");
    exit();
}

include 'header.php';
include_once 'database.php';

// Création d'une instance de la classe Database
$database = new Database();
$db = $database->getConnection();

// Récupération de la liste des marques et des modèles
$query_marques = "SELECT * FROM MARQUE";
$stmt_marques = $db->prepare($query_marques);
$stmt_marques->execute();
$marques = $stmt_marques->fetchAll(PDO::FETCH_ASSOC);

$query_modeles = "SELECT * FROM MODELE";
$stmt_modeles = $db->prepare($query_modeles);
$stmt_modeles->execute();
$modeles = $stmt_modeles->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire de création de camion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reception_le = $_POST['reception_le'] ?? null;
    $reception_par = $_POST['reception_par'] ?? null;
    $id_marque = $_POST['id_marque'] ?? null;
    $id_modele = $_POST['id_modele'] ?? null;
    $VAN = $_POST['VAN'] ?? null;
    $empattement = $_POST['empattement'] !== '' ? $_POST['empattement'] : 0;
    $num_serie = $_POST['num_serie'] ?? null;
    $PTAC = $_POST['PTAC'] !== '' ? $_POST['PTAC'] : 0;
    $PTRA = $_POST['PTRA'] !== '' ? $_POST['PTRA'] : 0;
    $max_essieu_av = $_POST['max_essieu_av'] !== '' ? $_POST['max_essieu_av'] : 0;
    $max_essieu_ar = $_POST['max_essieu_ar'] !== '' ? $_POST['max_essieu_ar'] : 0;
    $etat_vehicule = $_POST['etat_vehicule'] ?? null;
    $nb_places = $_POST['nb_places'] !== '' ? $_POST['nb_places'] : 0;
    $km = $_POST['km'] !== '' ? $_POST['km'] : 0;
    $couleur = $_POST['couleur'] ?? null;
    $enjoliver = isset($_POST['enjoliver']) ? 1 : 0;
    $cabine = $_POST['cabine'] ?? null;
    $boite = $_POST['boite'] ?? null;
    $roues = $_POST['roues'] ?? null;
    $code_affaire = $_POST['code_affaire'] ?? null;
    $raison_sociale = $_POST['raison_sociale'] ?? null;
    $type_vh = $_POST['type_vh'] ?? null;
    $info_type_vh = $_POST['info_type_vh'] ?? null;
    $commentaire = $_POST['commentaire'] ?? null;

    $query = "INSERT INTO camion (reception_le, reception_par, id_marque, id_modele, VAN, empattement, num_serie, PTAC, PTRA, max_essieu_av, max_essieu_ar, etat_vehicule, nb_places, km, couleur, enjoliver, cabine, boite, roues, code_affaire, raison_sociale, type_vh, info_type_vh, commentaire)
              VALUES (:reception_le, :reception_par, :id_marque, :id_modele, :VAN, :empattement, :num_serie, :PTAC, :PTRA, :max_essieu_av, :max_essieu_ar, :etat_vehicule, :nb_places, :km, :couleur, :enjoliver, :cabine, :boite, :roues, :code_affaire, :raison_sociale, :type_vh, :info_type_vh, :commentaire)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':reception_le', $reception_le);
    $stmt->bindParam(':reception_par', $reception_par);
    $stmt->bindParam(':id_marque', $id_marque);
    $stmt->bindParam(':id_modele', $id_modele);
    $stmt->bindParam(':VAN', $VAN);
    $stmt->bindParam(':empattement', $empattement);
    $stmt->bindParam(':num_serie', $num_serie);
    $stmt->bindParam(':PTAC', $PTAC);
    $stmt->bindParam(':PTRA', $PTRA);
    $stmt->bindParam(':max_essieu_av', $max_essieu_av);
    $stmt->bindParam(':max_essieu_ar', $max_essieu_ar);
    $stmt->bindParam(':etat_vehicule', $etat_vehicule);
    $stmt->bindParam(':nb_places', $nb_places);
    $stmt->bindParam(':km', $km);
    $stmt->bindParam(':couleur', $couleur);
    $stmt->bindParam(':enjoliver', $enjoliver);
    $stmt->bindParam(':cabine', $cabine);
    $stmt->bindParam(':boite', $boite);
    $stmt->bindParam(':roues', $roues);
    $stmt->bindParam(':code_affaire', $code_affaire);
    $stmt->bindParam(':raison_sociale', $raison_sociale);
    $stmt->bindParam(':type_vh', $type_vh);
    $stmt->bindParam(':info_type_vh', $info_type_vh);
    $stmt->bindParam(':commentaire', $commentaire);

    if ($stmt->execute()) {
        echo '<div class="notification is-success">Camion créé avec succès.</div>';
    } else {
        echo '<div class="notification is-danger">Erreur lors de la création du camion.</div>';
    }
}
?>

<div class="container mt-5">
    <h1 class="title">Créer un camion</h1>
    <form method="post" action="create_camion.php" class="box">
        <div class="field">
            <label class="label" for="reception_le">Réception le :</label>
            <div class="control">
                <input class="input" type="datetime-local" id="reception_le" name="reception_le" >
            </div>
        </div>
        
        <div class="field">
            <label class="label" for="reception_par">Réception par :</label>
            <div class="control">
                <input class="input" type="text" id="reception_par" name="reception_par" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="id_marque">Marque :</label>
            <div class="control">
                <div class="select">
                    <select id="id_marque" name="id_marque" >
                        <?php foreach ($marques as $marque): ?>
                            <option value="<?php echo $marque['id_marque']; ?>"><?php echo $marque['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="id_modele">Modèle :</label>
            <div class="control">
                <div class="select">
                    <select id="id_modele" name="id_modele" >
                        <?php foreach ($modeles as $modele): ?>
                            <option value="<?php echo $modele['id_modele']; ?>"><?php echo $modele['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="VAN">VAN :</label>
            <div class="control">
                <input class="input" type="text" id="VAN" name="VAN" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="empattement">Empattement :</label>
            <div class="control">
                <input class="input" type="number" id="empattement" name="empattement" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="num_serie">Num Série :</label>
            <div class="control">
                <input class="input" type="text" id="num_serie" name="num_serie" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="PTAC">PTAC :</label>
            <div class="control">
                <input class="input" type="number" id="PTAC" name="PTAC" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="PTRA">PTRA :</label>
            <div class="control">
                <input class="input" type="number" id="PTRA" name="PTRA" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="max_essieu_av">Max Essieu Av :</label>
            <div class="control">
                <input class="input" type="number" id="max_essieu_av" name="max_essieu_av" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="max_essieu_ar">Max Essieu Ar :</label>
            <div class="control">
                <input class="input" type="number" id="max_essieu_ar" name="max_essieu_ar" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="etat_vehicule">Etat Véhicule :</label>
            <div class="control">
                <div class="select">
                    <select id="etat_vehicule" name="etat_vehicule" >
                        <option value="occasion">Occasion</option>
                        <option value="neuf">Neuf</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="nb_places">Nb Places :</label>
            <div class="control">
                <input class="input" type="number" id="nb_places" name="nb_places" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="km">Km :</label>
            <div class="control">
                <input class="input" type="number" id="km" name="km" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="couleur">Couleur :</label>
            <div class="control">
                <input class="input" type="text" id="couleur" name="couleur" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="enjoliver">Enjoliver :</label>
            <div class="control">
                <input class="checkbox" type="checkbox" id="enjoliver" name="enjoliver">
            </div>
        </div>

        <div class="field">
            <label class="label" for="cabine">Cabine :</label>
            <div class="control">
                <div class="select">
                    <select id="cabine" name="cabine">
                        <option value="simple">Simple</option>
                        <option value="double">Double</option>
                        <option value="profonde">Profonde</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="boite">Boite :</label>
            <div class="control">
                <div class="select">
                    <select id="boite" name="boite">
                        <option value="auto">Auto</option>
                        <option value="manuelle">Manuelle</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="roues">Roues :</label>
            <div class="control">
                <div class="select">
                    <select id="roues" name="roues">
                        <option value="simple">Simple</option>
                        <option value="jumelees">Jumelees</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="code_affaire">Code Affaire :</label>
            <div class="control">
                <input class="input" type="text" id="code_affaire" name="code_affaire" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="raison_sociale">Raison Sociale :</label>
            <div class="control">
                <input class="input" type="text" id="raison_sociale" name="raison_sociale" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="type_vh">Type Véhicule :</label>
            <div class="control">
                <div class="select">
                    <select id="type_vh" name="type_vh" >
                        <option value="Fu">Fu</option>
                        <option value="C3.5">C3.5</option>
                        <option value="CMED">CMED</option>
                        <option value="CP12-16">CP12-16</option>
                        <option value="BAR-PAT">BAR-PAT</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="info_type_vh">Info Type Véhicule :</label>
            <div class="control">
                <textarea class="textarea" id="info_type_vh" name="info_type_vh"></textarea>
            </div>
        </div>

        <div class="field">
            <label class="label" for="commentaire">Commentaire :</label>
            <div class="control">
                <textarea class="textarea" id="commentaire" name="commentaire"></textarea>
            </div>
        </div>

        <div class="control">
            <input class="button is-primary" type="submit" value="Créer">
        </div>
    </form>
</div>

<?php
include 'footer.php';
?>
