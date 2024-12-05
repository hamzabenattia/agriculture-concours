<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\Notification;
use App\Repository\CandidatRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CandidatCrudController extends AbstractCrudController
{
      public function __construct(private EmailSender $emailSender)
      {

      }


    public static function getEntityFqcn(): string
    {
        return Candidat::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Détaille de candiature'),
            IdField::new('id', 'ID')->hideOnForm(),
 

            ChoiceField::new('status', 'Statut')->setChoices([
                Candidat::STATUS_EN_ATTENTE => Candidat::STATUS_EN_ATTENTE,
                Candidat::STATUS_ACCEPTED => Candidat::STATUS_ACCEPTED,
                Candidat::STATUS_REJECTED => Candidat::STATUS_REJECTED,
            ])->renderAsBadges(
                [
                    Candidat::STATUS_ACCEPTED => 'success',
                    Candidat::STATUS_REJECTED => 'danger',
                    Candidat::STATUS_EN_ATTENTE => 'info'
                ]
                ),
            DateField::new('created_at','Date de postulation')->hideOnForm(),


            FormField::addTab('Détaille de concours')->onlyOnDetail(),

            AssociationField::new('concours', 'Titre de concours')->hideOnForm(),
            TextField::new('concours.type', 'Type de concours')->onlyOnDetail(),
            DateField::new('concours.startDate', 'Date de Début')->onlyOnDetail(),
            DateField::new('concours.endDate', 'Date de fin')->onlyOnDetail(),
            TextField::new('concours.status', 'Statut de concours')->onlyOnDetail(),
            FormField::addTab('Détaille de candidate')->onlyOnDetail(),

            TextField::new('user.firstName','Prénom de candidat')->onlyOnDetail(),
            TextField::new('user.lastName','Nom de candidat')->onlyOnDetail(),
            TextField::new('user.email','Email de candidat')->onlyOnDetail(),
            TextField::new('phoneNumber','Téléphone')->onlyOnDetail(),
            TextField::new('ville','Ville')->onlyOnDetail(),
            TextField::new('adresse','Adresse')->onlyOnDetail(),
            AssociationField::new('user', 'Candidat')->onlyOnIndex(),



        ];
    }




    public function configureCrud(Crud $crud): Crud
{
    return $crud
       
        ->setPageTitle('index', 'Liste des %entity_label_plural%')
        ->setPageTitle('edit','Validation du candidatures')
        
            ;
}


public function configureActions(Actions $actions): Actions
{
    $viewCV = Action::new('Télecharger le Cv', 'Télecharger le CV', 'fa fa-download')
    ->linkToUrl(fn ($entity) => '/files/cv/' . $entity->getCvName());


    return $actions
        ->remove(Crud::PAGE_INDEX, Action::NEW)
        ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ->remove(Crud::PAGE_DETAIL, Action::EDIT)


        ->add(Crud::PAGE_INDEX, $viewCV)
        ->add(Crud::PAGE_DETAIL, $viewCV)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)



        ->addBatchAction(Action::new('reject', 'Rejeter les candidatures')
        ->linkToCrudAction('rejectUsers')
        ->addCssClass('btn btn-danger')
        ->setIcon('fa fa-ban'))
        ->addBatchAction(Action::new('approve', 'Accepter les candidatures')
        ->linkToCrudAction('approveUsers')
        ->addCssClass('btn btn-primary')
        ->setIcon('fa fa-check'))
        
        
    
        ;
}


public function approveUsers(BatchActionDto $batchActionDto, CandidatRepository $candidatRepository , EntityManagerInterface $entityManager)
{
    foreach ($batchActionDto->getEntityIds() as $id) {
        $candidat = $candidatRepository->find($id);
        $candidat->setStatus(Candidat::STATUS_ACCEPTED);

        $this->emailSender->sendEmail(
            'concours@agriculture.tn',
            $candidat->getUser()->getEmail(),  
            'Confirmation de votre acceptation à '.$candidat->getConcours()->getTitle(),
            'emails/candidat_accept.html.twig',
            [
                'candidate' => $candidat,
            ],
            );

            $notification = new Notification();
            $notification->setUser($candidat->getUser());
            $notification->setMessage('Confirmation de votre acceptation à '.$candidat->getConcours()->getTitle()       );
        
            $entityManager->persist($notification);
            $entityManager->flush();
        
    }


    $entityManager->flush();

   

    return $this->redirect($batchActionDto->getReferrerUrl());
}


public function rejectUsers(BatchActionDto $batchActionDto, CandidatRepository $candidatRepository , EntityManagerInterface $entityManager)
{
    foreach ($batchActionDto->getEntityIds() as $id) {
        $candidat = $candidatRepository->find($id);
        $candidat->setStatus(Candidat::STATUS_REJECTED);

        $this->emailSender->sendEmail(
            'concours@agriculture.tn',
            $candidat->getUser()->getEmail(),  
            'Résultat de votre candidature à '.$candidat->getConcours()->getTitle(),
            'emails/candidat_refuse.html.twig',
            [
                'candidate' => $candidat,
            ],
            );

            $notification = new Notification();
            $notification->setUser($candidat->getUser());
            $notification->setMessage('Résultat de votre candidature à '.$candidat->getConcours()->getTitle());
            $entityManager->persist($notification);
            $entityManager->flush();
        
    }


    $entityManager->flush();

    return $this->redirect($batchActionDto->getReferrerUrl());
}






public function configureFilters(Filters $filters): Filters
{
    return $filters
        ->add(ChoiceFilter::new('status')->setChoices([
            Candidat::STATUS_EN_ATTENTE => Candidat::STATUS_EN_ATTENTE,
            Candidat::STATUS_ACCEPTED => Candidat::STATUS_ACCEPTED,
            Candidat::STATUS_REJECTED => Candidat::STATUS_REJECTED,
        ]))
        ->add(EntityFilter::new('concours'))
        ->add(EntityFilter::new('user','Candidat'))
               ;
        }


}
