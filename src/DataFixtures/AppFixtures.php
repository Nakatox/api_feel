<?php

namespace App\DataFixtures;

use App\Entity\Mood;
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
    }
}
