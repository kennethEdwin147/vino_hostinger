<?php
class Bouteille
{
    /**
     *  Route s'assurant que l'usager soit authentifié
     *
     * @return void
     */
    public function protection()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('location: /utilisateur/accueil');
            exit();
        }
    }
    
    /**
     * Fonction qui permet la recherche en utilisant le filter
     *
     * @return void
     */
    public function recherche()
    {
        $model = new BouteilleModel();
        $dataBrut = $model->recherche();

        $resultat = array_filter($dataBrut, function($el) {

            $recherche = strtolower(trim($_POST['recherche']));

            if ( str_contains(strtolower($el['bout_nom']), $recherche)
                ||  str_contains(strtolower($el['bdc_quantite']), $recherche)
                ||  str_contains(strtolower($el['bdc_millesime']), $recherche)
                ||  str_contains(strtolower($el['bout_pays']), $recherche)
                ||  str_contains(strtolower($el['bout_description']), $recherche)
            ) {
                return  $el;
            }
            
        });


        // echo "<pre>";
        //  print_r($resultat);
        //  echo "</pre>";

        $this->render('bouteille/recherche.html', [
             'bouteilles' => $resultat
        ]);

    }

    /* trie */
    public function tri()
    {
        $tri = $_POST['tri'];
        $model = new BouteilleModel();
        print_r($tri);
        $resultat = $model->triNom($tri);
        $this->render('bouteille/cellier.html', [
            'bouteilles' => $resultat
        ]);

    }

    /**
     * Affiche la page d'ajout de bouteille
     *
     * @return void
     */
    public function nouveau()
    {
        $bouteillesSAQ = (new BouteilleSAQModel())->getListeBouteille();
        $listeCellier = (new CellierModel())->getAllCelliers($_SESSION['uti_id']);

        $this->render('bouteille/nouveau.html',[
            'bouteillesSAQ' => $bouteillesSAQ,
            'listeCellier' => $listeCellier,
            'id_cellier' => $_GET['id_cellier'] ?? 'aucun'
        ]);
    }


    /**
     * Gère la requête INSERT de bouteille
     *
     * @return void
     */
    public function insertion()
    {
        $bte = new BouteilleModel();
        /* Récupère l'id de la bouteille par son nom */
        $id_bouteille = $bte->getIdByName($_POST['nom_bouteille_saq'])['bout_id'] ?? '11';
        $_POST['bdc_bout_id'] = $id_bouteille;

        $cellier = $bte->insertion($_POST);
        $id_cellier = $_POST['bdc_cel_id'];
        header("Location: /cellier/un/$id_cellier?message=ajouter");
        exit();
    }

    /**
     * Affiche la page de modification d'une bouteille
     *
     * @param  mixed $id_bouteille id de la bouteille
     * @return void
     */
    public function detailBouteille($id_bouteille)
    {
        $model = new BouteilleModel();
        $result = $model->getUneBouteilleCellier($id_bouteille);
        echo '<pre>';
        print_r($result['bdc_date_achat'] );
        echo '</pre>';
        $result['bdc_date_achat'] = str_replace('00:00:00', '', $result['bdc_date_achat']);
        // à supprimer
        echo '<pre>';
        print_r($result['bdc_date_achat'] );
        echo '</pre>';
        $result['bdc_date_achat']= trim($result['bdc_date_achat']);
        $this->render('bouteille/detail.html',[
            'resultatDetail' => $result
        ]);
    }

    /**
     * Gère la requête UPDATE d'une bouteille
     *
     * @return void
     */
    public function modifierBouteille()
    {
        (new BouteilleModel())->modifierBouteille($_POST);
        $id_cellier = $_POST['bdc_cel_id'];
        header("Location: /cellier/un/$id_cellier?message=modifier");
        exit();
    }


    /**
     * Gère la requête UPDATE du nombre d'une bouteille
     *
     * @return void
     */
    public function ajouterQuantiteBouteille() {
        $body = json_decode(file_get_contents('php://input'));
        $bte = new BouteilleModel();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
        echo json_encode($resultat);
    }


    /**
     * Gère la requête UPDATE du nombre d'une bouteille
     *
     * @return void
     */
    public function boireQuantiteBouteille() {
        $body = json_decode(file_get_contents('php://input'));
        $bte = new BouteilleModel();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
        echo json_encode($resultat);
    }


    public function apiSelect()
    {
        $bte = new Bouteille();
        $body = json_decode(file_get_contents('php://input'));
        $listeBouteille = (new BouteilleSAQModel())->autocomplete($body->nom);
        echo json_encode($listeBouteille);
    }

    /**
     * Gère la requête DELETE d'une bouteille
     *
     * @param  mixed $id_bouteille
     * @return void
     */
    public function supprimer($id_bouteille)
    {
        (new BouteilleModel())->supprimer($id_bouteille);
        $id_cellier = $_GET['bdc_cel_id'];
        header("Location: /cellier/un/$id_cellier?message=supprimer");
        exit();
    }

    /**
     * Affiche la page demandée
     *
     * @param  string $file_name
     * @param  object|object[]
     * @return void
     */
    public function render($file_name, $data = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/template');
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
            'cache' => false
        ]);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        echo $twig->render($file_name , $data);
    }
}
