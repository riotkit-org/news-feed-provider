<?php declare(strict_types=1);

namespace WebProxyBundle\Service;

use Blocktrail\CryptoJSAES\CryptoJSAES;

/**
 * Generates URL's with a one-time token
 * to access a page through a web proxy service
 *
 * @package WebProxyBundle\Service
 */
class OneTimeViewUrlGenerator
{
    /**
     * @var string $url
     */
    private $url;

    /**
     * @var string $passphrase
     */
    private $passphrase;

    /**
     * @var bool $process
     */
    private $process;

    /**
     * @var int $expirationTimeInMinutes
     */
    private $expirationTimeInMinutes;

    /**
     * OneTimeViewUrlGenerator constructor.
     *
     * @param string $webproxyUrl
     * @param string $passphrase
     * @param bool   $process
     * @param int    $expirationTimeInMinutes
     */
    public function __construct(string $webproxyUrl, string $passphrase, bool $process, int $expirationTimeInMinutes)
    {
        $this->url = $webproxyUrl;
        $this->passphrase = $passphrase;
        $this->process = $process;
        $this->expirationTimeInMinutes = $expirationTimeInMinutes;
    }

    /**
     * Generate a URL with one-time-token
     *
     * @param string $url
     * @return string
     */
    public function generate(string $url): string
    {
        return $this->url . '?__wp_one_time_token=' . $this->generateEncryptedToken($url);
    }

    /**
     * @param string $url
     * @return string
     */
    private function generateEncryptedToken(string $url): string
    {
        $token = [
            'url'          => $url,
            'expires'      => (new \DateTime('now'))->modify('+' . $this->expirationTimeInMinutes . ' minutes')->format('Y-m-d H:i:s'),
            'processs'     => $this->process,
            'stripHeaders' => ['X-Frame-Options']
        ];

        return CryptoJSAES::encrypt(json_encode($token), $this->passphrase);
    }
}
