<?php
class Utilisateur
{
    public function accueil()
    {  
        if (isset($_SESSION['utilisateur'])) {
            header('location: /bouteille/cellier');
            exit();
        }
    
        $this->render('utilisateur/connexion.html',[
            'message'=> $_GET['message'] ?? 'nop',
        ]);

    }

    public function inscription()
    {  
        if (isset($_SESSION['utilisateur'])) {
            header('location: /bouteille/cellier');
            exit();
        }
         
        $this->render('utilisateur/inscription.html');  
    }

    
    /**
     * creation du compte et du cellier de l'utilisateur
     *
     * @return void
     */
    public function creation()
    {
        $model = new UtilisateurModel();

        // confirmer si courriel existe deja 
        $possibleUser = $model->getUsager($_POST['uti_courriel']);
        if (! isset($possibleUser)) {
             $id_utilisateur = $model->creerUsager($_POST);
             (new CellierModel())->insertion($id_utilisateur);
             header("Location: /utilisateur/accueil?message=compte"); 
             exit();
        } else {
            $this->render('utilisateur/inscription.html', [
                "erreur" => 'Ce courriel est deja utilisé'
            ]); 
        }  
    }

    /**
     * Tente l'ouverture d'une connexion : si réussi, redirige vers  
     * et sinon, réaffiche le formulaire de connexion avec un message d'erreur.
     */
    public function connexion()
    {
        $erreur = false;
        $courriel = $_POST['uti_courriel'];
        $mdp = $_POST['uti_mdp'];
        $model = new UtilisateurModel();

        $utilisateur = $model->getUsager($courriel); 
         if(!$utilisateur || !password_verify($mdp, $utilisateur['uti_mdp'])) {
            $erreur = "Mauvaise combinaison courriel/mot de passe";
        }

        if(!$erreur) {
            $_SESSION['utilisateur'] = $utilisateur;
            $_SESSION['uti_id'] = $_SESSION['utilisateur']['uti_id'];
            header("Location: /bouteille/cellier"); 
            exit();
        }
        else {
            $this->render('utilisateur/connexion.html', [
                "erreur" => $erreur
            ]);  
        }
     }

     public function deconnexion()
     {
        unset($_SESSION['utilisateur']);
        unset($_SESSION['uti_id']);
        header("Location: /utilisateur/accueil"); 
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



?>