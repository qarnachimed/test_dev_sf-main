<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Home extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $images = $this->getImages();

        return $this->render('default/index.html.twig', [
            'images' => $images,
        ]);
    }

    private function getImages(): array
    {
        $rssImages = $this->getRssImages();
        $apiImages = $this->getApiImages();

        $images = array_merge($rssImages, $apiImages);

        $uniqueImages = array_unique($images);

        return $uniqueImages;
    }

    private function getRssImages(): array
    {
        $rssUrl = 'http://www.commitstrip.com/en/feed/';

        try {

            $response = $this->httpClient->request('GET', $rssUrl);
            $xml = $response->getContent();
            $items = simplexml_load_string($xml)->channel->item;
            $images = [];
            foreach ($items as $item) {

                $image = $this->getImageFromRssItem($item);
                if (!empty($image)) {
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
        @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$content);

        $xpath = new \DomXpath($doc);

        $imageNodes = $xpath->query('//img');

        if ($imageNodes->length > 0) {
            $image = $imageNodes[0]->getAttribute('src');
            return $image;
        } else {
            return null;
        }
    }

    private function getApiImages(): array
    {
        $apiKey = 'YOUR_NEWSAPI_KEY';
        $apiUrl = "https://newsapi.org/v2/top-headlines?country=us&apiKey=$apiKey";

        try {
            $apiUrl = urlencode($apiUrl);
            $response = $this->httpClient->request('GET', $apiUrl);
            $json = $response->getContent();

            $data = json_decode($json);

            $images = [];
            foreach ($data->articles as $article) {
                if (!empty($article->urlToImage)) {
                    $images[] = $article->urlToImage;
                }
            }

            return $images;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
