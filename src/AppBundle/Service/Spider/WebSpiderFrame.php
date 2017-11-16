<?php declare(strict_types = 1);

namespace AppBundle\Service\Spider;

use AppBundle\ValueObject\Spider\ScrapingSpecification;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Instance of a page crawler for a given URL address
 */
class WebSpiderFrame
{
    protected $crawler;

    public function __construct(string $contents)
    {
        $this->crawler = new Crawler($contents);
    }

    /**
     * Find image that is set by the page as a main image that
     * should be shown in search engines, social media posts
     */
    public function findPageMainImage(): string
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

        return '';
    }

    /**
     * @param  ScrapingSpecification $spec
     * @return string
     */
    public function findPageContent(ScrapingSpecification $spec): string
    {
        if (!$spec->getPathToContent()) {
            return '';
        }

        $this->removeBlacklistedElements($spec);

        $node = $this->crawler->filterXPath($spec->getPathToContent())
            ->first();

        $this->removeConflictingAttributes($node);

        if ($node) {
            return $this->purify(
                $node->html()
            );
        }

        return '';
    }

    /**
     * Remove unwanted elements such as Ads, social buttons, other UI elements
     * defined per page
     *
     * @param ScrapingSpecification $spec
     */
    protected function removeBlacklistedElements(ScrapingSpecification $spec)
    {
        foreach ($spec->getPathsToRemove() as $path) {
            $this->crawler->filterXPath($path)->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
        }
    }

    /**
     * @param string $dirty
     * @return string
     */
    protected function purify(string $dirty): string
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);

        $clean = $purifier->purify($dirty);
        $clean = strip_tags($clean,
            '<p>' .
            '<br>' .
            '<div>' .
            '<span>' .
            '<u>' .
            '<i>' .
            '<strong>' .
            '<a>' .
            '<img>' .
            '<figure>' .
            '<h1>' .
            '<h2>' .
            '<h3>' .
            '<h4>' .
            '<h5>' .
            '<h6>'
        );

        return $clean;
    }

    /**
     * Remove "class" and "id" attributes from
     * the occurrences for security reasons
     *
     * @param Crawler $crawler
     */
    protected function removeConflictingAttributes(Crawler $crawler)
    {
        /**
         * @var Crawler[]|\DOMElement[] $crawler
         */
        foreach ($crawler as $node) {
            foreach (['class', 'id'] as $propertyName) {
                if ($node->hasAttribute($propertyName)) {
                    $node->removeAttribute($propertyName);
                }
            }
        }
    }
}
