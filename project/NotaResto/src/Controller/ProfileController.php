<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile', name: 'app_profile_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $r = User::class->getId()->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $r,
        ]);
    }
}
