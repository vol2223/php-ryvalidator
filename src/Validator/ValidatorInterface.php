<?php

namespace Vol2223\Ryvalidator\Validator;

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
