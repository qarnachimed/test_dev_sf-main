<?php

namespace App\Service;

use DomDocument;
use DomXpath;
use Exception;

class ImageService
{

    public function getRssImages(string $data): array
    {
        $images = [];

        try {
            $items = simplexml_load_string($data)->channel->item;
            foreach ($items as $item) {
                $image = $this->getImageFromRssItem($item);
                if (!is_null($image)) {
                    $images[] = $image;
                }
            }
            return $images;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getApiImages(string $data): array
    {
        $images = [];

        try {
            $data = json_decode($data, false);

            foreach ($data->articles as $article) {
                if (!is_null($article->urlToImage)) {
                    $images[] = $article->urlToImage;
                }
            }

            return $images;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function getImageFromRssItem($item): ?string
    {
        $content = (string) $item->children('content', true)->encoded;

        $doc = new DomDocument();
        @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $content);

        $xpath = new DomXpath($doc);

        $imageNodes = $xpath->query('//img');

        if (0 === $imageNodes->length) {
            return null;
        }

        return $imageNodes[0]->getAttribute('src');
    }
}
