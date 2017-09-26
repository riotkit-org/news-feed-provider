<?php declare(strict_types = 1);

namespace ImageRepositoryBundle\Service;

/**
 * Extracts links to images from text
 */
class ImageExtractor
{
    const REGEXPS = [
        '#(?P<address>((http|https)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$))#i',
    ];

    public function extractLinks(string $text): array
    {
        $images = [];

        foreach (self::REGEXPS as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches['address'] as $match) {
                    $images[] = trim($match, " \"\'");
                }
            }
        }

        return $images;
    }
}
