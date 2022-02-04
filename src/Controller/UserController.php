<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UserController extends AbstractController
{
    public function __invoke(): Response
    {
        $user = $this->getUser();
        $array = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'feels' => $user->getFeels(),
        ];
        return $this->json($array);
    }
}
