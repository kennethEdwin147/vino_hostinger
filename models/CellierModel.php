<?php
class CellierModel extends Modele {
    	    
    /**
     * requête INSERT d'un cellier (suite création du compte)
     *
     * @param  mixed $id_utilisateur
     * @return void
     */
    public function insertion($id_utilisateur)
    {
        $this->database->query('INSERT INTO cellier ', [ 
            'cel_nom' => "Cellier # $id_utilisateur",
            'cel_uti_id' => $id_utilisateur
        ]);
        
        return $this->database->getInsertId(); 
    }
    

    /**
     * Requête SELECT NOM des celliers
     *
     * @param  mixed $cel_id
     * @return void
     */
    public function getNomCellier($cel_id)
    {
        return  $this->database->fetch('SELECT cel_nom FROM cellier WHERE cel_id = ?', $cel_id);
    }

    
    /**
     * Requête SELECT de tous les celliers de l'utilisateur
     *
     * @param  mixed $id_utilisateur
     * @return void
     */
    public function getAllCelliers($id_utilisateur)
    {
        return $this->database->fetchAll('SELECT * FROM cellier  WHERE cel_uti_id  = ? ORDER BY cel_id DESC', $id_utilisateur);
    }
    
    /**
     * Requête SELECT des bouteilles d'un cellier de l' utilisateur
     *
     * @param  int $cel_id - L'ID du cellier
     * @return array[] liste de bouteilles d'un cellier de l'utilisateur
     */
    public function getBouillesDunCellier($cel_id) 
    {
        return $this->database->fetchAll(
			"SELECT 
				bouteille_du_cellier.*,
				bouteille_saq.*			
			from cellier
			INNER JOIN bouteille_du_cellier ON cellier.cel_id = bouteille_du_cellier.bdc_cel_id
			INNER JOIN bouteille_saq on bouteille_du_cellier.bdc_bout_id = bouteille_saq.bout_id 
            WHERE  %and ORDER BY bouteille_du_cellier.bdc_id DESC", [
				'cellier.cel_uti_id' => $_SESSION['uti_id'],
				'cellier.cel_id' => $cel_id
			]);
    }
    
        
    /**
     * requête AJOUTER un nouveau cellier
     *
     * @param  mixed $id_utilisateur
     * @param  mixed $cel_nom
     * @return void
     */
    public function ajoutNouvCellier($id_utilisateur, $cel_nom) 
    {
        $this->database->query('INSERT INTO cellier', [
            'cel_nom' => $cel_nom,
            'cel_uti_id'=> $id_utilisateur
        ]);
    }

        
    /**
     * requête SUPPRESSION d'un cellier
     *
     * @return void
     */
    public function supprimerCellier($cel_id)
    {
        $this->database->query('DELETE FROM cellier WHERE cel_id = ?', $cel_id);
    }
    
    
    /**
     * requete MODIFIER du nom d'un Cellier
     *
     * @param  mixed $cel_id
     * @param  mixed $cel_nom
     * @return void
     */
    public function modifierCellier($cel_id, $cel_nom)
    {
        $this->database->query('UPDATE cellier SET', [
            'cel_nom' => $cel_nom,
        ], 'WHERE cel_id = ?', $cel_id);
    }
}

?>


