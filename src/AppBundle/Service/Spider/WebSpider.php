<?php declare(strict_types = 1);

namespace AppBundle\Service\Spider;

use GuzzleHttp\Client;

/**
 * Acts like a web browser, opens urls for data collection
 */
class WebSpider
{
    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var WebSpiderFrame[] $openedPages
     */
    protected $openedPages = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Opens a URL and returns a frame
     * that allows to parse the HTML
     *
     * @param string $url
     * @return WebSpiderFrame
     */
    public function open(string $url): WebSpiderFrame
    {
        if (isset($this->openedPages[$url])) {
            return $this->openedPages[$url];
        }

        return $this->openedPages[$url] = $this->createNewFrame($url);
    }

    protected function createNewFrame(string $url): WebSpiderFrame
    {
        return new WebSpiderFrame($this->getPageContents($url));
    }

    protected function getPageContents(string $url): string
    {
        return $this->client
            ->get($url)
            ->getBody()
            ->getContents();
    }
}
