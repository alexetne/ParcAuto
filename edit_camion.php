<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();

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

// Vérification si un ID a été passé en paramètre
if (!isset($_GET['id'])) {
    echo '<div class="notification is-danger">Aucun camion sélectionné.</div>';
    include 'footer.php';
    exit();
}

$id_camion = $_GET['id'];

// Suppression d'un camion
if (isset($_GET['delete'])) {
    $query = "DELETE FROM camion WHERE ID_camion = :id_camion";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_camion', $id_camion);

    if ($stmt->execute()) {
        echo '<div class="notification is-success">Camion supprimé avec succès.</div>';
        include 'footer.php';
        exit();
    } else {
        echo '<div class="notification is-danger">Erreur lors de la suppression du camion.</div>';
        include 'footer.php';
        exit();
    }
}

// Récupération des informations du camion
$query = "SELECT * FROM camion WHERE ID_camion = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id_camion);
$stmt->execute();
$camion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$camion) {
    echo '<div class="notification is-danger">Camion non trouvé.</div>';
    include 'footer.php';
    exit();
}

// Récupération de la liste des marques et des modèles
$query_marques = "SELECT * FROM MARQUE";
$stmt_marques = $db->prepare($query_marques);
$stmt_marques->execute();
$marques = $stmt_marques->fetchAll(PDO::FETCH_ASSOC);

$query_modeles = "SELECT * FROM MODELE";
$stmt_modeles = $db->prepare($query_modeles);
$stmt_modeles->execute();
$modeles = $stmt_modeles->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour des informations du camion
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

    $query = "UPDATE camion SET 
                reception_le = :reception_le,
                reception_par = :reception_par,
                id_marque = :id_marque,
                id_modele = :id_modele,
                VAN = :VAN,
                empattement = :empattement,
                num_serie = :num_serie,
                PTAC = :PTAC,
                PTRA = :PTRA,
                max_essieu_av = :max_essieu_av,
                max_essieu_ar = :max_essieu_ar,
                etat_vehicule = :etat_vehicule,
                nb_places = :nb_places,
                km = :km,
                couleur = :couleur,
                enjoliver = :enjoliver,
                cabine = :cabine,
                boite = :boite,
                roues = :roues,
                code_affaire = :code_affaire,
                raison_sociale = :raison_sociale,
                type_vh = :type_vh,
                info_type_vh = :info_type_vh,
                commentaire = :commentaire
              WHERE ID_camion = :id";
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
    $stmt->bindParam(':id', $id_camion);

    if ($stmt->execute()) {
        echo '<div class="notification is-success">Camion mis à jour avec succès.</div>';
    } else {
        echo '<div class="notification is-danger">Erreur lors de la mise à jour du camion.</div>';
    }
}
?>

<div class="container mt-5">
    <h1 class="title">Modifier les informations du camion</h1>
    <form method="post" action="edit_camion.php?id=<?php echo $id_camion; ?>" class="box">
        <div class="field">
            <label class="label" for="reception_le">Réception le :</label>
            <div class="control">
                <input class="input" type="datetime-local" id="reception_le" name="reception_le" value="<?php echo date('Y-m-d\TH:i', strtotime($camion['reception_le'])); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="reception_par">Réception par :</label>
            <div class="control">
                <input class="input" type="text" id="reception_par" name="reception_par" value="<?php echo htmlspecialchars($camion['reception_par']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="id_marque">Marque :</label>
            <div class="control">
                <div class="select">
                    <select id="id_marque" name="id_marque" >
                        <?php foreach ($marques as $marque): ?>
                            <option value="<?php echo $marque['id_marque']; ?>" <?php echo $marque['id_marque'] == $camion['id_marque'] ? 'selected' : ''; ?>>
                                <?php echo $marque['nom']; ?>
                            </option>
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
                            <option value="<?php echo $modele['id_modele']; ?>" <?php echo $modele['id_modele'] == $camion['id_modele'] ? 'selected' : ''; ?>>
                                <?php echo $modele['nom']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="VAN">VAN :</label>
            <div class="control">
                <input class="input" type="text" id="VAN" name="VAN" value="<?php echo htmlspecialchars($camion['VAN']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="empattement">Empattement :</label>
            <div class="control">
                <input class="input" type="number" id="empattement" name="empattement" value="<?php echo htmlspecialchars($camion['empattement']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="num_serie">Num Série :</label>
            <div class="control">
                <input class="input" type="text" id="num_serie" name="num_serie" value="<?php echo htmlspecialchars($camion['num_serie']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="PTAC">PTAC :</label>
            <div class="control">
                <input class="input" type="number" id="PTAC" name="PTAC" value="<?php echo htmlspecialchars($camion['PTAC']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="PTRA">PTRA :</label>
            <div class="control">
                <input class="input" type="number" id="PTRA" name="PTRA" value="<?php echo htmlspecialchars($camion['PTRA']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="max_essieu_av">Max Essieu Av :</label>
            <div class="control">
                <input class="input" type="number" id="max_essieu_av" name="max_essieu_av" value="<?php echo htmlspecialchars($camion['max_essieu_av']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="max_essieu_ar">Max Essieu Ar :</label>
            <div class="control">
                <input class="input" type="number" id="max_essieu_ar" name="max_essieu_ar" value="<?php echo htmlspecialchars($camion['max_essieu_ar']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="etat_vehicule">Etat Véhicule :</label>
            <div class="control">
                <div class="select">
                    <select id="etat_vehicule" name="etat_vehicule" >
                        <option value="occasion" <?php echo $camion['etat_vehicule'] == 'occasion' ? 'selected' : ''; ?>>Occasion</option>
                        <option value="neuf" <?php echo $camion['etat_vehicule'] == 'neuf' ? 'selected' : ''; ?>>Neuf</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="nb_places">Nb Places :</label>
            <div class="control">
                <input class="input" type="number" id="nb_places" name="nb_places" value="<?php echo htmlspecialchars($camion['nb_places']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="km">Km :</label>
            <div class="control">
                <input class="input" type="number" id="km" name="km" value="<?php echo htmlspecialchars($camion['km']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="couleur">Couleur :</label>
            <div class="control">
                <input class="input" type="text" id="couleur" name="couleur" value="<?php echo htmlspecialchars($camion['couleur']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="enjoliver">Enjoliver :</label>
            <div class="control">
                <input class="checkbox" type="checkbox" id="enjoliver" name="enjoliver" <?php echo $camion['enjoliver'] ? 'checked' : ''; ?>>
            </div>
        </div>

        <div class="field">
            <label class="label" for="cabine">Cabine :</label>
            <div class="control">
                <div class="select">
                    <select id="cabine" name="cabine" >
                        <option value="simple" <?php echo $camion['cabine'] == 'simple' ? 'selected' : ''; ?>>Simple</option>
                        <option value="double" <?php echo $camion['cabine'] == 'double' ? 'selected' : ''; ?>>Double</option>
                        <option value="profonde" <?php echo $camion['cabine'] == 'profonde' ? 'selected' : ''; ?>>Profonde</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="boite">Boite :</label>
            <div class="control">
                <div class="select">
                    <select id="boite" name="boite" >
                        <option value="auto" <?php echo $camion['boite'] == 'auto' ? 'selected' : ''; ?>>Auto</option>
                        <option value="manuelle" <?php echo $camion['boite'] == 'manuelle' ? 'selected' : ''; ?>>Manuelle</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="roues">Roues :</label>
            <div class="control">
                <div class="select">
                    <select id="roues" name="roues" >
                        <option value="simple" <?php echo $camion['roues'] == 'simple' ? 'selected' : ''; ?>>Simple</option>
                        <option value="jumelees" <?php echo $camion['roues'] == 'jumelees' ? 'selected' : ''; ?>>Jumelees</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="code_affaire">Code Affaire :</label>
            <div class="control">
                <input class="input" type="text" id="code_affaire" name="code_affaire" value="<?php echo htmlspecialchars($camion['code_affaire']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="raison_sociale">Raison Sociale :</label>
            <div class="control">
                <input class="input" type="text" id="raison_sociale" name="raison_sociale" value="<?php echo htmlspecialchars($camion['raison_sociale']); ?>" >
            </div>
        </div>

        <div class="field">
            <label class="label" for="type_vh">Type Véhicule :</label>
            <div class="control">
                <div class="select">
                    <select id="type_vh" name="type_vh" >
                        <option value="Fu" <?php echo $camion['type_vh'] == 'Fu' ? 'selected' : ''; ?>>Fu</option>
                        <option value="C3.5" <?php echo $camion['type_vh'] == 'C3.5' ? 'selected' : ''; ?>>C3.5</option>
                        <option value="CMED" <?php echo $camion['type_vh'] == 'CMED' ? 'selected' : ''; ?>>CMED</option>
                        <option value="CP12-16" <?php echo $camion['type_vh'] == 'CP12-16' ? 'selected' : ''; ?>>CP12-16</option>
                        <option value="BAR-PAT" <?php echo $camion['type_vh'] == 'BAR-PAT' ? 'selected' : ''; ?>>BAR-PAT</option>
                        <option value="autre" <?php echo $camion['type_vh'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="info_type_vh">Info Type Véhicule :</label>
            <div class="control">
                <textarea class="textarea" id="info_type_vh" name="info_type_vh"><?php echo htmlspecialchars($camion['info_type_vh']); ?></textarea>
            </div>
        </div>

        <div class="field">
            <label class="label" for="commentaire">Commentaire :</label>
            <div class="control">
                <textarea class="textarea" id="commentaire" name="commentaire"><?php echo htmlspecialchars($camion['commentaire']); ?></textarea>
            </div>
        </div>

        <div class="control">
            <input class="button is-primary" type="submit" value="Mettre à jour">
            <a class="button is-danger" href="edit_camion.php?id=<?php echo $id_camion; ?>&delete=<?php echo $id_camion; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce camion ?');">Supprimer</a>
        </div>
    </form>
</div>

<?php
include 'footer.php';
?>
