<?php

namespace App\Controller;

use App\Entity\Opinion;


use App\Entity\Restaurant;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function findRestaurantByBestOpinion(RestaurantRepository $restaurantRepository, Request $request): Response
    {

        $restaurants = $restaurantRepository->findAllRestaurantByBestOpinionOrderByOpinionDesc();
        dump($restaurants);

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/restaurant/{restaurant}', name: 'app_restaurant_id')]
    public function findRestaurantById(RestaurantRepository $restaurantRepository, Restaurant $restaurant): Response
    {
        $r = $restaurantRepository->findRestaurant($restaurant);

        return $this->render('restaurant.html.twig', [
            'restaurant' => $r,
        ]);
    }

    #[Route('/restaurant/{restaurant}/create/opinion', name: 'app_restaurant_id_create_opinion')]
    public function createOpinion(ManagerRegistry $doctrine, Request $request, Restaurant $restaurant): Response
    {
        $opinion = new Opinion();
        $opinion
            ->setUser($this->getUser())
            ->setRestaurant($restaurant);

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($opinion);
            $em->flush();
        }
        return $this->render('create_opinion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
