<?php

namespace Vol2223\Ryvalidator\Validation;

interface ValidatorInterface
{
	/**
	 * バリデーションを実行する
	 *
	 * @param [] $requirement
	 * @param mixed $target
	 */
	public function validate($requirement, $target);
}
