<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Concours;
use App\Entity\User;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CandidateController extends AbstractController
{

    public function __construct(private EmailSender $emailSender)
    {
    }


    #[Route('/candidate/{id}', name: 'app_candidate')]
    public function index(Request $request, EntityManagerInterface $em, Concours $concours,  #[CurrentUser] User $user, CandidatRepository $cr): Response
    {


            $candidate = new Candidat();
            $form = $this->createForm(CandidatType::class, $candidate);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $candidate->setConcours($concours);
                $candidate->setUser($user);
                $em->persist($candidate);
                $em->flush();
    
                $this->addFlash('success', 'Votre candidature a bien été enregistrée');

                $this->emailSender->sendEmail(
                    new Address('noreply@agriculture.tn', 'noreply'),
                    $candidate->getUser()->getEmail(),
                    'Votre candidature a bien été enregistrée',
                    'emails/candidat_offre.html.twig',
                    [
                        'candidate' => $candidate,
                        'concours' => $concours,
                    ]
                );

    
                return $this->redirectToRoute('app_home');
            }


            $userAlreadyApplied = $cr->findOneBy([
                'concours' => $concours,
                'user' => $user,
            ]);
           
            return $this->render('candidate/index.html.twig', [
                'form' => $form,
                'concours' => $concours,
                'userAlreadyApplied' => $userAlreadyApplied

    
            ]);
        }



        #[Route('mes-candidatures', name: 'app_user_candidate_list', methods: ['GET'])]
        public function myCandidate(CandidatRepository $candidateRepository , #[CurrentUser] User $user , PaginatorInterface $paginator, Request $request): Response
        {
    
            $candidate = $paginator->paginate(
                $candidateRepository->findBy(['user' => $user], ['createdAt' => 'DESC']),
                $request->query->getInt('page', 1),
                5
            );
    
            return $this->render('user/my_candidate.html.twig', [
                'candidates' => $candidate,
            ]);
        }


        #[Route('candidat-remove/{id}', name: 'app_candidat_delete', methods: ['POST'])]
        #[IsGranted(
            attribute: new Expression('user === subject'),
            subject: new Expression('args["candidat"].getUser()'),
        )]        public function delete(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->getPayload()->get('_token'))) {
                $entityManager->remove($candidat);
                $entityManager->flush();
                $this->addFlash('success', 'Votre candidature a été supprimer avec succès');
            }
    
    
            return $this->redirectToRoute('app_user_candidate_list', [], Response::HTTP_SEE_OTHER);
        }       


}
