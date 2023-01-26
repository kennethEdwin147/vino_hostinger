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
     * requête SELECT d'un cellier
     *
     * @param  mixed $id_utilisateur
     * @return void
     */
    public function getCellier($id_utilisateur)
    {
        return $this->database->fetch('SELECT * FROM cellier WHERE cel_uti_id  = ?', $id_utilisateur);
    }
}

?>


