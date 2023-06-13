<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Helper\ArrayHelper;

class ImageService
{


    public function __construct()
    {
    }

    public function getImages(HttpClientInterface $httpClient, string $apiUrl, string $rssUrl): array
    {
        $rssImages = $this->getRssImages($httpClient, $rssUrl);
        $apiImages = $this->getApiImages($httpClient, $apiUrl);

        $images = ArrayHelper::mergeUnique($rssImages, $apiImages);

        return $images;
    }

    private function getRssImages($httpClient, $rssUrl): array
    {
        try {
            $response = $httpClient->request('GET', $rssUrl);
            $xml = $response->getContent();
            $items = simplexml_load_string($xml)->channel->item;
            $images = [];
            foreach ($items as $item) {
                $image = $this->getImageFromRssItem($item);
                if (isset($image)) {
                    $images[] = $image;
                }
            }
            return $images;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getImageFromRssItem($item): ?string
    {
        $content = (string) $item->children('content', true)->encoded;
        $doc = new \DomDocument();
        @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $content);

        $xpath = new \DomXpath($doc);

        $imageNodes = $xpath->query('//img');

        if (0 < $imageNodes->length) {
            $image = $imageNodes[0]->getAttribute('src');
            return $image;
        } else {
            return null;
        }
    }

    private function getApiImages($httpClient, $apiUrl): array
    {
        try {
            $response = $httpClient->request('GET', $apiUrl);
            $data = json_decode($response->getContent());
            $images = [];
            foreach ($data->articles as $article) {
                if (isset($article->urlToImage)) {
                    $images[] = $article->urlToImage;
                }
            }

            return $images;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
