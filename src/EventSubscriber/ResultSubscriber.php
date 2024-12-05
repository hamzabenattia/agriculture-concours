<?php 

namespace App\EventSubscriber;

use App\Entity\Examen;
use App\Entity\Notification;
use App\Entity\Resultat;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResultSubscriber implements EventSubscriberInterface
{
   

    public function __construct(private EmailSender $emailSender, private EntityManagerInterface $entityManagerInterface)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['sendResultNotification'],

        ];
    }



    public function sendResultNotification(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Resultat)) {
            return;
        }

        $candidate = $entity->getCandidat();

            $this->emailSender->sendEmail(
                'concours@agriculture.tn',
            $candidate->getUser()->getEmail(),  
            'Résultats de l\'examen du  '. $candidate->getConcours()->getTitle().' disponibles',
            'emails/result.html.twig',
            [
                'candidate' => $candidate,
            ],
            );
            
            $notification = new Notification();
            $notification->setUser($candidate->getUser());
            $notification->setMessage('Résultats de l\'examen du  '. $candidate->getConcours()->getTitle().' disponibles',        );
            $this->entityManagerInterface->persist($notification);
            $this->entityManagerInterface->flush();
 


    }
}