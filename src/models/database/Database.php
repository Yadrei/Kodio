<?php
	/* 
		Connexion à la base de données 
		@Author Yves P.
		@Version 1.0
		@Date Création: 16/08/2023
		@Dernière modification: 16/08/2023
	*/

	class Database 
	{
		private $host = 'localhost';
		private $username = 'root';
		private $password = 'root';
		private $dbname = 'uranus';

		private $connection;

		public function __construct() 
		{
			$this->connection = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->username, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function getConnection()
		{
			return $this->connection;
		}
	}
?>