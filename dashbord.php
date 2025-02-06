<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: http://localhost:8000/formConnection.html');
    exit();
}

require 'config.php';
require 'Liste.php';
require 'Utilisateur.php';
require 'ListElement.php';

// création de la liste (nom)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nouvelleListe'])) {
    $nomListe = htmlspecialchars($_POST['nouvelleListe']);

    // Création d'une nouvelle instance de Liste
    $nouvelleListe = new Liste($db, $nomListe);

    // Appel de la méthode creationListe()
    if ($nouvelleListe->creationListe()) {
        header("Location: " . $_SERVER['PHP_SELF']); // Rafraîchir la page pour afficher la nouvelle liste
        exit();
    } else {
        echo "Erreur lors de la création de la liste.";
    }
}
// supprimer une liste

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["supprimerListe"])) {
    var_dump($_POST);
    $liste = new Liste($db);
    $liste->id = $_POST["liste_id"];

    if ($liste->supprimerListe()) {
        echo "Liste supprimée avec succès !";
    } else {
        echo "Erreur lors de la suppression.";
    }
}

// création d'un element dans une liste existante
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ajoutElement']) && !empty($_POST['liste_id'])) {
    var_dump($_POST);
    $contenu = htmlspecialchars($_POST['ajoutElement']);
    $liste_id = intval($_POST['liste_id']);

    $nouvelElement = new ListElement($db, $liste_id, $contenu);

    if ($nouvelElement->ajouterElement()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Erreur lors de la création de la liste.";
    }
}

// suppression d'un element dans une liste existante
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["supprimerElement"])) {
    var_dump($_POST);
    $contenu = new ListElement($db);
    $contenu->setId($_POST['element_id']);

    if ($contenu->supprimerElement()) {
        echo 'Element de la liste supprimé avec succès !';
    }
    else {
        echo "erreur lors de la suppression de l'élèment.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des listes</title>
</head>

<body>
    <div>

<?php
// formulaire d'ajout d'une nouvelle liste
?>
        <form action="" method="POST">
            <label for="nouvelleListe">Nouvelle liste</label>
            <input type="text" name="nouvelleListe">
            <button type="submit">Ajouter</button>
        </form>
    </div>
    <div>
        <?php
        $requete = $db->prepare("SELECT * FROM liste");
        $requete->execute();
        $listes = $requete->fetchAll();

        if (!empty($listes)) {
            foreach ($listes as $liste) {

                $requeteUtilisateur = $db->prepare("SELECT identifiant FROM Utilisateur WHERE id = ?");
                $requeteUtilisateur->execute([$liste['utilisateur_id']]);
                $utilisateur = $requeteUtilisateur->fetch();

        ?>
                <div>

<?php
// formulaire de supprission de liste
?>
                    <p><?php echo $liste['nom']; ?></p>
                    <form action='' method='POST' style='display:inline;'>
                        <input type='hidden' name='liste_id' value="<?php echo htmlspecialchars($liste['id']); ?>">
                        <button type='submit' name='supprimerListe'>❌</button>
                    </form>
<?php
//  requete d'affichage des elements de listes
?>
                    <p>Liste créée par : <?php echo $utilisateur['identifiant']; ?></p>
                    <?php
                    $requeteElements = $db->prepare("SELECT id, contenu FROM liste_element WHERE liste_id = ?");
                    $requeteElements->execute([$liste['id']]);
                    $elements = $requeteElements->fetchAll();

                    if (!empty($elements)) {
                        foreach ($elements as $element) {
                            echo $element['contenu'];
                    ?>

                            <form action="" method="POST">
                                <input type="hidden" name='element_id' value="<?php echo htmlspecialchars($element['id']); ?>">
                                <button type="submit" name="supprimerElement">❌</button>

                            </form>

                            <br>
                    <?php
                        }
                    } else {
                        echo "<p>Aucun élément dans cette liste.</p>";
                    }
                    ?>
<?php
//  formaulaire d'ajout d'élément à une liste
?>
                    <form action="" method='POST'>
                        <input type='hidden' name='liste_id' value="<?php echo htmlspecialchars($liste['id']); ?>">
                        <input type="text" name="ajoutElement" required>
                        <button type='submit' name='ajouterElement'><?php echo "\u{2795}"; ?></button>
                    </form>

                    <br>
                </div>

        <?php
            }
        } else {
            echo "<p>Aucune liste trouvée.</p>";
        }
        ?>
    </div>
</body>

</html>