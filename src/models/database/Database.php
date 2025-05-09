<?php
	/* 
		Connexion à la base de données 
		@Author Yves P.
		@Version 1.0
		@Date Création: 16/08/2023
		@Dernière modification: 09/05/2025
	*/

	class Database 
	{
		private $host;
		private $username;
		private $password;
		private $dbname;
		private $connection;

		public function __construct() 
		{
			$_ENV['APP_ENV'] = $_SERVER['SERVER_NAME'] === 'localhost' ? 'local' : 'production';

			if ($_ENV['APP_ENV'] === 'local') {
				$this->host     = $_ENV['LOCAL_DB_HOST'];
				$this->username = $_ENV['LOCAL_DB_USER'];
				$this->password = $_ENV['LOCAL_DB_PASS'];
				$this->dbname   = $_ENV['LOCAL_DB_NAME'];
			}
			else
			{
				$this->host     = $_ENV['PROD_DB_HOST'];
				$this->username = $_ENV['PROD_DB_USER'];
				$this->password = $_ENV['PROD_DB_PASS'];
				$this->dbname   = $_ENV['PROD_DB_NAME'];
			}

			try {
				$dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
				$this->connection = new PDO($dsn, $this->username, $this->password);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				die("Erreur de connexion à la base de données : " . $e->getMessage());
			}
		}

		public function getConnection()
		{
			return $this->connection;
		}
	}
?>