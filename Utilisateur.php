<?php
    class Utilisateur {
        public $id;
        public $identifiant;

        public function __construct($id, $identifiant)
            {
                $this->id = $id;
                $this->identifiant = $identifiant;
            }

        public static function getUtilisateurConnecte() 
            {
                if 
                    (isset($_SESSION['identifiant'], $_SESSION['utilisateur_id']))
                        {
                            return new Utilisateur($_SESSION['utilisateur_id'], $_SESSION['identifiant']);
                        }
                return null;
            }
    }
?>