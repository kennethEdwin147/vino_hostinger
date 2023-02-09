<?php
class Compte
{    
    /**
     * Contrôle l'affichage de la page profil de l'utilisateur
     *
     * @return void
     */
    public function compte() 
    {
        $this->render('compte/compte.html', [
           
        ]);   
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
