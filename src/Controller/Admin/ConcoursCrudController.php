<?php

namespace App\Controller\Admin;

use App\Entity\Concours;
use App\Repository\ConcoursRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class ConcoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Concours::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            ChoiceField::new('type', 'Type')->setChoices([
                'Interne' => Concours::TYPE_INTERNE,
                'Externe' => Concours::TYPE_EXTERNE,
            ])->renderAsBadges([
                Concours::TYPE_INTERNE => 'primary',
                Concours::TYPE_EXTERNE => 'secondary',
            ]),

            TextEditorField::new('description', 'Description')->hideOnIndex(),
            DateField::new('startDate', 'Date de Début')->setColumns(3),
            DateField::new('endDate', 'Date de fin')->setColumns(3),
            FormField::addRow(),

            ChoiceField::new('status', 'Statut')->setChoices([
                'En cours' => Concours::STATUS_EN_COURS,
                'Terminé' => Concours::STATUS_TERMINE,
            ])->renderAsBadges(
                [
                    Concours::STATUS_EN_COURS => 'success',
                    Concours::STATUS_TERMINE => 'danger',
                ]
                )->setColumns(6),

                AssociationField::new('candidats')->hideOnForm()->setLabel('N° Candidats'),

            
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('type')->setChoices([
                'Interne' => Concours::TYPE_INTERNE,
                'Externe' => Concours::TYPE_EXTERNE,
            ]))
            ->add(ChoiceFilter::new('status')->setChoices([
                'En cours' => Concours::STATUS_EN_COURS,
                'Terminé' => Concours::STATUS_TERMINE,
            ]))

            ->add(DateTimeFilter::new('startDate', 'Date de Début'))

            ->add(DateTimeFilter::new('endDate', 'Date de fin'));
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->addBatchAction(Action::new('terminer', 'Terminer les concours')
        ->linkToCrudAction('terminerConcours')
        ->addCssClass('btn btn-warning')
        ->setIcon('fa fa-x'));
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
           
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setDefaultSort(['status' => 'ASC' , 'id' => 'DESC'])
            
                ;
    }


    public function terminerConcours(BatchActionDto $batchActionDto, ConcoursRepository $cr , EntityManagerInterface $entityManager)
{
    foreach ($batchActionDto->getEntityIds() as $id) {
        $concours = $cr->find($id);
        $concours->setStatus(Concours::STATUS_TERMINE);
    }

    $entityManager->flush();

    return $this->redirect($batchActionDto->getReferrerUrl());
}
}
