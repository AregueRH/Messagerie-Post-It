<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    class Liste {
        public $id;
        public $nom;
        private $db;

        public function __construct($db, $nom = null, $id = null)
            {
                $this->id = $id;
                $this->nom = $nom;
                $this->db = $db;
            }

        public function creationListe()
            {
                if 
                    (!isset($_SESSION['utilisateur_id']))
                        {
                            header('Location: http://localhost:8000/formConnection.html');
                            exit();
                        }
                try
                    {
                        $requete = $this->db->prepare("INSERT INTO liste (nom,utilisateur_id) Values (:nom, :utilisateur_id)");
                        $requete->bindParam(':nom', $this->nom);
                        $requete->bindParam(':utilisateur_id', $_SESSION['utilisateur_id']);
                        $requete->execute();

                        $this->id = $this->db->lastInsertId();
                        return true;
                    } 
                catch (PDOException $e) 
                    {
                        echo "Erreur : " . $e->getMessage();
                        return false;
                    }
            }

        public function supprimerListe()
            {
        var_dump($this->id);
                // if 
                //     (!isset($_SESSION['utilisateur_id'])) 
                //         {
                //             header('Location: http://localhost:8000/formConnection.html');
                //             exit();
                //         }

                    $requete = $this->db->prepare("DELETE FROM liste WHERE id = ? ");
                    return $requete->execute([$this->id]);
            }
}
?>