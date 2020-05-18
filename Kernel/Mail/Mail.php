<?php

namespace Kernel\Mail;

use Kernel\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail extends PHPMailer
{
	public function __construct($exceptions, $body = '')
	{
		parent::__construct($exceptions);

		//Show debug output
		$this->SMTPDebug = false;


		// Set a default 'From' address ('noreply@localhost.com', 'Default User');
		$this->setFrom(Config::get('mail.from.address'), Config::get('mail.from.name'));

		// Specify main and backup SMTP servers 
		$this->Host = Config::get('mail.mailers.smtp.host');

		// SMTP port 
		$this->Port = Config::get('mail.mailers.smtp.port');

		// Enable TLS encryption,`ssl` also accepted
		$this->SMTPSecure = Config::get('mail.mailers.smtp.encryption');

		// SMTP username 
		$this->Username = Config::get('mail.mailers.smtp.username');

		// SMTP password 
		$this->Password = Config::get('mail.mailers.smtp.password');

		//Send via SMTP
		$this->isSMTP();

		// Enable SMTP authentication
		$this->SMTPAuth = true;

		// Enable HTML Email
		$this->isHTML(true);

		//Set an HTML and plain-text body, import relative image references
		$this->msgHTML($body, './img/');

		//Inject a new debug output handler
		$this->Debugoutput = function ($str, $level) {
			echo "Debug level $level; message: $str\n";
		};
	}

	public function sendMail()
	{
		parent::send();
	}
}