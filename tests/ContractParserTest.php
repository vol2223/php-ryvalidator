<?php

namespace Vol2223\Ryvalidator;

class ContractParserTest extends \PHPUnit_Framework_TestCase
{
	public function test_parse()
	{
		$contractParser = new ContractParser();
		$expected = $contractParser->parse('test', __DIR__ . '/contract', 'test.yml');
		$this->assertEquals($expected[0]->filePath(), 'validations/test/access1/post/requests.php');
		$this->assertEquals($expected[1]->filePath(), 'validations/test/access1/post/responses.php');
		$this->assertEquals($expected[2]->filePath(), 'validations/test/access2/post/requests.php');
		$this->assertEquals($expected[3]->filePath(), 'validations/test/access2/post/responses.php');
		$actual['request1'] = [
			'type' => 'integer',
			'required' => false
		];
		$prefix  = <<<PHP
<?php

return 
PHP
;
		$actual = $prefix . var_export($actual, true) . ';';
		$this->assertSame($expected[0]->body(), $actual);
		unset($actual);

		$actual['responses1'] = [
			'type' => 'integer'
		];
		$actual = $prefix . var_export($actual, true) . ';';
		$this->assertSame($expected[1]->body(), $actual);
		unset($actual);

		$actual['request2'] = [
			'type' => 'integer',
			'required' => false
		];
		$actual = $prefix . var_export($actual, true) . ';';
		$this->assertSame($expected[2]->body(), $actual);
		unset($actual);

		$actual['responses2'] = [
			'type' => 'boolean',
		];
		$actual = $prefix . var_export($actual, true) . ';';
		$this->assertSame($expected[3]->body(), $actual);
	}
}
