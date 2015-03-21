<?php

namespace Vol2223\Ryvalidator\Context;

use Vol2223\Ryvalidator\Validator\EnumValidator;
use Vol2223\Ryvalidator\Validator\IntegerValidator;
use Vol2223\Ryvalidator\Validator\StringValidator;

class ValidationPackContext
{
	/**
	 * @var \Vol2223\Ryvalidator\Validator\EnumValidator
	 */
	private $enumValidator;

	/**
	 * @var \Vol2223\Ryvalidator\Validator\IntegerValidator
	 */
	private $integerValidator;

	/**
	 * @var \Vol2223\Ryvalidator\Validator\StringValidator
	 */
	private $stringValidator;

	/**
	 * @param \Vol2223\Ryvalidator\Validator\EnumValidator    $enumValidator
	 * @param \Vol2223\Ryvalidator\Validator\IntegerValidator $integerValidator
	 * @param \Vol2223\Ryvalidator\Validator\StringValidator  $stringValidator
	 */
	public function __construct(
		EnumValidator    $enumValidator = null,
		IntegerValidator $integerValidator = null,
		StringValidator  $stringValidator = null
	) {
		$this->enumValidator      = is_null($enumValidator) ? new EnumValidator() : $enumValidator;
		$this->integerValidator   = is_null($integerValidator) ? new IntegerValidator() : $integerValidator;
		$this->stringValidator    = is_null($stringValidator) ? new StringValidator() : $stringValidator;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validator\EnumValidator
	 */
	public function enumValidator()
	{
		return $this->enumValidator;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validator\IntegerValidator
	 */
	public function integerValidator()
	{
		return $this->integerValidator;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validator\StringValidator
	 */
	public function stringValidator()
	{
		return $this->stringValidator;
	}
}
