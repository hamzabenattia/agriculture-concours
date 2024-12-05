<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ResultatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ResultController extends AbstractController
{
    #[IsGranted(new Expression(
        'is_granted("ROLE_USER") or is_granted("ROLE_EMPLOYEE")'
    ))]

    #[Route('/mes-resultats', name: 'app_result')]
    public function index(#[CurrentUser] User $user, ResultatRepository $resultatRepository): Response
    {
        
        return $this->render('user/my_result.html.twig', [
            'results' => $resultatRepository->findByUser($user),
        ]);
    }



}


