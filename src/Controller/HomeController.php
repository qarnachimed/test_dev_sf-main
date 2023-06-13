<?php

namespace App\Controller;

use App\Helper\ArrayHelper;
use App\Service\ImageService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    /** @var ImageService  */
    private $imageService;

    /** @var HttpClientInterface  */
    private $httpClient;

    /** @var string  */
    private $apiUrl;

    /** @var string  */
    private $rssUrl;

    public function __construct(
        ImageService $imageService,
        HttpClientInterface $httpClient,
        string $apiUrl,
        string $rssUrl
    ){
        $this->imageService = $imageService;
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->rssUrl = $rssUrl;
    }

    /**
     * @throws Exception
     *
     * @Route("/", name="homepage")
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $imagesApi = $this->imageService->getApiImages(
            $this->getContentByUrl($this->apiUrl)
        );

        $imagesRss = $this->imageService->getRssImages(
            $this->getContentByUrl($this->rssUrl)
        );

        $images = ArrayHelper::mergeUnique($imagesRss, $imagesApi);

        return $this->render('default/index.html.twig', [
            'images' => $images,
        ]);
    }

    private function getContentByUrl(string $url): string
    {
        return $this->httpClient->request('GET', $url)->getContent();
    }
}