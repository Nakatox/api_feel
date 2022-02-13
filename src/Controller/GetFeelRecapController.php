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
                'description' => $data->getDescription(),
                'note' => $data->getNote(),
                'mood' => $data->getMood()->getName(),
                'createdAt' => $data->getDate(),
            ];
            return $array;
        }

        function getStats($data, $scale){

            function statsForEach($data){
                if (empty($data)) {
                    return 0;
                }
                $counterNote = 1;
                $amoutMood = [];
                $counterWordDescription = 1;
    
                foreach ($data as $feel) {
                    $counterNote += intval($feel["note"]);
                    if (!isset($amoutMood[$feel["mood"]])) {
                        $amoutMood[$feel["mood"]] = 1;
                    } else {
                        $amoutMood[$feel["mood"]]++;
                    }
                    $counterWordDescription += str_word_count($feel["description"]);
                }
                $counterNote = $counterNote / count($data);
                $counterWordDescription = $counterWordDescription / count($data);

                $array = [
                    'averageNote' => ceil($counterNote)-1,
                    'amoutMood' => $amoutMood,
                    'averageWordDescription' => ceil($counterWordDescription),
                ];
                return $array;
            }

            if ($scale == "month"){
                $rangeIteration = 4;
                $rangeBack = "week";
            } else if ($scale == "year"){
                $rangeIteration = 12;
                $rangeBack = "month";
            } else if ($scale == "week"){
                $rangeIteration = 7;
                $rangeBack = "day";
            } else if ($scale == "all"){
                $rangeIteration = 1;
                $rangeBack = "day";
            }

            $array = [];
            if ($scale !== "all"){
                for ($i = 1; $i <= $rangeIteration; $i++) {
                    $weekAgo = new \DateTime("-".$i." ".$rangeBack);
                    $weekAfter = new \DateTime("-".($i+1)." ".$rangeBack);
                    if ($i == 1){
                        $currentWeek = [];
                        foreach ($data as $key => $feel) {
                            if ($feel["createdAt"] > $weekAgo){
                                $currentWeek[] = $feel;
                            }
                        }
                        $array[$i] = statsForEach($currentWeek);
                    } else {
                        $currentWeek = [];
                        foreach ($data as $key => $feel) {
                            if ($feel["createdAt"] < $weekAgo && $feel["createdAt"] > $weekAfter){
                                $currentWeek[] = $feel;
                            }
                        }
                        $array[$i] = statsForEach($currentWeek);
                    }
                }
            } else {
                $array[1] = statsForEach($data);
            }

            return $array;
        }

        $pastWeek = new \DateTime("-1 week");
        $pastMonth = new \DateTime("-1 month");
        $pastYear = new \DateTime("-1 year");

        $result = [];
        $stats = [];
        
        foreach ($feels as $feel) {
            $feel = returnFeel($feel);
            if ($scale == "week"){
                if ($feel["createdAt"] > $pastWeek->format('Y-m-d H:i:s')) {
                    $result[] = $feel;
                }
            } else if ($scale == "month"){
                if ($feel["createdAt"] > $pastMonth->format('Y-m-d H:i:s')) {
                    $result[] = $feel;
                }
            } else if ($scale == "year"){
                if ($feel["createdAt"] > $pastYear->format('Y-m-d H:i:s')) {
                    $result[] = $feel;
                }
            } else {
                $result[] = $feel;
            }
        }
        $stats = getStats($result,$scale);

        return $this->json($stats);
    }
}
