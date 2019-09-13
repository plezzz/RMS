<?php

namespace AppBundle\Controller;

use AppBundle\Service\Search\SearchServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $searchService;

    public function __construct(SearchServiceInterface $searchService)
    {
        $this->searchService=$searchService;
    }

    /**
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/search", name="search")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $keyword = $request->request->get('keyword');
        list($printers, $users, $companies, $models)=$this->searchService->searchResult($keyword);

        return $this->render(
            'default/search.html.twig',
            [
                'printers' => $printers,
                'users' => $users,
                'companies' => $companies,
                'models' => $models,
                'keyword' => $keyword,
            ]
        );
    }
}
