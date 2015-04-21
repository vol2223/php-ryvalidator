<?php

namespace Vol2223\Ryvalidator;

use Symfony\Component\Yaml\Parser;
use Vol2223\Ryvalidator\Context\ContractContext;

class ContractParser
{
	const DEFINITIONS_DIRECTORY = 'definitions'; // yaml定義ディレクトリ名
	const VALIDATIONS_DIRECTORY = 'validations'; // バリデーションファイルの格納ディレクトリ名

	const INCLUDES_ACCESS_KEY = 'includes'; // 読み込みファイルのkey名
	const DESCRIPTION_ACCESS_KEY = 'description'; // 説明のkey名
	const IS_ACCESS_KEY = 'is'; // 読み込みファイル呼び出しのkey名

	const METHOD_GET = 'get'; // GETメソッド
	const METHOD_POST = 'post'; // POSTメソッド

	const REQUEST_TYPE_REQUEST = 'requests'; // リクエスト
	const REQUEST_TYPE_RESPONSE = 'responses'; // レスポンス

	/**
	 * 扱えるメソッド一覧
	 */
	private static $METHODS = [
		self::METHOD_GET,
		self::METHOD_POST
	];

	/**
	 * 扱えるリクエスト一覧
	 */
	private static $REQUESTS = [
		self::REQUEST_TYPE_REQUEST,
		self::REQUEST_TYPE_RESPONSE
	];

	/**
	 * @var yaml \Symfony\Component\Yaml\Parser
	 */
	private $parser;

	public function __construct()
	{
		$this->parser = new Parser();
	}

	/**
	 * パースの実行
	 *
	 * @param string $basePath
	 * @param string $yamlName
	 */
	public function parse($fileName, $basePath, $yamlName)
	{
		$this->load($basePath, $yamlName);
		$contracts = [];
		foreach ($this->targetIterator as $action => $methods) {
			foreach ($methods as $methodType => $requests) {
				if (!in_array($methodType, static::$METHODS)) {
					continue;
				}
				foreach ($requests as $requestType => $request) {
					if (!in_array($requestType, static::$REQUESTS)) {
						continue;
					}
					$contracts[] = new ContractContext($fileName, $action, strtoupper($methodType), $requestType, $request);
				}
			}
		}
		return $contracts;
	}

	/**
	 * yamlを読み込むみ配列にパースする
	 * 
	 * @param string $basePath
	 * @param string $yamlName
	 */
	private function load($basePath, $yamlName)
	{
		$basePath = $basePath . '/' . self::DEFINITIONS_DIRECTORY . '/';
		$value = $this->parser->parse(file_get_contents($basePath . $yamlName));
		$includes = [];
		if (isset($value[self::INCLUDES_ACCESS_KEY])) {
			foreach ($value[self::INCLUDES_ACCESS_KEY] as $include) {
				foreach ($this->parser->parse(file_get_contents($basePath . $include)) as $key => $includeContract) {
					$includes[$key] = $includeContract;
				}
			}
			unset($value[self::INCLUDES_ACCESS_KEY]);
		}

		$this->includes = $includes;
		$this->targetIterator = $value;
		$this->unsetDescription($this->targetIterator);
		$this->searchInclude($this->targetIterator);
	}

	/**
	 * include定義を付与する
	 *
	 * @param [] &$targetIterator
	 * @param [] &$ancestor
	 * @param string $keyOfParent
	 */
	private function searchInclude(Array &$targetIterator, Array &$ancestor = null, $keyOfParent = null)
	{
		$isKey = array_key_exists('is', $targetIterator);
		if ($isKey) {
			foreach ($this->includes[$targetIterator['is']] as $key => $value) {
				$ancestor[$keyOfParent][$key] = $value;
			}
			unset($ancestor[$keyOfParent]['is']);
		}
		foreach(array_keys($targetIterator) as $i){
			if(is_array($targetIterator[$i])) {
				$this->searchInclude($targetIterator[$i], $targetIterator, $i);
			}
		}
	}

	/**
	 * descriptionを消す
	 *
	 * @param [] &$targetIterator
	 */
	private function unsetDescription(&$targetIterator)
	{
		$isKey = array_key_exists('description', $targetIterator);
		if ($isKey) {
			unset($targetIterator['description']);
		}

		foreach(array_keys($targetIterator) as $i){
			if(is_array($targetIterator[$i])) {
				$this->unsetDescription($targetIterator[$i]);
			}
		}
	}
}
