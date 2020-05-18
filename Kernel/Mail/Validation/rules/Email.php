<?php

namespace Kernel\Mail\Validation\rules;

use  Kernel\Mail\Validation\ValidationInterface;

class Email implements ValidationInterface
{
	protected $value, $name;

	public function __construct($value, $name)
	{
		$this->value = $value;
		$this->name = $name;
	}

	public function validate(): string
	{
		if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {

			return	"$this->name is not a valid email";
		}

		return '';
	}
}