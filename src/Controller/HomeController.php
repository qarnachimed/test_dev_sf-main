<?php

namespace App\Controller;

use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class HomeController extends AbstractController
{
    private $imageService;
    private $httpClient;
    private $apiKey;
    private $apiUrl;
    private $rssUrl;

    public function __construct(ImageService $imageService, HttpClientInterface $httpClient, string $apiKey, string $apiUrl, string $rssUrl)
    {
        $this->imageService = $imageService;
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->rssUrl = $rssUrl;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $httpClient = $this->httpClient;
        $apiUrl = $this->apiUrl;
        $rssUrl = $this->rssUrl;
        $images = $this->imageService->getImages($httpClient, $apiUrl, $rssUrl);

        return $this->render('default/index.html.twig', [
            'images' => $images,
        ]);
    }
}