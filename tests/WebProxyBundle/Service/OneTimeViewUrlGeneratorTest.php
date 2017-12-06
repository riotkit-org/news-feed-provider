<?php declare(strict_types=1);

namespace Test\WebProxyBundle\Service;

use Blocktrail\CryptoJSAES\CryptoJSAES;
use Tests\TestCase;
use WebProxyBundle\Service\OneTimeViewUrlGenerator;

/**
 * @see OneTimeViewUrlGenerator
 */
class OneTimeViewUrlGeneratorTest extends TestCase
{
	/**
	 * @see OneTimeViewUrlGenerator::generate()
	 */
	public function test_generates_any_url()
	{
		$service = new OneTimeViewUrlGenerator(
			'https://webproxy.wolnosciowiec.org',
			'aaa',
			true,
			5
		);

		$generatedUrl = $service->generate('http://wolnywroclaw.pl');
		$this->assertNotEmpty($generatedUrl);
	}

	public function test_generates_url_with_valid_parameter_and_its_value()
	{
		$service = new OneTimeViewUrlGenerator(
			'https://webproxy.wolnosciowiec.org',
			'aaa',
			true,
			5
		);

		$generatedUrl = $service->generate('http://federacja-anarchistyczna.pl');


		$this->assertUrlMatches('http://federacja-anarchistyczna.pl', $generatedUrl);
	}

	private function assertUrlMatches(string $expected, string $generatedUrl)
	{
		$parts = explode('?__wp_one_time_token=', $generatedUrl);
		$actualDecrypted = CryptoJSAES::decrypt($parts[1], 'aaa');
		$array = json_decode($actualDecrypted, true);

		$this->assertSame($array['url'], $expected);
	}
}
