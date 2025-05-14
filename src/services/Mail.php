<?php
	/* 
		Classe gérant la création et l'envoi de mails
	    @Author Yves P.
	    @Version 1.1
	    @Date création : 09/04/2021
	    @Dernière modification : 14/05/2025
  	*/

	require '../libs/PHPMailer/PHPMailer.php';
	require '../libs/PHPMailer/SMTP.php';
	require '../libs/PHPMailer/Exception.php';
	  
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	Class Mail {
		protected $mailer;

		public function __construct($to = '', $replyTo = '', $replyToName = '', $subject, $body) {	
			$this->mailer = new PHPMailer(true);
			
			// Pour l'encodage des caractères spéciaux
			$this->mailer->CharSet = 'UTF-8';
			$this->mailer->Encoding = 'base64';

			// Configuration SMTP
			$this->mailer->isSMTP();
			$this->mailer->SMTPAuth = true;
			$this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

			$this->mailer->Host = $_ENV['SMTP_HOST'];
			$this->mailer->Username = $_ENV['SMTP_USER'];
			$this->mailer->Password = $_ENV['SMTP_PASS'];
			$this->mailer->Port = $_ENV['SMTP_PORT'];

			$this->mailer->isHTML(true);

			// Envoyeur est toujours no-reply
			$this->mailer->From = 'no-reply@shoku.be';
			$this->mailer->setFrom('no-reply@shoku.be', 'No Reply');
			$this->mailer->FromName = 'No Reply';

			// Si on vient d'un formulaire de contact, l'adresse à qui répondre est spécifiée...
			if (!empty($replyTo)) {
				// ... Donc le destinataire original est contact
				$this->mailer->addAddress('contact@shoku.be');

				// Et on répond à l'adresse indiquée
				$this->mailer->addReplyTo($replyTo, $replyToName);
			} elseif (!empty($to)) // Sinon, on vient d'ailleurs du formulaire, donc faut envoyer à la personne concernée simplement
				$this->mailer->addAddress($to);

			// Contenu du mail
			$this->mailer->Subject = $subject;
			$this->mailer->Body = $body;
		}

		public function Send(): bool {
			try {
				$this->mailer->send();
				return true;
			} catch (Exception $e) {
				error_log("Erreur envoi mail : " . $this->mailer->ErrorInfo);
				return false;
			}
		}
	}
?>