<?php

namespace Vol2223\Ryvalidator;

use \Mockery as M;
use Vol2223\Ryvalidator\Context\ValidationPackContext;
use Vol2223\LightValidator\LightValidator;
use Vol2223\LightValidator\Validation\EnumValidation;

class RyvalidatorTest extends \PHPUnit_Framework_TestCase
{
	public function test_validate_Normal()
	{
		$config = [
			'string' =>
			[
				'type' => 'string',
				'minLength' => 3,
				'maxLength' => 5,
			],
			'integer' =>
			[
				'type' => 'integer',
			],
			'object' =>
			[
				'type' => 'object',
				'properties' => [
					'huga' => [
						'type' => 'string'
					]
				]
			],
			'array' =>
			[
				'type' => 'array',
				'items' => [
					'type' => 'integer'
				]
			],
			'arrayObject' =>
			[
				'type' => 'array',
				'items' => [
					'type' => 'object',
					'properties' => [
						'hoge' => [
							'type' => 'integer'
						]
					]
				]
			],
			'enum' =>
			[
				'type' => 'enum',
				'enum' => [
					0 => 'HOGE',
					1 => 'PIYO'
				]
			],
			'required' =>
			[
				'type' => 'integer',
				'required' => false
			]
		];
		$targets = [
			'string' => 'abc',
			'integer' => 123,
			'object' => ['huga' => 'foga'],
			'array' => [
				1,2,3
			],
			'arrayObject' => [
				['hoge' => 1, 'hoge' => 100]
			],
			'enum' => 'HOGE'
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 */
	public function test_validate_MissMatchEnumList()
	{
		$config = [
			'enum' =>
			[
				'type' => 'enum',
				'enum' => [
					0 => 'HOGE',
					1 => 'PIYO'
				]
			]
		];
		$targets = [
			'enum' => 'GEGE'
		];
		$validationPackContext = new ValidationPackContext(
			null,
			null,
			new LightValidator(new EnumValidation())
		);
		$pyaValidator = new Ryvalidator($config, $validationPackContext);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:定義外のタイプでした。key=error,type=hogehogehoge
	 */
	public function test_validate_MissingType()
	{
		$config = [
			'error' =>
			[
				'type' => 'hogehogehoge'
			]
		];
		$targets = [
			'error' => 'GEGE'
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:Stringではありません。key=string,value=1
	 */
	public function test_validate_StringMissMatchParam()
	{
		$config = [
			'string' =>
			[
				'type' => 'string'
			]
		];
		$targets = [
			'string' => 1
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:Integerではありません。key=integer,value=hoge
	 */
	public function test_validate_IntegerMissMatchParam()
	{
		$config = [
			'integer' =>
			[
				'type' => 'integer'
			]
		];
		$targets = [
			'integer' => 'hoge'
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:Enumではありません。key=enum,value=1
	 */
	public function test_validate_EnumArrayMissMatchParam()
	{
		$config = [
			'enum' =>
			[
				'type' => 'enum',
				'enum' => [
					0 => 'HOGE',
					1 => 'PIYO'
				]
			],
		];
		$targets = [
			'enum' =>[1] 
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:Enumではありません。key=enum,value=2
	 */
	public function test_validate_EnumObjectMissMatchParam()
	{
		$config = [
			'enum' =>
			[
				'type' => 'enum',
				'enum' => [
					0 => 'HOGE',
					1 => 'PIYO'
				]
			],
		];
		$object = new \stdClass();
		$object->hoge = 2;
		$targets = [
			'enum' => $object
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}

	/**
	 * @expectedException Vol2223\Ryvalidator\Exception\ValidationException
	 * @expectedExceptionMessage validationのチェック:Arrayではありません。key=array,value=2
	 */
	public function test_validate_ArrayMissMatchParam()
	{
		$config = [
			'array' =>
			[
				'type' => 'array',
				'items' => [
					0 => [
						'type' => 'integer'
					]
				]
			],
		];
		$targets = [
			'array' => 2
		];
		$pyaValidator = new Ryvalidator($config);
		$pyaValidator->validate($targets);
	}
}
