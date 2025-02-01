<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: http://localhost:8000/formConnection.html');
    exit();
}

require 'config.php'; 
require 'Liste.php';  
require 'Utilisateur.php';

// Traitement du formulaire
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
                <p><?php echo $liste['nom']; ?></p>
                <p>Liste créé par --> <?php echo $utilisateur['identifiant'];?> <-- </p>
                <p>son identifiant est : <?php echo $liste['utilisateur_id']?></p>
                <br>
        <?php
            }
        } else {
            echo "<p>Aucune liste trouvée.</p>";
        }
        ?>
    </div>
</body>

</html>