<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {
    #[Route('/search', name: 'app_search')]
    public function recherchePost(Request $request): Response
    {
        $formR = $this->createForm(SearchType::class);
        $pagination = [];

        $formR->handleRequest($request);
        if ($formR->isSubmitted() && $formR->isValid()) {
            $title = $formR->getData()['title'];

                $this->RestaurantRepository->findByTitle($title);
        }

        return $this->render('home/show_category.html.twig', [
            'formR' => $formR->createView(),
            'pagination' => $pagination,
        ]);
    }
}