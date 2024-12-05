<?php

namespace App\Twig\Components;

use App\Repository\ConcoursRepository;
use App\Repository\OffresRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent (name: 'concours_filter', template: '/components/concours_filter.html.twig')]
class ConcoursFillterComponents{

    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $type = "";

    #[LiveProp(writable: true)]
    public string $title = "";

    #[LiveProp(writable: true)]
    public string $status = "";



    public function __construct(private ConcoursRepository $concoursRepository)
    {
    }

    public function getConcours()
    {
        return $this->concoursRepository->search($this->type, $this->title, $this->status);
    }
}