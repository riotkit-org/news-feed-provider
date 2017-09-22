<?php declare(strict_types=1);

namespace RssSupportBundle\ValueObject\Specification;

class RssSourceSpecification
{
    /**
     * @var string $url
     */
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}
