<?php

namespace Kernel\Mail\Validation;

class Validate
{
	protected $validation;

	public function __construct(ValidationInterface $validation)
	{
		$this->validation = $validation;
	}

	public function validate(): string
	{
		return  $this->validation->validate();
	}
}