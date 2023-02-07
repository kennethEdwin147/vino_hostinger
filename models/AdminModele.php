<?php

class AdminModele extends Modele {

 /**
     * Cette methode sert a selectionner un utilisateur de la BD
     */
    public function getAdmin(){
        $result = $this->database->fetch("SELECT * FROM utilisateur WHERE uti_rol_id = '2'");
        return $result;
    }

    /**
     * Affiche le nombre de usagers total dans la BD
     */
    public function getNombreUsagers(){
        $result = $this->database->fetch("SELECT COUNT(*) as nbUsager
        FROM utilisateur;");
        return $result['nbUsager'];
    }
    /**
     * Affiche le nombre total de bouteilles dans la BD 
     */
    public function getNombreBouteilleTotal(){
        $result = $this->database->fetch("SELECT COUNT(*) as bouteilleTotal FROM `bouteille_du_cellier`");
        return $result['bouteilleTotal'];
    }
    /**
     * Affiche le nombre de celliers total dans la BD
     */
    public function getNombreCelliers(){
        $result = $this->database->fetch("SELECT COUNT(*) as nbCelliers
        FROM cellier;");
        return $result['nbCelliers'];
    }


    public function findNbParCelliers($id){
        $result = $this->database->fetch("SELECT COUNT(*) as nb
        FROM bouteille_du_cellier WHERE bdc_cel_id = '$id';");
        return $result;
    }

    
    public function getNbParCelliers(){
        $cellier = $this->database->fetchAll("SELECT *
        FROM cellier");
        foreach( $cellier as $key => $value ){
            $value['nombreBouteille'] = $this->findNbParCelliers($value['cel_id'])['nb'];

        }
        return $cellier;
    }

    /**
     * Affiche le nombre de bouteille ajouter par l'usager et son cellier
     */
    public function findBouteilleParUsers(){
        $result = $this->database->fetchAll("SELECT COUNT(bouteille_du_cellier.bdc_bout_id) as nbBouteille,cellier.cel_nom,utilisateur.uti_courriel,utilisateur.uti_nom,utilisateur.uti_prenom,utilisateur.uti_rol_id FROM `cellier` JOIN bouteille_du_cellier on cellier.cel_id = bouteille_du_cellier.bdc_cel_id join utilisateur on cellier.cel_uti_id = utilisateur.uti_id group by utilisateur.uti_courriel;
        ");
    
    return $result  ;
    }
    /**
     * Affiche le nombre de bouteilles ajouter il ya 24h
     */
    public function getNbBouteille24h(){
        $result = $this->database->fetch("SELECT COUNT(*) as nb24 FROM bouteille_du_cellier WHERE bdc_date_achat > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        return $result['nb24'];
    }
}

?>