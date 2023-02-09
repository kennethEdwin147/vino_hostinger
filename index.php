<?php
require_once __DIR__. '/dataconf.model.php';
require __DIR__ . '/vendor/autoload.php';
session_start();

// Create Router instance
$router = new \Bramus\Router\Router();


$router->get('/', 'Utilisateur@accueil');
$router->get('/utilisateur/accueil', 'Utilisateur@accueil');
$router->get('/utilisateur/inscription', 'Utilisateur@inscription');

$router->post('/utilisateur/connexion', 'Utilisateur@connexion');
$router->get('/utilisateur/deconnexion', 'Utilisateur@deconnexion');
$router->post('/utilisateur/creation', 'Utilisateur@creation'); 

$router->get('/admin/dashboard', 'Admin@dashboard');
$router->get('/admin/connexion', 'Admin@admin');

/* route s'assurant que l'usager soit authentifié */
$router->before('GET|POST', '/bouteille/.*', 'Bouteille@protection');

$router->get('/bouteille/cellier', 'Bouteille@cellier');
$router->get('/bouteille/nouveau', 'Bouteille@nouveau');
$router->post('/bouteille/insertion', 'Bouteille@insertion');

$router->post('/bouteille/quantite/boire/', 'Bouteille@boireQuantiteBouteille');
$router->post('/bouteille/quantite/ajouter/', 'Bouteille@ajouterQuantiteBouteille');

$router->post('/bouteille/api/select', 'Bouteille@apiSelect');


$router->get('/bouteille/detail/{id}', 'Bouteille@detailBouteille');
$router->post('/bouteille/modifier', 'Bouteille@modifierBouteille');


$router->get('/bouteille/supprimer/{id}', 'Bouteille@supprimer');
$router->post('/bouteille/recherche', 'Bouteille@recherche');

/* route compte */
$router->before('GET|POST', '/compte/.*', 'Bouteille@protection');
$router->get('/compte/compte', 'Compte@compte');


/* Liste des Celliers */

/* route s'assurant que l'usager soit authentifié */
$router->before('GET|POST', '/cellier/.*', 'Cellier@protection');

$router->get('/cellier/cellier', 'Cellier@toutCellier');
$router->get('/cellier/un/{id}', 'Cellier@unCellier');

$router->post('/cellier/ajout', 'Cellier@ajout');
$router->get('/cellier/supprim/{id}','Cellier@supprim');
$router->post('/cellier/modif', 'Cellier@modif');

$router->run();


// SAQ
/* for ($i = 1; $i < 4 ; $i++) { 
    (new BouteilleSAQModel())->fetch_bottle_from_SAQ($i, 4 ); 
} */
  