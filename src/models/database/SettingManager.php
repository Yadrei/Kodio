<?php
	/*
		Class Manager pour les relations avec la DB sur la table SETTINGS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 18/09/2023
		@Last update: 18/09/2023
	*/

	class SettingManager
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add() {

		}

		private function Update() {

		}

		// Méthodes publiques
		public function Save() {

		}

		public function CheckMaintenance() {
			$query = $this->db->prepare('SELECT VALUE value FROM SETTINGS WHERE R_SETTING = "MAINT"');

			$query->execute();

			return $query->fetchColumn();
		}

		public function Maintenance($maintenance) {
			$query = $this->db->prepare('UPDATE SETTINGS SET VALUE = :maintenance WHERE R_SETTING = "MAINT"');

			$query->bindParam(':maintenance', $maintenance, PDO::PARAM_STR);

			$query->execute();
		}
	}
?>