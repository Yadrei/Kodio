<?php
	/* 
		Classe gérant la création et l'envoi de mails
	    @Author Yves P.
	    @Version 1.0
	    @Date création : 09/04/2021
	    @Dernière modification : 04/05/2021
  	*/

	Class Mail {
		// Attributs
		protected $header,
				  $receiver,
				  $subject,
				  $content;

		public function __construct($receiver, $subject, $content) {	
			$this -> receiver = $receiver;
			$this -> subject = $subject;

			$this -> header = array(
				'MIME-Version' => '1.0',
				'Content-type' => 'text/html; charset=utf-8',
 				'From' => 'Shoku Studio<noreply@shoku.be>',
				'Reply-To' => 'noreply@shoku.be',
				'X-Mailer' => 'PHP/'.phpversion(),
				'List-Unsubscribe' => 'noreply@shoku.be'
			);
			
			$this -> content = '
			<html>
				<head>
					<title>'. $this -> subject.'</title>
				</head>
				<body>'.$content.'
				</body>
			</html>';
		}

		public function Send() {
			return mb_send_mail($this -> receiver, $this -> subject, $this -> content, $this -> header);
		}
	}
?>