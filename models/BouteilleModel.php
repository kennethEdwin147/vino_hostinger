<?php
class BouteilleModel extends Modele {
    	
	/**
	 * Requête SELECT des bouteilles d'un cellier de l' utilisateur
	 *
	 * @return array[] liste de bouteilles d'un cellier de l'utilisateur
	 */
	public function getBouteillesCellier()
	{
		return $this->database->fetchAll(
			"SELECT 
				bouteille_du_cellier.*,
				bouteille_saq.*			
			from cellier
			INNER JOIN bouteille_du_cellier ON cellier.cel_id = bouteille_du_cellier.bdc_cel_id
			INNER JOIN bouteille_saq on bouteille_du_cellier.bdc_bout_id = bouteille_saq.bout_id 
			WHERE cellier.cel_uti_id = ?",
			$_SESSION['uti_id']
		);
	}
	
	/**
	 * Requête SELECT d'une bouteille d'un cellier
	 *
	 * @param  int $id_cellier id du cellier
	 * @return array un cellier
	 */
	public function getUneBouteilleCellier($id_bouteille)
	{
		return $this->database->fetch(
			"SELECT 
				bouteille_du_cellier.*,
				bouteille_saq.*			
			from cellier
			INNER JOIN bouteille_du_cellier ON cellier.cel_id = bouteille_du_cellier.bdc_cel_id
			INNER JOIN bouteille_saq on bouteille_du_cellier.bdc_bout_id = bouteille_saq.bout_id 
			WHERE  %and ", [
				'cellier.cel_uti_id' => $_SESSION['uti_id'],
				'bouteille_du_cellier.bdc_id' => $id_bouteille
			]);
	}
	
	
	
	/**
	 * Cette méthode ajoute une ou des bouteilles au cellier
	 * 
	 * @param Array $data Tableau des données représentants la bouteille.
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function insertion($data)
	{
		$this->database->query('INSERT INTO bouteille_du_cellier ', [ 
			'bdc_bout_id' => $data['bdc_bout_id'],
			'bdc_date_achat' => $data['bdc_date_achat'],
			'bdc_garde_jusqua' => $data['bdc_garde_jusqua'],
			'bdc_notes' => $data['bdc_notes'],
			'bdc_quantite' => $data['bdc_quantite'],
			'bdc_millesime' => $data['bdc_millesime'],
			'bdc_cel_id' => $data['bdc_cel_id']
		]);
        
		return $this->database->getInsertId();
	}
	
		
	/**
	 * Requete UPDATE d'une bouteille
	 *
	 * @param  mixed $data Tableau des données représentants la bouteille.
	 * @return void
	 */
	public function modifierBouteille($data)
	{
		$this->database->query('UPDATE bouteille_du_cellier SET', [
			'bdc_date_achat' => $data['bdc_date_achat'],
			'bdc_garde_jusqua' => $data['bdc_garde_jusqua'],
			'bdc_notes' => $data['bdc_notes'],
			'bdc_quantite' => $data['bdc_quantite'],
			'bdc_millesime' => $data['bdc_millesime']
		], 'WHERE bdc_id = ?', $data['bdc_id']);

		return $this->database->getAffectedRows();
	}
	
	
	/**
	 * Cette méthode change la quantité d'une bouteille en particulier dans le cellier
	 * 
	 * @param int $id id de la bouteille
	 * @param int $nombre Nombre de bouteille a ajouter ou retirer
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function modifierQuantiteBouteilleCellier($id, $nombre)
	{
		$requete = "UPDATE bouteille_du_cellier SET bdc_quantite = GREATEST(bdc_quantite + ". $nombre. ", 0) WHERE bdc_id = ". $id;
        $res = $this->database->query($requete);
		return $res->getRowCount();
	}
}




?>


