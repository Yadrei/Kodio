<?php
	/*
		Class Manager pour les relations avec la DB sur la table SETTINGS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 18/09/2023
		@Last update: 19/03/2025
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

		public function CheckComments() {
			$query = $this->db->prepare('SELECT VALUE value FROM SETTINGS WHERE R_SETTING = "COM"');

			$query->execute();

			return $query->fetchColumn();
		}

		public function CheckCookies() {
			$query = $this->db->prepare('SELECT VALUE value FROM SETTINGS WHERE R_SETTING = "COOKIES"');

			$query->execute();

			return $query->fetchColumn();
		}

		public function CheckMaintenance() {
			$query = $this->db->prepare('SELECT VALUE value FROM SETTINGS WHERE R_SETTING = "MAINT"');

			$query->execute();

			return $query->fetchColumn();
		}

		public function Comments($comments) {
			$query = $this->db->prepare('UPDATE SETTINGS SET VALUE = :comments WHERE R_SETTING = "COM"');

			$query->bindParam(':comments', $comments, PDO::PARAM_STR);

			$query->execute();
		}

		public function Cookies($cookies) {
			$query = $this->db->prepare('UPDATE SETTINGS SET VALUE = :cookies WHERE R_SETTING = "COOKIES"');

			$query->bindParam(':cookies', $cookies, PDO::PARAM_STR);

			$query->execute();
		}

		public function GetSocial($network) {
			$query = $this->db->prepare('SELECT VALUE value FROM SETTINGS WHERE R_SETTING = :network');

		  	$query->bindParam(':network', $network, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Setting');
			$query->execute();

			$social = $query->fetch();

			$query->closeCursor();

			return $social;
		}

		public function Maintenance($maintenance) {
			$query = $this->db->prepare('UPDATE SETTINGS SET VALUE = :maintenance WHERE R_SETTING = "MAINT"');

			$query->bindParam(':maintenance', $maintenance, PDO::PARAM_STR);

			$query->execute();
		}

		public function UpdateSocials($network, $url) {
			$query = $this->db->prepare('UPDATE SETTINGS SET VALUE = :url WHERE R_SETTING = :network');

			switch ($network) {
				case 'facebook':
					$social = 'SOC_FB';
					break;
				case 'twitter':
					$social = 'SOC_TWT';
					break;
				case 'instagram':
					$social = 'SOC_INST';
			}

			$query->bindParam(':network', $social, PDO::PARAM_STR);
			$query->bindParam(':url', $url, PDO::PARAM_STR);
			$query->execute();
		}
	}
?>