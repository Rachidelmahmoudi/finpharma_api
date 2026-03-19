<?php

namespace App\Controller;

use App\Form\MainSearchType;
use App\Form\SearchTypes;
use App\Service\MainSearcher;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function getIndex(Request $request, MainSearcher $main_searcher, PaginatorInterface $paginator): Response
    {
        $global_search = $this->createForm(MainSearchType::class);
        $global_search->handleRequest($request);
        $query_main_search = $request->query->all('main_search');
        $view_data = ['global_search' => $global_search];
        if (!empty($query_main_search)) {
            ['query' => $query, 'type' => $type] = $query_main_search;
        }
        if ($global_search->isSubmitted() && $global_search->isValid()) {
            ['query' => $query, 'type' => $type] = $global_search->getData();
            $type = empty($type) ? (SearchTypes::ALL)->value : $type;
            $query = $query ?? '';
        }
        if (!empty($type) || !empty($query)) {
            $result = $main_searcher->search($type, $query);
            $pagination = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1),
                5
            );
            $view_data = ['global_search' => $global_search, 'pagination' => $pagination, 'results' => $result->getResult(), 'active_type' => $type];
        }
        return $this->render('index/index.html.twig', $view_data);
    }

    #[Route('/pharmacies', name: 'get_pharmacies')]
    public function getPharmacies(Request $request): Response
    {
         return $this->render('index/pharmacies.html.twig');
    }
}
