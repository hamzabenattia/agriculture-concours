<?php

namespace App\Controller\Admin;

use App\Entity\Resultat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class ResultatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resultat::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('candidat','Candidat'),
            AssociationField::new('examen','Examen'),
            NumberField::new('note','Note'),
            ChoiceField::new('status', 'Statut')->setChoices([
                'Réussi' => 'Réussi',
                'Échoué' => 'Échoué',
            ])->renderAsBadges([
                'Réussi' => 'success',
                'Échoué' => 'danger',
            ]),
            DateField::new('created_at','Date de publication')->hideOnForm(),
        ];
    }
}
