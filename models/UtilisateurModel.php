<?php
/* */

class UtilisateurModel extends Modele {

    /**
     * Cette methode sert a selectionner un utilisateur de la BD
     */
    public function getUsager($courriel){
        $result = $this->database->fetch("SELECT * FROM utilisateur WHERE uti_courriel = '$courriel'");
        return $result;
    }
    
    /**
     * Cette methode sert a creer un utilisateur dans la BD
     * @param Array $data Tableau des donnes representant un utilisateur dans la BD
     */
    public function creerUsager($data){
            
        $this->database->query('INSERT INTO utilisateur', [ 
			'uti_courriel' => trim($data['uti_courriel']),
			'uti_mdp' => password_hash(trim($data['uti_mdp']), PASSWORD_DEFAULT),
			'uti_nom' => trim($data['uti_nom']),
			'uti_prenom' => trim($data['uti_prenom']),
			'uti_rol_id' => '1'
        ]);
 
        return $this->database->getInsertId();
    }
 

}


?>