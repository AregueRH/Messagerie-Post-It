<?php
if 
    ((!isset($_POST["identifiant"])) || (empty($_POST["identifiant"])) || (!isset($_POST["motDePasse"])) || (empty($_POST["motDePasse"])))
        {
            $msgErreur = 'veuillez proposer un identifiant et un mot de passe valide';
            var_dump($msgErreur);
            header('Location: http://localhost:8000/formInscription.html');
            exit();
        }
else
        {
            $identifiant = htmlspecialchars($_POST["identifiant"]);
            $mdp = htmlspecialchars($_POST["motDePasse"]);
            $hashed_mdp = password_hash($mdp, PASSWORD_DEFAULT);
        }

try 
        {
            $db = new PDO ('mysql:host=localhost;dbname=bdd_post-it;charset=utf8', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            die('Erreur : '.$e->getMessage());
            
        }
        
$request = $db->prepare("INSERT INTO utilisateur (identifiant, mot_de_passe_hashed) VALUES (?,?) ");
$request->execute([$identifiant, $hashed_mdp]);

header('Location: http://localhost:8000/formConnection.html');
exit();
?>