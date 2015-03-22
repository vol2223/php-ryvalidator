<?php

namespace Vol2223\Ryvalidator\Context;

use Vol2223\LightValidator\LightValidator;

class ValidationPackContext
{
	/**
	 * @var \Vol2223\LightValidator\LightValidator
	 */
	private $enumValidator;

	/**
	 * @var \Vol2223\LightValidator\LightValidator
	 */
	private $integerValidator;

	/**
	 * @var \Vol2223\LightValidator\LightValidator
	 */
	private $stringValidator;

	/**
	 * @param \Vol2223\LightValidator\LightValidator $integerValidator
	 * @param \Vol2223\LightValidator\LightValidator $stringValidator
	 * @param \Vol2223\LightValidator\LightValidator $enumValidator
	 */
	public function __construct(
		LightValidator $integerValidator = null,
		LightValidator $stringValidator = null,
		LightValidator $enumValidator = null
	) {
		$this->integerValidator   = is_null($integerValidator) ? new LightValidator() : $integerValidator;
		$this->stringValidator    = is_null($stringValidator) ? new LightValidator() : $stringValidator;
		$this->enumValidator      = is_null($enumValidator) ? new LightValidator() : $enumValidator;
	}

	/**
	 * @return \Vol2223\LightValidator\LightValidator
	 */
	public function enumValidator()
	{
		return $this->enumValidator;
	}

	/**
	 * @return \Vol2223\LightValidator\LightValidator
	 */
	public function integerValidator()
	{
		return $this->integerValidator;
	}

	/**
	 * @return \Vol2223\LightValidator\LightValidator
	 */
	public function stringValidator()
	{
		return $this->stringValidator;
	}
}
