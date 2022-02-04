<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher){
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        $this->hashPassword($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        $this->entityManager->remove($data);
        $this->entityManager->flush();

    }

    public function hashPassword(User $user){
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
    }
}