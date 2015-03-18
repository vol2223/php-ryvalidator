<?php

namespace Vol2223\PyaValidator;

use Symfony\Component\Yaml\Parser;

class ContractParser
{
	const YAML_DIRECTORY = 'definitions';

	/**
	 * @var yaml \Symfony\Component\Yaml\Parser
	 */
	private $parser;

	/**
	 * @var []
	 */
	private $requestByGet;

	/**
	 * @var []
	 */
	private $requestByPost;

	/**
	 * @var []
	 */
	private $responsesByGet;

	/**
	 * @var []
	 */
	private $responsesByPost;

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
	public function parse($basePath, $yamlName)
	{
		$this->load($basePath, $yamlName);
		file_put_contents('test.txt', var_export($this->targetIterator->getArrayCopy(), true));
//		foreach ($this->load($basePath, $yamlName) as $key => $target) {
//			if (isset($target['post']['request'])) {
//				return $this->requestByPost[$key] = $target['post']['request'];
//			}
//			if (isset($target['post']['responses'])) {
//				return $this->responsesByPost[$key] = $target['post']['responses'];
//			}
//			if (isset($target['get']['request'])) {
//				return $this->requestByGet[$key] = $target['get']['request'];
//			}
//			if (isset($target['get']['responses'])) {
//				return $this->responsesByGet[$key] = $target['get']['responses'];
//			}
//		}
	}

	/**
	 * loading yaml
	 * 
	 * @param string $basePath
	 * @param string $yamlName
	 */
	private function load($basePath, $yamlName)
	{
		$basePath = $basePath . '/' . self::YAML_DIRECTORY . '/';
		$value = $this->parser->parse(file_get_contents($basePath . $yamlName));
		$includes = [];
		if (isset($value['includes'])) {
			foreach ($value['includes'] as $include) {
				foreach ($this->parser->parse(file_get_contents($basePath . $include)) as $key => $includeContract) {
					$includes[$key] = $includeContract;
				}
			}
			unset($value['includes']);
		}

		$this->includes = $includes;
		$this->targetIterator = new \RecursiveArrayIterator($value);
		$this->searchInclude($this->targetIterator);
	}

	private function searchInclude(\RecursiveArrayIterator $targetIterator, \RecursiveArrayIterator $beforeTargetIterator = null)
	{
		while(true) {
			$current = $targetIterator->current();
			if (is_null($current)) {
				break;
			}
			if ($targetIterator->hasChildren()) {
				if($targetIterator->getChildren()->offsetExists('is')) {
					$targetIterator->offsetSet(
						$targetIterator->key(),
						$this->includes[$targetIterator->getChildren()->offsetGet('is')]
					);
				}
				if (is_null($beforeTargetIterator)) {
					$this->searchInclude($targetIterator->getChildren(), $targetIterator);
				} else {
					$beforeTargetIterator->offsetSet(
						$beforeTargetIterator->key(),
						$this->searchInclude($targetIterator->getChildren(), $targetIterator)->getArrayCopy()
					);
				}
			}
			$targetIterator->next();
		}
		return $targetIterator;
	}
}
