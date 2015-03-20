<?php

namespace Vol2223\Ryvalidator\Validation;

interface ValidationInterface
{
	/**
	 * バリデーションを実行する
	 *
	 * @param [] $requirement
	 * @param mixed $target
	 */
	public static function validate($requirement, $target);
}
