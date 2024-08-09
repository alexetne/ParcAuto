 <?php
    include 'header.php';
    if (!isset($_POST['id']) && !isset($_GET['id'])) {
        die('Aucun camion sélectionné.');
    }

    $id_camion = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);
    
    // Affichage des erreurs
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'database.php';
    require_once '/usr/share/phpqrcode/qrlib.php';
    require_once '/usr/share/fpdf/fpdf.php';

    // Si le formulaire n'a pas encore été soumis, afficher le formulaire
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    ?>
        <form action="generate_qrcode.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id_camion; ?>">
            <h3>Sélectionnez les champs à inclure dans le PDF:</h3>
            <label><input type="checkbox" name="fields[]" value="reception_le"> Date de réception</label><br>
            <label><input type="checkbox" name="fields[]" value="reception_par"> Réception par</label><br>
            <label><input type="checkbox" name="fields[]" value="marque_nom"> Marque</label><br>
            <label><input type="checkbox" name="fields[]" value="modele_nom"> Modèle</label><br>
            <label><input type="checkbox" name="fields[]" value="VAN"> VAN</label><br>
            <label><input type="checkbox" name="fields[]" value="empattement"> Empattement</label><br>
            <label><input type="checkbox" name="fields[]" value="num_serie"> Numéro de série</label><br>
            <label><input type="checkbox" name="fields[]" value="PTAC"> PTAC</label><br>
            <label><input type="checkbox" name="fields[]" value="PTRA"> PTRA</label><br>
            <label><input type="checkbox" name="fields[]" value="max_essieu_av"> Max essieu avant</label><br>
            <label><input type="checkbox" name="fields[]" value="max_essieu_ar"> Max essieu arrière</label><br>
            <label><input type="checkbox" name="fields[]" value="etat_vehicule"> État du véhicule</label><br>
            <label><input type="checkbox" name="fields[]" value="nb_places"> Nombre de places</label><br>
            <label><input type="checkbox" name="fields[]" value="km"> Kilométrage</label><br>
            <label><input type="checkbox" name="fields[]" value="couleur"> Couleur</label><br>
            <label><input type="checkbox" name="fields[]" value="enjoliver"> Enjoliveur</label><br>
            <label><input type="checkbox" name="fields[]" value="cabine"> Cabine</label><br>
            <label><input type="checkbox" name="fields[]" value="boite"> Boîte</label><br>
            <label><input type="checkbox" name="fields[]" value="roues"> Roues</label><br>
            <label><input type="checkbox" name="fields[]" value="code_affaire"> Code affaire</label><br>
            <label><input type="checkbox" name="fields[]" value="raison_sociale"> Raison sociale</label><br>
            <label><input type="checkbox" name="fields[]" value="type_vh"> Type de véhicule</label><br>
            <label><input type="checkbox" name="fields[]" value="info_type_vh"> Informations sur le type de véhicule</label><br>
            <label><input type="checkbox" name="fields[]" value="commentaire"> Commentaire</label><br>
            <input type="submit" value="Générer le PDF">
        </form>
    <?php
    } else {
        try {
            // Création d'une instance de la classe Database
            $database = new Database();
            $db = $database->getConnection();

            // Récupération des informations du camion
            $query = "SELECT camion.*, MARQUE.nom AS marque_nom, MODELE.nom AS modele_nom, MODELE.commentaire AS modele_commentaire
                      FROM camion
                      LEFT JOIN MARQUE ON camion.id_marque = MARQUE.id_marque
                      LEFT JOIN MODELE ON camion.id_modele = MODELE.id_modele
                      WHERE ID_camion = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id_camion, PDO::PARAM_INT);
            $stmt->execute();
            $camion = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$camion) {
                die('Camion non trouvé.');
            }

            // Traitement des champs sélectionnés
            $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

            // Génération du QR code
            $url = 'http://192.168.1.71/parcauto/fiche_camion.php?id=' . $id_camion;
            $qrCodePath = tempnam(sys_get_temp_dir(), 'qrcode_') . '.png';
            QRcode::png($url, $qrCodePath, QR_ECLEVEL_L, 10);

            // Génération du PDF
            class PDF extends FPDF
            {
                function Header()
                {
                    // Aucun header nécessaire
                }

                function Footer()
                {
                    // Aucun footer nécessaire
                }
            }

            $pdf = new PDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            // Ajout du QR Code
            $pdf->Image($qrCodePath, ($pdf->GetPageWidth() - 50) / 2, 50, 50, 50);

            // Ajout des informations
            $pdf->SetY(110);
            $pdf->SetFont('Arial', '', 12);
            foreach ($fields as $field) {
                if (isset($camion[$field])) {
                    $label = ucfirst(str_replace('_', ' ', $field));
                    $value = $camion[$field];
                    $pdf->Cell(0, 10, "$label: $value", 0, 1, 'C');
                }
            }

            // Suppression du fichier QR code temporaire
            unlink($qrCodePath);

            // Envoi du PDF au client pour téléchargement
            $pdfOutput = $pdf->Output('S');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $id_camion . '.pdf"');
            echo $pdfOutput;
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }

        exit();
    }
    include 'footer.php';
    ?>
