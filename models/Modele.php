<?php
class Modele {
	
    protected $database;
		
	/**
	 * Récupère une instance de la connexion à la base de données.
	 *
	 * @return void
	 */
	function __construct ()
	{
		$this->database = new Dibi\Connection([
			'driver'   => 'mysqli',
			'host'     => HOST,
			'username' => USER,
			'password' => PASSWORD,
			'database' => DATABASE,
		]);
	
	}
	
}



?>