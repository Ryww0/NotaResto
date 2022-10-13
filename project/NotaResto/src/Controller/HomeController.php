<?php

namespace App\Controller;

use App\Entity\Opinion;


use App\Entity\Restaurant;
use App\Form\OpinionType;
use App\Form\RechercheType;
use App\Form\RestaurantType;
use App\Repository\OpinionRepository;
use App\Repository\RestaurantRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function findRestaurantByBestOpinion(RestaurantRepository $restaurantRepository, Request $request): Response
    {

        $restaurants = $restaurantRepository->findAllRestaurantByBestOpinionOrderByOpinionDesc();

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/restaurant/{restaurant}', name: 'app_restaurant_id')]
    public function findRestaurantById(ManagerRegistry $doctrine, Request $request, RestaurantRepository $restaurantRepository, Restaurant $restaurant)
    {
        $r = $restaurantRepository->findRestaurant($restaurant);

        $opinion = new Opinion();
        $opinion
            ->setUser($this->getUser())
            ->setRestaurant($restaurant);

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $opinion = $form->getData();
            dump($opinion);
            if ($opinion->getNote() <= 5 && $opinion->getNote() >= 0) {
                $em = $doctrine->getManager();
                $em->persist($opinion);
                $em->flush();
//                return $this->redirectToRoute('app_restaurant_id', ['restaurant' => $restaurant->getId()])

                return new JsonResponse(['html' => $this->render('cards/card_opinion.html.twig', ['opinion' => $opinion])->getContent()]);
            }
        }

        return $this->render('restaurant.html.twig', [
            'restaurant' => $r,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/liste', name: 'app_liste_des_restaurant')]
    public function showAllRestaurant(RestaurantRepository $restaurantRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $restaurantRepository->findAllRestaurantOrderByOpinionDesc(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );

        $formR = $this->createForm(RechercheType::class);

        $formR->handleRequest($request);
        if ($formR->isSubmitted() && $formR->isValid()) {
            $s = $formR->getData()['search'];

            $pagination = $paginator->paginate(
                $restaurantRepository->findRestaurantWherePostcodeLikeSearch($s), /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                8/*limit per page*/
            );
        }


        return $this->render('view_all_restaurant.html.twig', [
            'formR' => $formR->createView(),
            'pagination' => $pagination
        ]);
    }

    #[Route('restaurateur/add/restaurant', name: 'app_create_restaurant')]
    public function createNewRestaurant(Request $request, ManagerRegistry $doctrine)
    {
        $resto = new Restaurant();
        $resto->setUser($this->getUser());

        $formResto = $this->createForm(RestaurantType::class, $resto);

        $formResto->handleRequest($request);
        if ($formResto->isSubmitted() && $formResto->isValid()) {
            $resto = $formResto->getData();

            $em = $doctrine->getManager();
            $em->persist($resto);
            $em->flush();
        }

        return $this->render('create_resto.html.twig', [
           'form' => $formResto->createView(),
        ]);
    }
}
