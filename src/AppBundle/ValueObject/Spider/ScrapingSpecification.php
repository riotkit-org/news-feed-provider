<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Spider;

/**
 * Specifies how to extract a few additional things from the
 * page content (HTML format)
 */
class ScrapingSpecification implements \JsonSerializable
{
    /**
     * List of paths in X-Path format
     * that should be removed before scrapping (eg. Ads, Social Media, UI elements)
     *
     * @var string[] $toRemove
     */
    protected $toRemove = [];

    /**
     * X-Path that describes where is the content
     *
     * @var string $contentPath
     */
    protected $contentPath = '';

    public function __construct(array $spec = null)
    {
        $this->toRemove    = isset($spec['removePaths']) && is_array($spec['removePaths'])  ? $spec['removePaths'] : [];
        $this->contentPath = isset($spec['contentPath']) && is_string($spec['contentPath']) ? $spec['contentPath'] : '';
    }

    /**
     * @return \string[]
     */
    public function getPathsToRemove(): array
    {
        return $this->toRemove;
    }

    /**
     * @return string
     */
    public function getPathToContent(): string
    {
        return $this->contentPath;
    }

    public function jsonSerialize()
    {
        return [
            'removePaths' => $this->toRemove,
            'contentPath' => $this->contentPath,
        ];
    }
}
