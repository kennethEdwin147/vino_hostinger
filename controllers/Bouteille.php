<?php
class Bouteille
{
    public function protection()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('location: /utilisateur/accueil');
            exit();
        } 
    } 

    /**
     * Affiche la page du cellier de l'utilisateur
     *
     * @return void
     */
    public function cellier()
    {
      
        $bouteilles = (new BouteilleModel())->getBouteillesCellier();
        $this->render('bouteille/cellier.html', [
            'bouteilles' => $bouteilles,
            'message' => $_GET['message'] ?? 'ouii'
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
        $cellierUtilisateur = (new CellierModel())->getCellier($_SESSION['uti_id']);

        $this->render('bouteille/nouveau.html',[
            'bouteillesSAQ' => $bouteillesSAQ,
            'cellierUtilisateur' => $cellierUtilisateur 
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
        header("Location: /bouteille/cellier?message=ajouter");
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
        $result['bdc_date_achat'] = str_replace('.000000', '', $result['bdc_date_achat']);
        $this->render('bouteille/detail.html',[
            'resultatDetail' => $result   
        ]);
    }

    public function modifierBouteille()
    {
        (new BouteilleModel())->modifierBouteille($_POST);
        header("Location: /bouteille/cellier?message=modifier");
        exit();
    }

    
    public function ajouterQuantiteBouteille() {
        $body = json_decode(file_get_contents('php://input'));
        $bte = new BouteilleModel();
		$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
        echo json_encode($resultat);
    }
   

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

    public function supprimer($id_bouteille)
    {
        (new BouteilleModel())->supprimer($id_bouteille);
        header("Location: /bouteille/cellier?message=supprimer");
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
