<?php declare(strict_types = 1);

namespace AppBundle\Service\Spider;

class WebSpider
{
    public function open(string $url): string
    {
        return new WebSpiderFrame()
    }
}
