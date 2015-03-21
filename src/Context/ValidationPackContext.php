<?php

namespace Vol2223\Ryvalidator\Context;

use Vol2223\Ryvalidator\Validation\EnumValidation;
use Vol2223\Ryvalidator\Validation\IntegerValidation;
use Vol2223\Ryvalidator\Validation\StringValidation;

class ValidationPackContext
{
	/**
	 * @var \Vol2223\Ryvalidator\Validation\EnumValidation
	 */
	private $enumValidation;

	/**
	 * @var \Vol2223\Ryvalidator\Validation\IntegerValidation
	 */
	private $integerValidation;

	/**
	 * @var \Vol2223\Ryvalidator\Validation\StringValidation
	 */
	private $stringValidation;

	/**
	 * @param \Vol2223\Ryvalidator\Validation\EnumValidation    $enumValidation
	 * @param \Vol2223\Ryvalidator\Validation\IntegerValidation $integerValidation
	 * @param \Vol2223\Ryvalidator\Validation\StringValidation  $stringValidation
	 */
	public function __construct(
		EnumValidation    $enumValidation = null,
		IntegerValidation $integerValidation = null,
		StringValidation  $stringValidation = null
	) {
		$this->enumValidation      = is_null($enumValidation) ? new EnumValidation() : $enumValidation;
		$this->integerValidation   = is_null($integerValidation) ? new IntegerValidation() : $integerValidation;
		$this->stringValidation    = is_null($stringValidation) ? new StringValidation() : $stringValidation;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validation\EnumValidation
	 */
	public function enumValidation()
	{
		return $this->enumValidation;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validation\IntegerValidation
	 */
	public function integerValidation()
	{
		return $this->integerValidation;
	}

	/**
	 * @return \Vol2223\Ryvalidator\Validation\StringValidation
	 */
	public function stringValidation()
	{
		return $this->stringValidation;
	}
}
