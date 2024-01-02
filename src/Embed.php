<?php

namespace Magareux\EmbedBlock;

use Concrete\Core\Http\Client\Client;
use Concrete\Core\Support\Facade\Application;
use GuzzleHttp\Exception\GuzzleException;

/**
 * A utility class to keep compatibility with Embed\Embed 3.x.
 */
class Embed
{
    public const DEFAULT_IMAGE_WIDTH = 640;
    public const DEFAULT_IMAGE_HEIGHT = 480;

    protected \Embed\Extractor $info;
    protected ?int $imageWidth = null;
    protected ?int $imageHeight = null;

    /**
     * @param string $source
     */
    public function __construct(string $source)
    {
        $embed = new \Embed\Embed();
        $this->info = $embed->get($source);
    }

    public static function create(string $source): self
    {
        return new self($source);
    }

    public function getInfo(): \Embed\Extractor
    {
        return $this->info;
    }

    public function getUrl(): string
    {
        return (string) $this->info->url;
    }

    public function getImage(): string
    {
        return (string) $this->info->image;
    }

    public function getImageWidth(): int
    {
        if ($this->imageWidth === null) {
            $this->extractImageSizes();
        }

        return (int) $this->imageWidth;
    }

    public function getImageHeight(): int
    {
        if ($this->imageHeight === null) {
            $this->extractImageSizes();
        }

        return (int) $this->imageHeight;
    }

    public function getTitle(): string
    {
        return (string) $this->info->title;
    }

    public function getDescription(): string
    {
        return (string) $this->info->description;
    }

    protected function extractImageSizes()
    {
        if (empty($this->getImage())) {
            return;
        }

        $app = Application::getFacadeApplication();
        /** @var Client $client */
        $client = $app->make('http/client');
        try {
            $result = $client->request('GET', $this->info->image);
            $imagesizes = getimagesizefromstring($result->getBody()->getContents());
            if ($imagesizes === false) {
                $this->imageWidth = self::DEFAULT_IMAGE_WIDTH;
                $this->imageHeight = self::DEFAULT_IMAGE_HEIGHT;
            } else {
                $this->imageWidth = $imagesizes[0];
                $this->imageHeight = $imagesizes[1];
            }
        } catch (GuzzleException $e) {
            $this->imageWidth = self::DEFAULT_IMAGE_WIDTH;
            $this->imageHeight = self::DEFAULT_IMAGE_HEIGHT;
        }
    }
}