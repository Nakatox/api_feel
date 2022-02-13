<?php

namespace App\DataFixtures;

use App\Entity\Feel;
use App\Entity\Mood;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $moods = [
            'happy',
            'sad',
            'angry',
            'excited',
            'tired',
            'bored',
            'lonely',
            'scared',
            'stressed',
            'anxious',
            'nervous',
            'hopeful',
            'optimistic',
        ];
        foreach ($moods as $mood) {
            $newMood = new Mood();
            $newMood->setName($mood);
            $manager->persist($newMood);
        }
        $manager->flush();

        // create a user
        $user = new User();
        $user->setEmail('root@root.fr');
        $user->setPassword('$2y$13$xQcd5V2UyOuaqKmESi1Rq.ORoXVq1hbZCumCSkBbRqwujvr6qAG.O');
        $manager->persist($user);



        // create many feels for this user
        for ($i = 1; $i < 365; $i++) {
            $feel = new Feel();
            $feel->setDescription(str_repeat("Lorem ipsum dolor sit amet consectetur adipisicing elit. ", rand(1, 10)));
            $feel->setNote(rand(1, 10));
            $feel->setMood($manager->getRepository(Mood::class)->findOneBy(['name' => $moods[rand(0, count($moods) - 1)]]));
            $feel->setOwner($user);
            $feel->setDate(new \DateTime("-".$i." day"));
            $manager->persist($feel);
        }

        $manager->flush();
    }
}
