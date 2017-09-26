<?php declare(strict_types = 1);

namespace ImageRepositoryBundle\Service;

use Wolnosciowiec\FileRepositoryBundle\Service\FileUploader;

/**
 * Image Uploader
 * ==============
 *   Uploads images to external service, used to avoid hotlinking of resources
 *   for more stability
 */
class ImageUploader
{
    protected $uploader;
    protected $imageTags;

    public function __construct(FileUploader $uploader, array $imageTags = ['news-feed-provider'])
    {
        $this->uploader  = $uploader;
        $this->imageTags = $imageTags;
    }

    public function upload(string $url): string
    {
        if ($this->isAlreadyUploaded($url)) {
            return $url;
        }

        return $this->uploader->uploadFromUrl($url, $this->imageTags);
    }

    /**
     * Match two urls by domain
     * Try to avoid re-uploading of content
     *
     * @param string $url
     * @return bool
     */
    protected function isAlreadyUploaded(string $url): bool
    {
        $uploaderHost = parse_url($this->uploader->getClient()->getBaseUrl(), PHP_URL_HOST);
        $urlHost      = parse_url($url, PHP_URL_HOST);

        return $uploaderHost === $urlHost;
    }
}
