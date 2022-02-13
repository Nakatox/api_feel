<?php

namespace App\Controller;

use App\Entity\Feel;
use App\Repository\FeelRepository;
use App\Repository\MoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class PostFeelController extends AbstractController
{
    public function __invoke($data,Request $request, EntityManagerInterface $entityManagerInterface, MoodRepository $moodRepository, FeelRepository $feelRepository): Response
    {
        $user = $this->getUser();
        $body = json_decode($request->getContent(), true);

        $today = new \DateTime();
        $firstFeel = $feelRepository->findOneBy(['date' => $today, "owner"=> $user]);

        if ($body['isCustom'] == true){
            $feelExist = $feelRepository->findOneBy(['owner' => $user, 'date' => new \DateTime($body['newDate'])]);
            if ($feelExist == null){

                $feel = new Feel();
                $feel->setDescription($body['description']);
                $feel->setNote($body['note']);
                $feel->setMood($moodRepository->findOneBy(['id' => substr($body['mood'], strrpos($body['mood'], '/') + 1)]));
                $feel->setOwner($user);
                $feel->setDate(new \DateTime($body['newDate']));
                $entityManagerInterface->persist($feel);
                $entityManagerInterface->flush();
                
                return $this->json(['status'=>200,'data'=>$feel], 200);
            } else {
                return $this->json(['status' => 400, 'error' => 'already'], Response::HTTP_BAD_REQUEST);
            }
        }
        if ($firstFeel == null){
            $data->setOwner($user);
            $entityManagerInterface->persist($data);
            $entityManagerInterface->flush();
        } else {
            return $this->json(['status'=> 400 ,'error' => 'only'], Response::HTTP_BAD_REQUEST);
        }
        $array = [
            'id' => $data->getId(),
            'owner' => $data->getOwner()->getId(),
            'description' => $data->getDescription(),
            'note' => $data->getNote(),
            'mood' => $data->getMood()->getName(),
            'createdAt' => $data->getDate()->format('Y-m-d H:i:s'),
        ];

        return $this->json(['status'=>200,'data'=>$array], 200);
    }
}
