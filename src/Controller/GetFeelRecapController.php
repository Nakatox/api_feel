<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetFeelRecapController extends AbstractController
{
    public function __invoke($scale): Response
    {
        $feels = $this->getUser()->getFeels();

        function returnFeel($data){
            $array = [
                'id' => $data->getId(),
                'owner' => $data->getOwner()->getId(),
                'description' => $data->getDescription(),
                'note' => $data->getNote(),
                'mood' => $data->getMood()->getName(),
                'createdAt' => $data->getDate()->format('Y-m-d H:i:s'),
            ];
            return $array;
        }

        $today = new \DateTime();
        $pastWeek = new \DateTime("-1 week");
        $pastMonth = new \DateTime("-1 month");
        $pastYear = new \DateTime("-1 year");

        $result = [];
        $stats = [];
        
        if ($scale == "week"){
            foreach ($feels as $feel) {
                dd($feel);
                if ($feel->getDate() > $pastWeek) {
                    $result[] = $feel;
                }
            }
        }

        return $this->json($result);
    }
}
