<?php

namespace App\Controller;

use App\Repository\FeelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetFeelController extends AbstractController
{
    public function __invoke(FeelRepository $feelRepository): Response
    {
        $feels = $feelRepository->findBy(['owner' => $this->getUser()],['date' => 'DESC']);
        return $this->json($feels);
    }
}
