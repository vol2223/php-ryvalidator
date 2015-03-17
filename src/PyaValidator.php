<?php

namespace Vol2223\PyaValidator;

use Vol2223\PyaValidator\Exception\ValidationException;
use Vol2223\PyaValidator\Exception\ArrayValidationException;
use Vol2223\PyaValidator\Validation\IntegerValidation;
use Vol2223\PyaValidator\Validation\StringValidation;
use Vol2223\PyaValidator\Validation\EnumValidation;

class PyaValidator
{
	/**
	 * @var [] バリデーション設定の配列
	 */
	private $config;

	/**
	 * @var [] バリデーションを行う対象の配列
	 */
	private $targets;

	/**
	 * バリデータの情報を配列で渡す
	 *
	 * @param [] $config バリデーションの定義情報
	 * @param [] $targets バリデーションを行う対象の配列
	 */
	public function __construct(Array $config, Array $targets)
	{
		$this->config = $config;
		$this->targets = $targets;
	}

	/**
	 * バリデーションを実行
	 *
	 * @param [] $config 特に指定がなければコンストラクタで定義したものを使う
	 * @param [] $target 特に指定がなければコンストラクタで定義したものを使う
	 */
	public function validate($config = [], $targets = [])
	{
		$config = !empty($config) ? $config : $this->config;
		$targets = !empty($targets) ? $targets : $this->targets;
		foreach ($config as $key => $requirement) {
			if (0 === $key) {
				// keyが0の時は配列を想定しているため例外的な処理
				foreach ($targets as $target) {
					$this->_validate($requirement, $target, 'Array');
				}
			} else {
				if (
					isset($requirement['required'])
					and false === $requirement['required']
					and !isset($targets[$key])
				) {
					// フィールドのセット無しが許可されている場合はスルー
					continue;
				}
				$this->_validate($requirement, $targets[$key], $key);
			}
		}
	}

	/**
	 * 1keyに対したバリデーションの実行
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\ValidationException;
	 */
	private function _validate($requirement, $target, $logKey)
	{
		switch ($requirement['type']) {
		case ValidatorType::VALIDATOR_TYPE_STRING:
			$this->stringValidate($requirement, $target, $logKey);
			break;
		case ValidatorType::VALIDATOR_TYPE_INTEGER:
			$this->integerValidate($requirement, $target, $logKey);
			break;
		case ValidatorType::VALIDATOR_TYPE_ENUM:
			$this->enumValidate($requirement, $target, $logKey);
			break;
		case ValidatorType::VALIDATOR_TYPE_OBJECT:
			$this->objectValidate($requirement, $target, $logKey);
			break;
		case ValidatorType::VALIDATOR_TYPE_ARRAY:
			$this->arrayValidate($requirement, $target, $logKey);
			break;
		default:
			throw new ValidationException(
				sprintf('validationのチェック:定義外のタイプでした。key=%s,type=%s',$logKey, $requirement['type'])
			);
		}
	}

	/**
	 * 文字列のバリデーション
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\StringValidationException
	 * @throws \Vol2223\PayValidator\Exception\ValidationException
	 */
	private function stringValidate($requirement, $target, $logKey)
	{
		if (!is_string($target)) {
			throw new ValidationException(
				sprintf('validationのチェック:Stringではありません。key=%s,value=%s',$logKey, $target)
			);
		}
		StringValidation::validate($requirement, $target);
	}

	/**
	 * 数値のバリデーション
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\IntegerValidationException
	 * @throws \Vol2223\PayValidator\Exception\ValidationException
	 */
	private function integerValidate($requirement, $target, $logKey)
	{
		if (!is_int($target)) {
			throw new ValidationException(
				sprintf('validationのチェック:Integerではありません。key=%s,value=%s',$logKey, $target)
			);
		}
		IntegerValidation::validate($requirement, $target);
	}

	/**
	 * Enumのバリデーション
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\IntegerValidationException
	 * @throws \Vol2223\PayValidator\Exception\ValidationException
	 */
	private function enumValidate($requirement, $target, $logKey)
	{
		if (is_array($target) or is_object($target)) {
			throw new ValidationException(
				sprintf('validationのチェック:Enumではありません。key=%s,value=%s',$logKey, implode(',', (array)$target))
			);
		}
		EnumValidation::validate($requirement, $target);
	}

	/**
	 * オブジェクトのバリデーション
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\ObjectValidationException
	 * @throws \Vol2223\PayValidator\Exception\ValidationException
	 */
	private function objectValidate($requirement, $target, $logKey)
	{
		if (!is_object($target)) {
			throw new ValidationException(
				sprintf('validationのチェック:Objectではありません。key=%s,value=%s',$logKey, implode(',', (array)$target))
			);
		}
		$this->validate($requirement['properties'], (array)$target);
	}

	/**
	 * 配列のバリデーション
	 *
	 * @param [] $requirement
	 * @param [] $target
	 * @param string $logKey ログ用のキー
	 * @throws \Vol2223\PayValidator\Exception\ArrayValidationException
	 * @throws \Vol2223\PayValidator\Exception\ValidationException
	 */
	private function arrayValidate($requirements, $targets, $logKey)
	{
		if (!is_array($targets)) {
			throw new ValidationException(
				sprintf('validationのチェック:Arrayではありません。key=%s,value=%s',$logKey, implode(',', (array)$targets))
			);
		}
		try {
			$this->validate($requirements['items'], $targets);
		} catch (ValidationException $e) {
			throw ArrayValidationException($e->getMessage());
		} catch (\Exception $e) {
			throw $e;
		}
	}
}
