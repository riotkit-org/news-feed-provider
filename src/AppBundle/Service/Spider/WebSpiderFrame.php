<?php declare(strict_types = 1);

namespace AppBundle\Service\Spider;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebSpiderFrame
{
    protected $client;
    protected $crawler;

    public function __construct(string $url)
    {
        $this->client = new Client();
        $this->crawler = new Crawler($this->client->get($url));
    }

    /**
     * Find image that is set as a main image that
     * should be shown in search engines, social media posts
     */
    protected function findPageMainImage()
    {
        // by metas
        $selectors = [
            ['meta[property="og:image"]', 'content'],
            ['meta[name="msapplication-TileImage"]', 'content'],
            ['link[rel="icon"][sizes="192x192"]', 'href'],
            ['link[rel="icon"][sizes="128x128"]', 'href'],
            ['link[rel="icon"][sizes="64x64"]', 'href'],
            ['link[rel="icon"][sizes="32x32"]', 'href'],
            ['link[rel="apple-touch-icon-precomposed"]', 'href'],
        ];

        foreach ($selectors as $selector) {
            $expression    = $selector[0];
            $attributeName = $selector[1];

            $results = $this->crawler->filter($expression);

            if ($results->count() > 0) {
                return (string) $results->first()->attr($attributeName);
            }
        }


    }
}
