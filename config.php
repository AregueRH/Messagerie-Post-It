<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $db = new PDO('mysql:host=localhost;dbname=bdd_post-it;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // setAttribute permet d'activer le mode erreur

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
