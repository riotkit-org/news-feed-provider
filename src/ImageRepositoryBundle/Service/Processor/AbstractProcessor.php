<?php declare(strict_types = 1);

namespace ImageRepositoryBundle\Service\Processor;

use GuzzleHttp\Exception\ServerException;
use ImageRepositoryBundle\Service\{
    ImageExtractor, ImageUploader
};
use Monolog\Logger;
use Wolnosciowiec\FileRepositoryBundle\Exception\UploadFailureException;

abstract class AbstractProcessor
{
    protected $uploader;
    protected $extractor;
    protected $logger;
    protected $enabled;

    public function __construct(ImageUploader $uploader, ImageExtractor $extractor, Logger $logger, bool $enabled = true)
    {
        $this->uploader  = $uploader;
        $this->extractor = $extractor;
        $this->logger    = $logger;
        $this->enabled   = $enabled;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Replaces links that are valid images (File Repository will attempt to upload and validate them)
     * In case of failure an original link is preserved and the error is logged
     *
     * @param string $text
     * @return string
     */
    protected function processText(string $text): string
    {
        $links = $this->extractor->extractLinks($text);

        foreach ($links as $link) {
            $this->logger->info('Passing "' . $link . '" to uploader');

            try {
                $newUrl = $this->uploader->upload($link);
                $text = str_replace($link, $newUrl, $text);

            } catch (UploadFailureException $exception) {
                $this->logger->error($exception);

            } catch (ServerException $exception) {
                $this->logger->warning($exception);
            }
        }

        $this->logger->info('Processed ' . count($links) . ' links');
        return $text;
    }
}
