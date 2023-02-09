<?php
class BouteilleSAQModel extends Modele
{
    
    /**
     * Type de Bouteile
     *
     * @var int
     */
    private $type_id = 0;

    /**
	 * Requête SELECT de la liste de bouteilles SAQ
	 *
	 * @return array[] liste de bouteille SAQ 
	 */
	public function getListeBouteille()
	{
		return $this->database->fetchAll('SELECT * FROM bouteille_saq');
	}
    
    /**
     * Requete INSERT d'une bouteille de la SAQ
     *
     * @param  mixed $data
     * @return void
     */
    public function addBouteilleSAQ($data) 
    {
        extract($data);
        $this->database->query('INSERT INTO bouteille_saq ', [ 
            'bout_nom' => $bout_nom,
            'bout_image' => $bout_image,
            'bout_code_saq' => $bout_code_saq,
            'bout_pays' => $bout_pays,
            'bout_description' => $bout_description,
            'bout_prix_saq' => $bout_prix_saq,
            'bout_url_saq' => $bout_url_saq,
            'bout_url_img' => $bout_url_img,
            'bout_format' => $bout_format,
            'bout_type_id' => $bout_type_id
        ]);
        
        return $this->database->getInsertId(); 
    }

    /**
	 * Cette méthode permet de retourner les résultats de recherche pour la fonction d'autocomplete de l'ajout des bouteilles dans le cellier
	 * 
	 * @param string $nom La chaine de caractère à rechercher
	 * @param integer $nb_resultat Le nombre de résultat maximal à retourner.
	 * 
	 * @throws Exception Erreur de requête sur la base de données 
	 * 
	 * @return array id et nom de la bouteille trouvée dans le catalogue
	 */
       
	public function autocomplete($nom, $nb_resultat=10)
	{
		
		$rows = Array();
		/* $nom = $this->_db->real_escape_string($nom); */
		$nom = preg_replace("/\*/","%" , $nom);
		 
		$requete ='SELECT bout_id , bout_nom FROM bouteille_saq where LOWER(bout_nom) like LOWER("%'. $nom .'%") LIMIT 0,'. $nb_resultat; 
		return $this->database->fetchAll($requete);
	}

    
    /**
     * Récupère les Bouteilles de la SAQ
     *
     * https://www.scraperapi.com/blog/simple-guide-to-building-a-php-web-scraper-using-goutte-for-beginners/
     * 
     * @param  mixed $page_id l'id de la page
     * @param  mixed $type_vin 
     * - 1 pour rouge
     * - 2 pour blanc
     * - 3 pour rose
     * - 4 pour orange
     * @return void
     */
    public function fetch_bottle_from_SAQ($page_id, $type_vin)
    {
        $client = new Goutte\Client();

        /* Liens selon le type de bouteilles */
        $indexLien = [
            '1' => 'https://www.saq.com/fr/produits/vin/vin-rouge?p='.$page_id.'&product_list_limit=96',
            '2' => 'https://www.saq.com/fr/produits/vin/vin-blanc?p='.$page_id.'&product_list_limit=96',
            '3' => 'https://www.saq.com/fr/produits/vin/vin-rose?p='.$page_id.'&product_list_limit=96',
            '4' =>  'https://www.saq.com/fr/produits/vin-orange?p='.$page_id.'&product_list_limit=96',
        ];

        $crawler = $client->request('GET', $indexLien[$type_vin]);
        /* type_global */
        $this->type_id = $type_vin;

        $crawler->filter('.product-item-info')->each(function ($node) {
            $data['bout_nom'] = $node->filter('.product-item-link')->text();
            $data['bout_image'] = $node->filter('.product-image-photo')->attr('src');
            $data['bout_code_saq'] = $node->filter('.saq-code > span + span')->text();
            // prix
            $data['bout_prix_saq'] = $node->filter('span .price')->text();
            $data['bout_prix_saq'] = (double) str_replace(['$', ','], ['', '.'], $data['bout_prix_saq']);
            $data['bout_url_img'] = $node->filter('.product-image-photo')->attr('src');
            $data['bout_type_id'] = $this->type_id;
            $data['bout_url_saq'] = $node->filter('.product-item-photo')->attr('href');
            // Vin rouge  |  750 ml  |  Espagne
            $span_divider = $node->filter('.product-item-identity-format')->text();
            $data['bout_description'] = trim($span_divider);
    
            $span_divider = explode('|', $span_divider);
            $data['bout_format'] = trim($span_divider[1]);
            $data['bout_pays'] =  trim($span_divider[2]);
    
            // à supprimer
            echo '<pre>';
            print_r($data);
            echo '</pre>';

            $this->addBouteilleSAQ($data);
        });
    }
 
}
