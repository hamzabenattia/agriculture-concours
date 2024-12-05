<?php 

namespace App\EventSubscriber;

use App\Entity\Examen;
use App\Entity\Notification;
use App\Entity\Resultat;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExamenSubscriber implements EventSubscriberInterface
{
   

    public function __construct(private EmailSender $emailSender, private EntityManagerInterface $entityManagerInterface)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['sendNotification'],
        ];
    }

    public function sendNotification(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Examen)) {
            return;
        }

        $concours = $entity->getConcours();
        $candidates = $concours->getCandidats();

    


        foreach ($candidates as $candidate) {
            
            $this->emailSender->sendEmail(
                'concours@agriculture.tn',
            $candidate->getUser()->getEmail(),  
            'Convocation à l\'examen du  '.$concours->getTitle(),
            'emails/examen_information.html.twig',
            [
                'examen' => $entity,
                'candidate' => $candidate,
            ],
            );
            
            $notification = new Notification();
            $notification->setUser($candidate->getUser());
            $notification->setMessage('Convocation à l\'examen du  '.$concours->getTitle(),        );
            $this->entityManagerInterface->persist($notification);
            $this->entityManagerInterface->flush();
 
        }
    }


}