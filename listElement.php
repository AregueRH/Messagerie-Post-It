<?php

if 
    (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

class ListElement 
    {
        private $id;
        private $liste_id;
        private $db;
        private $contenu;

        public function __construct($db, $liste_id = null, $contenu = null) 
            {
                $this->db = $db;
                $this->liste_id = $liste_id;
                $this->contenu = $contenu;
            }

        public function ajouterElement() 
            {
                try 
                    {
                        $requete = $this->db->prepare("INSERT INTO liste_element (liste_id, contenu) VALUES (:liste_id, :contenu)");
                        $requete->bindparam(':liste_id', $this->liste_id);
                        $requete->bindparam(':contenu', $this->contenu);
                        return $requete->execute();
                    } 
                    
                catch (PDOException $e) 
                    {
                        echo "Erreur : " . $e->getMessage();
                        return false;
                    }
            }

        public function supprimerElement()
            {
                $requete = $this->db->prepare("DELETE FROM liste_element WHERE id = ?");
                return $requete->execute([$this->id]);
            }
    }