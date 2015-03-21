<?php

namespace Vol2223\Ryvalidator\Context;

use Vol2223\Ryvalidator\Validation\EnumValidator;
use Vol2223\Ryvalidator\Validation\IntegerValidator;
use Vol2223\Ryvalidator\Validation\StringValidator;

class ValidationPackContext
{
	/**
	 * @var \Vol2223\Ryvalidator\Validation\EnumValidator
	 */
	private $enumValidator;

	/**
	 * @var \Vol2223\Ryvalidator\Validation\IntegerValidator
	 */
	private $integerValidator;

	/**
	 * @var \Vol2223\Ryvalidator\Validation\StringValidator
	 */
	private $stringValidator;

	/**
	 * @param \Vol2223\Ryvalidator\Validation\EnumValidator    $enumValidator
	 * @param \Vol2223\Ryvalidator\Validation\IntegerValidator $integerValidator
	 * @param \Vol2223\Ryvalidator\Validation\StringValidator  $stringValidator
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
	 * @return \Vol2223\Ryvalidator\Validation\EnumValidator
	 */
	public function enumValidator()
	{
		return $this->enumValidator;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validation\IntegerValidator
	 */
	public function integerValidator()
	{
		return $this->integerValidator;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validation\StringValidator
	 */
	public function stringValidator()
	{
		return $this->stringValidator;
	}
}
