<?php
session_start();
if ((!isset($_POST["identifiant"])) || (empty(($_POST["identifiant"]))) || (!isset($_POST["mdp"])) || (empty($_POST["mdp"]))) {
    header('Location: http://localhost:8000/formConnection.html');
    $msgErreur = 'Identifiant ou Mot de passe incorrect.';
    exit();
} else {
    $identifiant = htmlspecialchars($_POST["identifiant"]);
    $mdp = htmlspecialchars($_POST["mdp"]);
}

try {
    $db = new PDO('mysql:host=localhost;dbname=bdd_post-it;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$request = $db->prepare("SELECT id, mot_de_passe_hashed FROM utilisateur WHERE identifiant = ?");
$request->execute([$identifiant]);
$utilisateur = $request->fetch(PDO::FETCH_ASSOC);

if ($utilisateur && password_verify($mdp, $utilisateur['mot_de_passe_hashed'])) {
    $_SESSION['identifiant'] = $identifiant;
    $_SESSION['utilisateur_id'] = $utilisateur['id'];
    header('Location: http://localhost:8000/dashbord.php');
    exit();
} else {
    $msgErreur = 'Identifiant ou Mot de passe incorrect.';
    header('Location: http://localhost:8000/formConnection.html');
    exit();
}
