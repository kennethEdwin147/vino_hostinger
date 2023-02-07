<?php
 class Cellier {

    public function toutCellier() {
        $model = new CellierModel();

        $this->render('cellier/cellier.html', [
            'celliers' => $model->getAllCelliers($_SESSION['uti_id']),
            'message' => $_GET['message'] ?? 'ouii'

        ]);
    }
    
    /**
     * Contrôle l'affichage d'un cellier de l'utilisateur
     *
     * @param  mixed $cel_id
     * @return void
     */
    public function unCellier($cel_id)
    {

        $bouteilles = (new CellierModel())->getBouillesDunCellier($cel_id);
        $nom = (new CellierModel())->getNomCellier($cel_id);       
        $this->render('cellier/un.html', [
            'nom' => $nom['cel_nom'],
            'bouteilles' => $bouteilles,
            'message' => $_GET['message'] ?? 'ouii'
        ]); 
    }
   
    /**
     * L'ajout d'un nouveau cellier 
     *
     * @return void
     */
    public function ajout(){
        $model = new CellierModel();

        $model -> ajoutNouvCellier($_SESSION['uti_id'], $_POST['ajoutCellier']);

        header("Location: /cellier/cellier?message=ajouter");
    }

    
    /**
     * Supprimer un cellier
     *
     * @return void
     */
    public function supprim($cel_id){
       $model = new CellierModel();

       $model -> supprimerCellier($cel_id);

       header("Location: /cellier/cellier?message=supprimer");
    }

        
    /**
     * Modifier le nom d'un cellier
     *
     * @return void
     */
    public function modif(){
        $model = new CellierModel();

        $model -> modifierCellier($_POST['cel_id'], $_POST['cel_nom']);

        header("Location: /cellier/cellier?message=modifier");
    }
    
    /**
     * Route s'assurant que l'usager soit authentifié
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

?>