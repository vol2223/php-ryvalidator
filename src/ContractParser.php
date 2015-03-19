<?php

namespace Vol2223\PyaValidator;

use Symfony\Component\Yaml\Parser;
use Vol2223\PyaValidator\Context\ContractContext;

class ContractParser
{
	const DEFINITIONS_DIRECTORY = 'definitions';
	const VALIDATIONS_DIRECTORY = 'validations';
	const INCLUDES_ACCESS_KEY = 'includes';
	const METHOD_GET = 'get';
	const METHOD_POST = 'post';
	const REQUEST_TYPE_REQUEST = 'requests';
	const REQUEST_TYPE_RESPONSE = 'responses';

	private static $METHODS = [
		self::METHOD_GET,
		self::METHOD_POST
	];

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
	 * execute parse
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
					$contracts[] = new ContractContext($fileName, $action, $methodType, $requestType, $request);
				}
			}
		}
		return $contracts;
	}

	/**
	 * loading yaml
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
			$ancestor[$keyOfParent] = $this->includes[$targetIterator['is']];
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
