<?php

namespace Vol2223\Ryvalidator\Validation;

use Vol2223\Ryvalidator\Exception\EnumValidationException;

class EnumValidation implements ValidationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function validate($requirement, $target)
	{
		static::enumValidate($requirement['enum'], $target);
	}

	/**
	 * Enumの整合性チェック
	 *
	 * @param [] $enumList
	 * @param mixed $target
	 */
	private static function enumValidate($enumList, $target)
	{
		if (!in_array($target, $enumList)) {
			throw new EnumValidationException(sprintf(
				'Enumのリストに無いものをでした enumList=%s : actual=%s',
				implode(',', $enumList),
				$target
			));
		}
	}
}
