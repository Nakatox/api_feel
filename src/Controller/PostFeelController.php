<?php

namespace App\Controller;

use App\Entity\Feel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class PostFeelController extends AbstractController
{
    public function __invoke($data, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $data->setOwner($user);

        $entityManagerInterface->persist($data);
        $entityManagerInterface->flush();

        $array = [
            'id' => $data->getId(),
            'owner' => $data->getOwner()->getId(),
            'description' => $data->getDescription(),
            'note' => $data->getNote(),
            'mood' => $data->getMood()->getName(),
            'createdAt' => $data->getDate()->format('Y-m-d H:i:s'),
        ];
        return $this->json($array);
    }
}
