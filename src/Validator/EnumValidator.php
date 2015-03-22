<?php

namespace Vol2223\Ryvalidator\Validator;

use Vol2223\Ryvalidator\Exception\ValidationException;

class EnumValidator implements ValidatorInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function validate($requirement, $target)
	{
		static::enumValidate($requirement, $target);
	}

	/**
	 * Enumの整合性チェック
	 *
	 * @param [] $enumList
	 * @param mixed $target
	 */
	private static function enumValidate($enumList, $target)
	{
		$enumList = $enumList['enum'];
		if (!in_array($target, $enumList)) {
			throw new ValidationException(sprintf(
				'Enumのリストに無いものをでした enumList=%s : actual=%s',
				implode(',', $enumList),
				$target
			));
		}
	}
}
