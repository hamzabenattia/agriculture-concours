<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\Concours;
use App\Entity\Examen;
use App\Entity\Resultat;
use App\Entity\User;
use App\Repository\CandidatRepository;
use App\Repository\ConcoursRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{


    public function __construct(private ChartBuilderInterface $chartBuilder, private ConcoursRepository $concoursRepository, private CandidatRepository $candidatRepository ) {
        }

    #[Route('/dashboard', name: 'app_admin')]
    #[IsGranted(new Expression(
        'is_granted("ROLE_ADMIN") or is_granted("ROLE_DAF") or is_granted("ROLE_IRESA")'
    ))]

    public function index(): Response
    {
        $nombreConcours = $this->concoursRepository->findCandidatNumber();
        // $candidat = $this->candidatRepository->findAll();


        
       
        return $this->render('admin/my_dashboard.html.twig', [
            'nombreConcours' => $nombreConcours,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('
             <div class="d-flex align-items-center justify-content-center jus">
                <img src="images/tn_flag.png" class="w-25 h-25" />
                <img src="images/logo.png" alt="logo" class="w-25 h-25" />
            </div>

            <div class="text-center">
                <span>République Tunisienne</span>
                <span>Ministère de l’agriculture</span>
            </div>')
            ->setFaviconPath('images/logo.png')

            ;

    }




    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)->setPermission(User::ROLE_DAF);
        yield MenuItem::linkToCrud('Concours', 'fas fa-list', Concours::class)->setPermission(User::ROLE_DAF);
        yield MenuItem::linkToCrud('Candidatures', 'fas fa-clipboard', Candidat::class)->setPermission(User::ROLE_DAF);
        yield MenuItem::linkToCrud('Examen', 'fas fa-calendar-days', Examen::class)->setPermission(User::ROLE_DAF);
        yield MenuItem::linkToCrud('Resultat', 'fas fa-square-poll-vertical', Resultat::class)->setPermission(User::ROLE_DAF);
        yield MenuItem::linkToLogout('Se déconnecter', 'fa fa-fw fa-sign-out',);
    }

}
