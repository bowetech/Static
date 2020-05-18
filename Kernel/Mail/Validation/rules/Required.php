<?php

namespace Kernel\Mail\Validation\rules;

use  Kernel\Mail\Validation\ValidationInterface;


class Required implements ValidationInterface
{
	protected $value, $name;

	public function __construct($value, $name)
	{
		$this->value = $value;
		$this->name = $name;
	}

	public function validate(): string
	{
		if (strlen($this->value) === 0) {
			return 	"$this->name field is required.";
		}

		return '';
	}
}